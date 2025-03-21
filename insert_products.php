<?php
require "language.php";
include_once __DIR__ . "/loadenv.php";

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['Id_Uti']) || $_SESSION['isProd'] == false ){
    header("Location: index.php");
    exit();
}

$Id_Uti = htmlspecialchars($_SESSION["Id_Uti"]);

// Récupérer l'ID du producteur
$returnQueryIdProd = $db->select('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti = ?', 'i', [$Id_Uti])[0];

if (!$returnQueryIdProd) {
    die("Erreur : Producteur introuvable.");
}

$IdProd = $returnQueryIdProd["Id_Prod"];

// Récupérer le prochain ID de produit
$returnqueryNbProduits = $db->select('SELECT COALESCE(MAX(Id_Produit), 0) + 1 AS nextId FROM PRODUIT;')[0];
$nbProduits = $returnqueryNbProduits["nextId"];

// Récupérer les données du formulaire
$Nom_Produit = htmlspecialchars($_POST["nomProduit"]);
$Type_De_Produit = intval($_POST["categorie"]);
$Prix = floatval($_POST["prix"]);
$Unite_Prix = htmlspecialchars($_POST["unitPrix"]);
$Quantite = floatval($_POST["quantite"]);

// Vérifier que l'unité de prix existe bien dans la base de données
$returnQueryCheckUnite = $db->select('SELECT Id_Unite FROM UNITE WHERE Nom_Unite = ?', 's', [$Unite_Prix])[0];

if (!$returnQueryCheckUnite) {
    die("Erreur : L'unité de prix sélectionnée n'existe pas.");
}

$Unite_Prix_ID = $returnQueryCheckUnite["Id_Unite"];
$Unite_Stock_ID = $Unite_Prix_ID; // L'unité de stock est la même que l'unité de prix

// Insertion du produit dans la base de données
$db->query('INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)', 'isiididi', [$nbProduits, $Nom_Produit, $Type_De_Produit, $IdProd, $Quantite, $Unite_Stock_ID, $Prix, $Unite_Prix_ID]);

// Gestion de l'image du produit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"]) && $_FILES["image"]["size"] > 0) {
    $targetDir = __DIR__ . "/img_produit/";
    $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $newFileName = $nbProduits . '.' . $extension;
    $targetPath = $targetDir . $newFileName;

    if (file_exists($targetPath)) {
        unlink($targetPath);
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
        echo "<br>" . $htmlImgTelecSucces . " : " . $newFileName . "<br>";
    } else {
        echo $htmlImgTelecRate . error_get_last()['message'] . "<br>";
        header('Location: produits.php?erreur=' . error_get_last()['message']);
        exit();
    }
}

header('Location: produits.php');
exit();
?>
