<?php
include_once __DIR__ . "/loadenv.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["IdProductAModifier"])) {
    $Id_Produit = htmlspecialchars($_POST["IdProductAModifier"]);
    $Nom_Produit = htmlspecialchars($_POST["nomProduit"]);
    $Categorie = htmlspecialchars($_POST["categorie"]);
    $Prix = htmlspecialchars($_POST["prix"]);
    $Prix_Unite = htmlspecialchars($_POST["unitPrix"]);
    $Quantite = htmlspecialchars($_POST["quantite"]);
    $Quantite_Unite = htmlspecialchars($_POST["unitQuantite"]);

    $updateProduit = "UPDATE PRODUIT SET 
        Nom_Produit = ?, 
        Id_Type_Produit = ?, 
        Qte_Produit = ?, 
        Id_Unite_Stock = ?, 
        Prix_Produit_Unitaire = ?, 
        Id_unite_Prix = ? 
        WHERE Id_Produit = ?";

    $db->query($updateProduit, 'sididii', [
        $Nom_Produit,
        $Categorie,
        $Quantite,
        $Quantite_Unite,
        $Prix,
        $Prix_Unite,
        $Id_Produit
    ]);

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
