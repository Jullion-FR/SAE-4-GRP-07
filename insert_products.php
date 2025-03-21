<?php
require "language.php";
include_once __DIR__ . "/loadenv.php";

function dbConnect()
{
    $utilisateur = $_ENV['DB_USER'];
    $serveur = $_ENV['DB_HOST'];
    $motdepasse = $_ENV['DB_PASS'];
    $basededonnees = $_ENV['DB_NAME'];
    return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
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
$bdd = dbConnect();

// Récupérer l'ID du producteur
$queryIdProd = $bdd->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti=:Id_Uti;');
$queryIdProd->bindParam(":Id_Uti", $Id_Uti, PDO::PARAM_INT);
$queryIdProd->execute();
$returnQueryIdProd = $queryIdProd->fetch(PDO::FETCH_ASSOC);

if (!$returnQueryIdProd) {
    die("Erreur : Producteur introuvable.");
}

$IdProd = $returnQueryIdProd["Id_Prod"];

// Récupérer le prochain ID de produit
$queryNbProduits = $bdd->query('SELECT COALESCE(MAX(Id_Produit), 0) + 1 AS nextId FROM PRODUIT;');
$returnqueryNbProduits = $queryNbProduits->fetch(PDO::FETCH_ASSOC);
$nbProduits = $returnqueryNbProduits["nextId"];

// Récupérer les données du formulaire
$Nom_Produit = htmlspecialchars($_POST["nomProduit"]);
$Type_De_Produit = intval($_POST["categorie"]);
$Prix = floatval($_POST["prix"]);
$Unite_Prix = htmlspecialchars($_POST["unitPrix"]);
$Quantite = floatval($_POST["quantite"]);

// Vérifier que l'unité de prix existe bien dans la base de données
$queryCheckUnite = $bdd->prepare('SELECT Id_Unite FROM UNITE WHERE Nom_Unite = :Unite_Prix;');
$queryCheckUnite->bindParam(":Unite_Prix", $Unite_Prix, PDO::PARAM_STR);
$queryCheckUnite->execute();
$returnQueryCheckUnite = $queryCheckUnite->fetch(PDO::FETCH_ASSOC);

if (!$returnQueryCheckUnite) {
    die("Erreur : L'unité de prix sélectionnée n'existe pas.");
}

$Unite_Prix_ID = $returnQueryCheckUnite["Id_Unite"];
$Unite_Stock_ID = $Unite_Prix_ID; // L'unité de stock est la même que l'unité de prix

// Insertion du produit dans la base de données
$insertionProduit = "INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix) 
VALUES (:nbProduits, :Nom_Produit, :Type_De_Produit, :IdProd, :Quantite, :Unite_Stock_ID, :Prix, :Unite_Prix_ID);";

$bindInsertProduct = $bdd->prepare($insertionProduit);
$bindInsertProduct->bindParam(':nbProduits', $nbProduits, PDO::PARAM_INT);
$bindInsertProduct->bindParam(':Nom_Produit', $Nom_Produit, PDO::PARAM_STR);
$bindInsertProduct->bindParam(':Type_De_Produit', $Type_De_Produit, PDO::PARAM_INT);
$bindInsertProduct->bindParam(':IdProd', $IdProd, PDO::PARAM_INT);
$bindInsertProduct->bindParam(':Quantite', $Quantite, PDO::PARAM_STR);
$bindInsertProduct->bindParam(':Unite_Stock_ID', $Unite_Stock_ID, PDO::PARAM_INT);
$bindInsertProduct->bindParam(':Prix', $Prix, PDO::PARAM_STR);
$bindInsertProduct->bindParam(':Unite_Prix_ID', $Unite_Prix_ID, PDO::PARAM_INT);
$bindInsertProduct->execute();

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
