<?php
include_once __DIR__ . "/loadenv.php";

function dbConnect()
{
    $utilisateur = $_ENV['DB_USER'];
    $serveur = $_ENV['DB_HOST'];
    $motdepasse = $_ENV['DB_PASS'];
    $basededonnees = $_ENV['DB_NAME'];

    // Connect to database
    return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL
    ]);
}

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
$bdd = dbConnect();

// Récupération du dernier numéro de commande
$queryNbCommandes = $bdd->query('SELECT MAX(Id_Commande) AS maxId FROM COMMANDE;');
$returnqueryNbCommandes = $queryNbCommandes->fetch(PDO::FETCH_ASSOC);
$nbCommandes = ($returnqueryNbCommandes["maxId"] ?? 0) + 1;

// Insertion de la commande
$insertionCommande = "INSERT INTO COMMANDE (Id_Commande, Id_Statut, Id_Prod, Id_Uti) 
                      VALUES (:nbCommandes, 1, :Id_Prod, :Id_Uti)";
$bindInsertionCommande = $bdd->prepare($insertionCommande);
$bindInsertionCommande->bindParam(':nbCommandes', $nbCommandes, PDO::PARAM_INT);
$bindInsertionCommande->bindParam(':Id_Prod', $Id_Prod, PDO::PARAM_INT);
$bindInsertionCommande->bindParam(':Id_Uti', $Id_Uti, PDO::PARAM_INT);
$bindInsertionCommande->execute();

// Parcours des produits commandés
foreach ($Url as $produit => $quantite) {
    $produit = intval(str_replace("quantite_", "", $produit)); // Extraction de l'ID produit
    $quantite = str_replace(",", ".", trim($quantite)); // Remplacement de la virgule par un point

    // Vérifie si la quantité est bien numérique et supérieure à 0
    if (is_numeric($quantite) && floatval($quantite) > 0) {
        // Insertion du produit dans la commande
        $insertionProduit = "INSERT INTO CONTENU (Id_Commande, Id_Produit, Qte_Produit_Commande, Num_Produit_Commande) 
                             VALUES (:nbCommandes, :produit, :quantite, :i)";
        $bindInsertionProduit = $bdd->prepare($insertionProduit);
        $bindInsertionProduit->bindParam(':nbCommandes', $nbCommandes, PDO::PARAM_INT);
        $bindInsertionProduit->bindParam(':produit', $produit, PDO::PARAM_INT);
        $bindInsertionProduit->bindParam(':quantite', $quantite, PDO::PARAM_STR); // UTILISATION DE PARAM_STR POUR LES DÉCIMAUX
        $bindInsertionProduit->bindParam(':i', $i, PDO::PARAM_INT);
        $bindInsertionProduit->execute();

        // Mise à jour du stock
        $updateProduit = "UPDATE PRODUIT SET Qte_Produit = Qte_Produit - :quantite WHERE Id_Produit = :produit";
        $bindUpdateProduit = $bdd->prepare($updateProduit);
        $bindUpdateProduit->bindParam(':quantite', $quantite, PDO::PARAM_STR); // UTILISATION DE PARAM_STR POUR LES DÉCIMAUX
        $bindUpdateProduit->bindParam(':produit', $produit, PDO::PARAM_INT);
        $bindUpdateProduit->execute();

        $i++;
    }
}

// Redirection après la commande
header('Location: achats.php');
exit();
