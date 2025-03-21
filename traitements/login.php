<?php

// Exit if no data
if (!($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'], $_POST['redirect']))) {
    header('Location: ../index.php');
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
if ($_SESSION['test_pwd'] <= 0) {
    $_SESSION['erreur'] = $htmlErreurMaxReponsesAtteintes;
    header('Location: ../login.php');
    exit();
}

$redirect = $_POST['redirect'];
if (empty($redirect)) {
    $redirect = "../index.php";
}

include_once __DIR__ . "/sublogin.php";

tryLogin($email, $password, $redirect);