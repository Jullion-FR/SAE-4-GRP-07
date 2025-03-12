<?php
    require "language.php" ; 
    include_once __DIR__ . "/../../loadenv.php";
?>
<?php

$email = $_POST["email"];
$_SESSION["mailTemp"]=$email;

$utilisateur = $_ENV['DB_USER'];
$serveur = $_ENV['DB_HOST'];
$motdepasse = $_ENV['DB_PASS'];
$basededonnees = $_ENV['DB_NAME'];
$bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

// Vérifiez d'abord si l'adresse e-mail existe déjà dans la table UTILISATEUR
$checkEmailQuery = "SELECT COUNT(*) AS count FROM UTILISATEUR WHERE Mail_Uti = :mail";
$checkEmailStmt = $bdd->prepare($checkEmailQuery);
$checkEmailStmt->bindParam(':mail', $_SESSION["mailTemp"]);
$checkEmailStmt->execute();
$emailCount = $checkEmailStmt->fetch(PDO::FETCH_ASSOC)['count'];

if ($emailCount > 0) {  
    // Génération d'un code aléatoire à 6 chiffres
    $code = rand(100000, 999999);
    $_SESSION["code"]=$code;
    // Envoi du code par e-mail
    $subject = $htmlReinVotreMdp;
    $message = $htmlTonMdpEst.$code;
    $headers = "From: no-reply@letalenligne.com";

    // Envoi de l'e-mail
    $mailSent = mail($email, $subject, $message, $headers);

    // Redirection
    if ($mailSent) {
        $_POST['popup'] = 'mdp_oublie/code';
    } else {
        $_SESSION['erreur'] = $htmlErreurMailIncorrect;
    }
}else {
    $_SESSION['erreur'] = $htmlPasMailDansBDD;
}
?>
