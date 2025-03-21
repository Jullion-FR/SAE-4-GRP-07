<?php

// Load environment
if (!isset($_SESSION)) {
    session_start();
}
include_once __DIR__ . "/../loadenv.php";

// Check if values set
if (
    !isset($_POST['currentPassword']) || 
    !isset($_POST['newPassword']) 
) {
    $_SESSION['erreur'] = "Veuillez remplir tous les champs";
    header('Location: ../account.php');
    exit();
}

// Call update pass procedure
$result = $db->callProcedure("changePassword(?, ?, ?)", "sss", [$_SESSION['Mail_Uti'], $_POST['currentPassword'], $_POST['newPassword']]);

// Set result
if ($result == "err") {
    $_SESSION['erreur'] = "Une erreur est survenu";
    header('Location: ../account.php');
    exit();
} else if ($result == "err:wrongpass") {
    $_SESSION['erreur'] = "Mot de passe actuel incorrect";
    header('Location: ../account.php');
    exit();
} else {
    $_SESSION['success'] = "Mot de passe modifié";
    header('Location: ../account.php');
    exit();
}