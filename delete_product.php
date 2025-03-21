<?php
include_once __DIR__ . "/loadenv.php";
?>
<?php

$Id_Produit = htmlspecialchars($_POST["deleteIdProduct"]);

$db->query("DELETE FROM CONTENU WHERE Id_Produit = ?", 'i', [$Id_Produit]);

$db->query("DELETE FROM PRODUIT WHERE Id_Produit = ?", 'i', [$Id_Produit]);

// suppression de l'image (path à changer sur le serveur !!!!)
$imgpath = "img_produit/" . $Id_Produit . ".png";
//echo $imgpath;
unlink($imgpath);
header('Location: produits.php');
?>