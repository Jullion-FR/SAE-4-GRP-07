<?php
include_once __DIR__ . "/../loadenv.php";
include_once __DIR__ . "/../language.php";

// Check if is post && contains message
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['message'])) {
    header('Location: ../index.php');
    exit();
}

// Check if connected
if (isset($_SESSION['Id_Uti'])) {
    $db->query('CALL broadcast_admin(?, ?);', 'is', [$_SESSION['Id_Uti'], $_POST['message']]);
} else {

    // Check if email is set
    if (!isset($_POST['email'])) {
        header('Location: ../index.php');
        exit();
    }

    $db->query('CALL broadcast_admin(0, ?);', 's', [$_POST['email'] . ': ' . $_POST['message']]);
}

// Clean end
$_SESSION['success'] = 'Le message a bien été envoyé.';
header('Location: ../sigdys.php');