<?php
include_once __DIR__ . "/../loadenv.php";

if (!isset($_SESSION)) {
    session_start();
}
// Vérification si un identifiant utilisateur est fourni en POST, sinon utiliser l'utilisateur connecté
$userId = $_POST["targetID"] ?? null;
$userId = htmlspecialchars($userId);  // Sécurisation de l'entrée utilisateur
if($_SESSION["Id_Uti"] != $userId){
  $delParAdmin = true;
}

// Récupération des informations de l'utilisateur
$user = $db->select('SELECT Id_Uti, Prenom_Uti, Nom_Uti, Mail_Uti, Adr_Uti FROM UTILISATEUR WHERE Id_Uti = ?', "s", [$userId]) ?? null;

if (!$user) {
    // Redirection et sortie si l'utilisateur n'existe pas
    header('Location: ' . ($delParAdmin ? '/panel_admin.php' : '/traitements/logout.php'));
    exit;
}
$db->query('DELETE FROM ADMINISTRATEUR WHERE Id_Uti = ?', 'i', [$userId]);
// Vérification si l'utilisateur est un producteur
$producteurInfo = $db->select('SELECT Id_Prod, Id_Uti, Prof_Prod FROM PRODUCTEUR WHERE Id_Uti = ?', 's', [$userId])[0] ?? null;
$isProducteur = $producteurInfo !== null;

if (!$isProducteur) {  
    // L'utilisateur n'est PAS producteur : suppression des commandes et des données associées
    $commandes = $db->select('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes WHERE Id_Uti = ?', 's', [$userId]);

    foreach ($commandes as $commande) {
        $db->query('UPDATE PRODUIT SET Qte_Produit = Qte_Produit + ? WHERE Id_Produit = ?', 'ii', [$commande["Qte_Produit_Commande"], $commande["Id_Produit"]]);
        $db->query('DELETE FROM CONTENU WHERE Id_Produit = ?', 'i', [$commande["Id_Produit"]]);
    }

    // Suppression des commandes, messages et utilisateur
    $db->query('DELETE FROM COMMANDE WHERE Id_Uti = ?', 'i', [$userId]);
    $db->query('DELETE FROM MESSAGE WHERE Emetteur = ? OR Destinataire = ?', 'ii', [$userId, $userId]);
    $db->query('DELETE FROM UTILISATEUR WHERE Id_Uti = ?', 'i', [$userId]);

} else {  
    // Suppression des produits et des commandes liées s'il est producteur
    $idProd = $producteurInfo["Id_Prod"];
    $produits = $db->select('SELECT Id_Produit FROM PRODUIT WHERE Id_Prod = ?', 's', [$idProd]);

    foreach ($produits as $produit) {
        $db->query('DELETE FROM CONTENU WHERE Id_Produit = ?', 'i', [$produit["Id_Produit"]]);
        $db->query('DELETE FROM PRODUIT WHERE Id_Produit = ?', 'i', [$produit["Id_Produit"]]);
    }

    // Suppression des commandes, messages, producteur et utilisateur
    $db->query('DELETE FROM COMMANDE WHERE Id_Uti = ?', 'i', [$userId]);
    $db->query('DELETE FROM COMMANDE WHERE Id_Prod = ?', 'i', [$idProd]);

    $db->query('DELETE FROM MESSAGE WHERE Emetteur = ? OR Destinataire = ?', 'ii', [$userId, $userId]);

    $db->query('DELETE FROM PRODUCTEUR WHERE Id_Uti = ?', 'i', [$userId]);
    $db->query('DELETE FROM UTILISATEUR WHERE Id_Uti = ?', 'i', [$userId]);

    // Suppression de l'image du producteur
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/img_producteur/" . $userId . ".png";
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

}

// Redirection après traitement

header('Location: ' . ($delParAdmin ? 'panel_admin.php' : 'traitements/logout.php'));
exit;
?>