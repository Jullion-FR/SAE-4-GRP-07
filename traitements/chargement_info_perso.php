<?php
include_once __DIR__ . "/../loadenv.php";
?>
<?php
$utilisateur = $_ENV['DB_USER'];
$serveur = $_ENV['DB_HOST'];
$motdepasse = $_ENV['DB_PASS'];
$basededonnees = $_ENV['DB_NAME'];
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Vérifiez la connexion
if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);  
}
// Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
$requete = 'SELECT * FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=?';
$stmt = $connexion->prepare($requete);
$stmt->bind_param("s", $_SESSION['Mail_Uti']); // "s" indique que la valeur est une chaîne de caractères
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$connexion->close();
?>