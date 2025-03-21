<?php
include_once __DIR__ . "/loadenv.php";

if (!isset($_SESSION)) {
    session_start();
}
if (!(isset($_SESSION['Id_Uti'])) || !isset($_SESSION['isProd'])){
    header("Location: index.php");
    exit();
}

$Id_Uti = htmlspecialchars($_SESSION["Id_Uti"]);
$Url = $_GET;
$Id_Prod = intval($Url["Id_Prod"]); // Conversion en entier pour la sécurité
unset($Url["Id_Prod"]);

$i = 1;

// Récupération du dernier numéro de commande
$returnqueryNbCommandes = $db->select('SELECT MAX(Id_Commande) AS maxId FROM COMMANDE;')[0];
$nbCommandes = ($returnqueryNbCommandes["maxId"] ?? 0) + 1;

// Insertion de la commande
$db->query('INSERT INTO COMMANDE (Id_Commande, Id_Statut, Id_Prod, Id_Uti) VALUES (?, 1, ?, ?)', 'iii', [$nbCommandes, $Id_Prod, $Id_Uti]);
$insertionCommande = "INSERT INTO COMMANDE (Id_Commande, Id_Statut, Id_Prod, Id_Uti) 
                      VALUES (:nbCommandes, 1, :Id_Prod, :Id_Uti)";

// Parcours des produits commandés
foreach ($Url as $produit => $quantite) {
    $produit = intval(str_replace("quantite_", "", $produit)); // Extraction de l'ID produit
    $quantite = str_replace(",", ".", trim($quantite)); // Remplacement de la virgule par un point

    // Vérifie si la quantité est bien numérique et supérieure à 0
    if (is_numeric($quantite) && floatval($quantite) > 0) {
        // Insertion du produit dans la commande
        $db->query('INSERT INTO CONTENU (Id_Commande, Id_Produit, Qte_Produit_Commande, Num_Produit_Commande) VALUES (?, ?, ?, ?)', 'iidi', [$nbCommandes, $produit, $quantite, $i]);

        // Mise à jour du stock
        $db->query('UPDATE PRODUIT SET Qte_Produit = Qte_Produit - ? WHERE Id_Produit = ?', 'di', [$quantite, $produit]);

        $i++;
    }
}

// Redirection après la commande
header('Location: achats.php');
exit();
