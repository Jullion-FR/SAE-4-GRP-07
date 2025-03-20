<?php
require "language.php";
include_once __DIR__ . "/loadenv.php";
require "./popups/delete_account_warning.php";

if (!isset($_SESSION)) {
    session_start();
}

// If user isn't admin, redirect to index
if (!(isset($_SESSION['isAdmin']) && $_SESSION["isAdmin"] == true)){
    header("Location: index.php");
    exit();
}

$utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>

<body>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <div class="contenuBarre">

            </div>
        </div>
        <div class="rightColumn">
            <?php include 'topbanner.php'; ?>

            <!-- PRODUCTEURS -->
            <p class="admin-title">Producteurs</p>
            <div class="gallery-container">
            <?php

                $rows = $db->select('SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti', '', []);

                foreach ($rows as $row) {
                    $targetID = $row["Id_Uti"];
                    $prenom = addslashes($row["Prenom_Uti"]);
                    $nom = addslashes($row["Nom_Uti"]);
                    $adresse = addslashes($row["Adr_Uti"]);
                    $profession = isset($row["Prof_Prod"]) ? addslashes($row["Prof_Prod"]) : "";
                    $imageSrc = file_exists("img_producteur/".$row["Id_Prod"].".png") ? "/img_producteur/".$row["Id_Prod"].".png" : "/img/default_producteur.png";
                    ?>
                        <div class="squarePanelAdmin">
                            <button onclick="openPopup('<?= $targetID ?>', '<?= $prenom ?>', '<?= $nom ?>', '<?= $adresse ?>', '<?= $profession ?>', '<?= $imageSrc ?>')"><img src="./img/trash.svg" alt="Supprimer le compte"></button>
                            
                            <img src="<?= $imageSrc ?>" alt="Photo de profil" onerror="this.onerror=null; this.src='./img/default_producteur.png';">
                            <p><span><?= $htmlNomDeuxPoints ?></span> <?= $nom ?></p>
                            <p><span><?= $htmlPrénomDeuxPoints ?></span> <?= $prenom ?></p>
                            <p><span><?= $htmlMailDeuxPoints ?></span> <?= $row["Mail_Uti"] ?></p>
                            <p><span><?= $htmlAdresseDeuxPoints ?></span> <?= $adresse ?></p>
                            <p><span><?= $htmlProfessionDeuxPoints ?></span> <?= $profession ?></p>
                        </div>
                    <?php
                }
            
            ?>
            </div>

            <!-- UTILISATEURS -->
            <p class="admin-title">Utilisateurs</p>
            <div class="gallery-container">

                <?php
                $rows = $db->select('SELECT UTILISATEUR.Id_Uti, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti FROM UTILISATEUR WHERE UTILISATEUR.Id_Uti NOT IN (SELECT PRODUCTEUR.Id_Uti FROM PRODUCTEUR) AND UTILISATEUR.Id_Uti NOT IN (SELECT ADMINISTRATEUR.Id_Uti FROM ADMINISTRATEUR) AND UTILISATEUR.Id_Uti<>0;', '', []);
                
                foreach($rows as $row){

                    $targetID = $row["Id_Uti"];
                    $prenom = addslashes($row["Prenom_Uti"]);
                    $nom = addslashes($row["Nom_Uti"]);
                    $mail = $row["Mail_Uti"];
                    $adresse = addslashes($row["Adr_Uti"]);

                    ?>
                        <div class="squarePanelAdmin">
                            <button onclick="openPopup('<?= $targetID ?>', '<?= $prenom ?>', '<?= $nom ?>', '<?= $adresse ?>', '', './img/connexion.svg')"><img src="./img/trash.svg" alt="Supprimer le compte"></button>

                            <p><span><?= $htmlNomDeuxPoints ?></span> <?= $nom ?></p>
                            <p><span><?= $htmlPrénomDeuxPoints ?></span> <?= $prenom ?></p>
                            <p><span><?= $htmlMailDeuxPoints ?></span> <?= $mail ?></p>
                            <p><span><?= $htmlAdresseDeuxPoints ?></span> <?= $adresse ?></p>
                        </div>
                    <?php

                }
                
                ?>

            </div>

            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>