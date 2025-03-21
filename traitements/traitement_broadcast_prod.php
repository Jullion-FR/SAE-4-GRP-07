<?php
include_once __DIR__ . "/../loadenv.php";
?>
<?php

if(!isset($_SESSION)){
        session_start();
        }

// Database connection
$utilisateur = $_ENV['DB_USER'];
$serveur = $_ENV['DB_HOST'];
$motdepasse = $_ENV['DB_PASS'];
$basededonnees = $_ENV['DB_NAME'];
// Connect to database
$bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  $message = $bdd->quote($message);

  $bdd->query('CALL broadcast_Producteur(' . $_SESSION["Id_Uti"] . ', ' . $message . ');');
  header("Location: ../messagerie.php");
} else {
    echo "error";
    echo $message;
    var_dump(isset($_SESSION["Id_Uti"]));
    var_dump(isset($message));

  }
  
  ?>