<?php
// Détruisez toutes les variables de session
if (!isset($_SESSION)) {
    session_start();
}

$_SESSION = array();
// Effacez le cookie de session
$_SESSION['erreur'] = $htmlDeconnectionReussie;
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    @setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Détruisez la session
session_destroy();
header('Location: ../index.php');
die()
?>