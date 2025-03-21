<?php
include_once __DIR__ . "/loadenv.php";
?>
<?php
function dbConnect()
{
  $utilisateur = $_ENV['DB_USER'];
  $serveur = $_ENV['DB_HOST'];
  $motdepasse = $_ENV['DB_PASS'];
  $basededonnees = $_ENV['DB_NAME'];
  // Connect to database
  return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
}
$bdd = dbConnect();
$Id_Produit = htmlspecialchars($_POST["deleteIdProduct"]);

$delContenu = $bdd->prepare('DELETE FROM CONTENU WHERE Id_Produit=:Id_Produit;');
$delContenu->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
$delContenu->execute();

$delProduct = $bdd->prepare('DELETE FROM PRODUIT WHERE Id_Produit=:Id_Produit;');
$delProduct->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
$delProduct->execute();

// suppression de l'image (path à changer sur le serveur !!!!)
$imgpath = "img_produit/" . $Id_Produit . ".png";
//echo $imgpath;
unlink($imgpath);
header('Location: produits.php');
?>