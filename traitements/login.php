<?php

// Exit if no data
if (!($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'], $_POST['redirect']))) {
    header('Location: index.php');
    exit;
}

// Require
session_start();
require_once __DIR__ . "/../language.php";
require_once __DIR__ . "/../loadenv.php";

$password = $_POST['password'];
$email = $_POST['email'];

// Gestion des tentatives de connexion
$_SESSION['test_pwd'] = $_SESSION['test_pwd'] ?? 5;

// Vérification de l'existence de l'utilisateur
$user = $db->select("SELECT Id_Uti FROM UTILISATEUR WHERE Mail_Uti = ?", "s", [$email]);
if (!$user) {
    $_SESSION['erreur'] = $htmlAdresseMailInvalide;
    header('Location: ../login.php');
    exit();
}
$idUti = $user[0]['Id_Uti'];

// Vérification du nombre de mot de passe tester
if ($_SESSION['test_pwd'] > 300) {
    $_SESSION['erreur'] = $htmlErreurMaxReponsesAtteintes;
    header('Location: ../login.php');
    exit();
}

// Vérification du mot de passe via une procédure stockée
$result = $db->select("CALL verifMotDePasse(?, ?)", "is", [$idUti, $password]);
if (!empty($result) && (isset($result[0]["1"]) && $result[0]["1"] == 1 || isset($result[0]["0"]) && $result[0]["0"] == 1)) {
    
    // Bon mot de passe
    $_SESSION['Mail_Uti'] = $email;
    $_SESSION['Id_Uti'] = $idUti;
    
    // Vérification du statut de producteur
    $isProd = $db->select("SELECT COUNT(*) FROM producteur WHERE producteur.Id_Uti = ?;", "i", [$idUti]);
    $_SESSION["isProd"] = $isProd[0]['COUNT(*)'] > 0;
    
    // Vérification du statut d'administrateur
    $isAdmin = $db->select("SELECT COUNT(*) as count FROM ADMINISTRATEUR WHERE Id_Uti = ?", "i", [$idUti]);
    $_SESSION["isAdmin"] = $isAdmin[0]['count'] > 0;
    $_SESSION['erreur'] = '';

    header('Location: ' . $_POST['redirect']);
    exit;

} else {

    // Mauvais mot de passe
    $_SESSION['test_pwd']++;
    $_SESSION['erreur'] = $htmlMauvaisMdp . $_SESSION['test_pwd'] . $htmlTentatives;
    header('Location: ../login.php');
    exit();

}
