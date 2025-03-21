<?php
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

$bdd = dbConnect();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["IdProductAModifier"])) {
    $Id_Produit = htmlspecialchars($_POST["IdProductAModifier"]);
    $Nom_Produit = htmlspecialchars($_POST["nomProduit"]);
    $Categorie = htmlspecialchars($_POST["categorie"]);
    $Prix = htmlspecialchars($_POST["prix"]);
    $Prix_Unite = htmlspecialchars($_POST["unitPrix"]);
    $Quantite = htmlspecialchars($_POST["quantite"]);
    $Quantite_Unite = htmlspecialchars($_POST["unitQuantite"]);

    $updateProduit = "UPDATE PRODUIT SET 
        Nom_Produit = :Nom_Produit, 
        Id_Type_Produit = :Categorie, 
        Qte_Produit = :Quantite, 
        Id_Unite_Stock = :Quantite_Unite, 
        Prix_Produit_Unitaire = :Prix, 
        Id_unite_Prix = :Prix_Unite 
        WHERE Id_Produit = :Id_Produit";

    $stmt = $bdd->prepare($updateProduit);
    $stmt->bindParam(':Nom_Produit', $Nom_Produit);
    $stmt->bindParam(':Categorie', $Categorie);
    $stmt->bindParam(':Quantite', $Quantite);
    $stmt->bindParam(':Quantite_Unite', $Quantite_Unite);
    $stmt->bindParam(':Prix', $Prix);
    $stmt->bindParam(':Prix_Unite', $Prix_Unite);
    $stmt->bindParam(':Id_Produit', $Id_Produit);
    $stmt->execute();

    // Gestion de l'image
    if (isset($_FILES["image"]) && $_FILES["image"]["size"] > 0) {
        $targetDir = __DIR__ . "/img_produit/";
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $newFileName = $Id_Produit . ".png";
        $targetPath = $targetDir . $newFileName;

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            // Upload OK
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    }

    header("Location: produits.php");
    exit();
} else {
    echo "Erreur : données manquantes.";
}
