<?php
include_once __DIR__ . "/../loadenv.php";
if (!isset($_SESSION)) session_start();

if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true)) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_uti'])) {
    $idUti = intval($_POST['id_uti']);

    // Vérifier si l'utilisateur est déjà admin
    $check = $db->select("SELECT * FROM ADMINISTRATEUR WHERE Id_Uti = ?", 'i', [$idUti]);

    if (count($check) > 0) {
        // Supprimer les droits admin
        $db->query("DELETE FROM ADMINISTRATEUR WHERE Id_Uti = ?", 'i', [$idUti]);
    } else {
        // Récupérer l'ID max actuel
        $getMaxId = $db->select("SELECT MAX(Id_Admin) AS max_id FROM ADMINISTRATEUR")[0];
        $nextId = (intval($getMaxId['max_id']) ?? 0) + 1;

        // Ajouter les droits admin avec nouvel ID
        $insert = $db->query("INSERT INTO ADMINISTRATEUR (Id_Admin, Id_Uti) VALUES (?, ?)", 'ii', [$nextId, $idUti]);
    }

    header("Location: ../panel_admin.php");
    exit();
}
?>
