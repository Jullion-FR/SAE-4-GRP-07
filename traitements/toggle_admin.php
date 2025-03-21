<?php
include_once __DIR__ . "/../loadenv.php";
if (!isset($_SESSION)) session_start();

if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true)) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_uti'])) {
    $idUti = intval($_POST['id_uti']);
    $db = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Vérifier si l'utilisateur est déjà admin
    $check = $db->prepare("SELECT * FROM ADMINISTRATEUR WHERE Id_Uti = ?");
    $check->execute([$idUti]);

    if ($check->rowCount() > 0) {
        // Supprimer les droits admin
        $remove = $db->prepare("DELETE FROM ADMINISTRATEUR WHERE Id_Uti = ?");
        $remove->execute([$idUti]);
    } else {
        // Récupérer l'ID max actuel
        $getMaxId = $db->query("SELECT MAX(Id_Admin) AS max_id FROM ADMINISTRATEUR");
        $maxIdRow = $getMaxId->fetch(PDO::FETCH_ASSOC);
        $nextId = ($maxIdRow['max_id'] ?? 0) + 1;

        // Ajouter les droits admin avec nouvel ID
        $insert = $db->prepare("INSERT INTO ADMINISTRATEUR (Id_Admin, Id_Uti) VALUES (?, ?)");
        $insert->execute([$nextId, $idUti]);
    }

    header("Location: ../panel_admin.php");
    exit();
}
?>
