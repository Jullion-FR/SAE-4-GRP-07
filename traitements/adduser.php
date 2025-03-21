<?php

// Exit if no data
if (!($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'], $_POST['nom'], $_POST['prenom'], $_POST['type']))) {
    header('Location: ../index.php');
    exit;
}

// Require
session_start();
require_once __DIR__ . "/../language.php";
require_once __DIR__ . "/../loadenv.php";

// Get info
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['rue'] .", ". $_POST['code_postal']. " ".mb_strtoupper($_POST['ville']);
$password = $_POST['password'];
$email = $_POST['email'];

// Réucpère le nouvelle identifiant (parce que c'est pas fait automatiquement lol)
$id = $db->select("SELECT MAX(Id_Uti) AS id_max FROM UTILISATEUR")[0]['id_max'];
$id++;

// Vérification de l'existence de l'adresse mail
$nb = $db->select("SELECT COUNT(*) AS nb FROM UTILISATEUR WHERE Mail_Uti = ?", "s", [$email])[0]['nb'];
if ($nb != 0) {
    $_SESSION['erreur'] = $htmlAdrMailDejaUtilisee;
    header('Location: ../signup_user.php');
    exit;
}

// Ajoute l'utilisateur
$db->query("INSERT INTO UTILISATEUR (Id_Uti, Prenom_Uti, Nom_Uti, Adr_Uti, Pwd_Uti, Mail_Uti) VALUES (?, ?, ?, ?, ?, ?)", "isssss", [$id, $prenom, $nom, $adresse, $password, $email]);

// S'il s'agit d'un producteur
if ($_POST['type'] == 'prod'){

    // Get info
    $profession = $_POST['profession'];

    // Get new id for producteur
    $id_prod = $db->select("SELECT MAX(Id_Prod) AS id_max1 FROM PRODUCTEUR")[0]['id_max1'];
    $id_prod++;

    // Ajoute le producteur
    $db->query("INSERT INTO PRODUCTEUR (Id_Uti, Id_Prod, Prof_Prod) VALUES (?, ?, ?)", "iis", [$id, $id_prod, $profession]);

    // Copy default image
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    $default = "default_producteur.png";
    $new_img = $id_prod.".png";
    $source = $documentRoot."/img/".$default;
    $destination = $documentRoot."/img_producteur/".$new_img;
    copy($source, $destination);

}

// Login
require_once __DIR__ . "/sublogin.php";
tryLogin($email, $password, "../index.php");