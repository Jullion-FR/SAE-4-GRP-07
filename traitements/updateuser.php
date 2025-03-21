<?php
// Load environment
if (!isset($_SESSION)) {
    session_start();
}
include_once __DIR__ . "/../loadenv.php";

// Check if values set
if (
    !isset($_POST['nom']) || 
    !isset($_POST['prenom']) || 
    !isset($_POST['rue']) || 
    !isset($_POST['code_postal']) || 
    !isset($_POST['ville'])
) {
    $_SESSION['error'] = "Veuillez remplir tous les champs";
    header('Location: ../account.php');
    exit();
}

// Create adresse
$adresse = $_POST['rue'] . ', ' . $_POST['code_postal'] . ' ' . $_POST['ville'];

// Update db
$db->query("UPDATE UTILISATEUR SET Nom_Uti = ?, Prenom_Uti = ?, Adr_Uti = ? WHERE Mail_Uti = ?;", 'ssss', [$_POST['nom'], $_POST['prenom'], $adresse, $_SESSION['Mail_Uti']]);
$_SESSION['success'] = "Informations mises à jour";
header('Location: ../account.php');
exit();