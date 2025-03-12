<?php
include_once __DIR__ . "/../loadenv.php";
?>
<?php


$utilisateur = $_ENV['DB_USER'];
$serveur = $_ENV['DB_HOST'];
$motdepasse = $_ENV['DB_PASS'];
$basededonnees = $_ENV['DB_NAME'];
$bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  
  $bdd->query('CALL broadcast_admin(' . $_SESSION["Id_Uti"] . ', \'' . $message . '\');');
} else {
  
  $bdd->query('CALL broadcast_admin(0 , \''. $_POST["mail"]. $message . '\');');
}

