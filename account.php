<?php
require "language.php";
include_once __DIR__ . "/loadenv.php";
if (!isset($_SESSION)) {
    session_start();
}

// Load user infos
$result = $db->select("SELECT * FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=?", 's' ,[$_SESSION['Mail_Uti']]);
if (count($result) == 0) {
    echo "Erreur : utilisateur introuvable";
    exit();
}
$result = $result[0];

// Parse adressse
$adresse = $result['Adr_Uti']; // rue, 53000 ville
$adresse = explode(", ", $adresse);
$rue = $adresse[0];
$code_postal = explode(" ", $adresse[1])[0];
$ville = explode(" ", $adresse[1])[1];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" href="css/staticpopup.css">
</head>

<body>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
        </div>
        <div class="rightColumn">

            <?php include 'topbanner.php'; ?>

            <div class="content">

                <a href="traitements/logout.php"><?= $htmlSeDeconnecter ?></a>

                <form action="traitements/updateuser.php" class="info" method="post">

                    <p>Nom :</p>
                    <input type="text" name="nom" placeholder="Dupont" required value="<?= $result['Prenom_Uti'] ?>">

                    <p>Prénom :</p>
                    <input type="text" name="prenom" placeholder="Jean" required value="<?= $result['Nom_Uti'] ?>">

                    <p>Rue :</p>
                    <input type="text" name="rue" placeholder="20 Rue du seum" required value="<?= $rue ?>">

                    <p>Code postal :</p>
                    <input type="text" name="code_postal" placeholder="53000" required value="<?= $code_postal ?>">

                    <p>Ville :</p>
                    <input type="text" name="ville" placeholder="Laval" required value="<?= $ville ?>">

                    <br>
                    <input type="submit" value="<?= $htmlModifier ?>">

                    <?php
                    if (isset($_SESSION['erreur'])) {
                        echo '<p class="error">' . $_SESSION['erreur'] . '</p>';
                        unset($_SESSION['erreur']);
                    }
                    if (isset($_SESSION['success'])) {
                        echo '<p class="success">' . $_SESSION['success'] . '</p>';
                        unset($_SESSION['success']);
                    }
                    ?>

                </form>

                <div class="bottomAction">
                    <?php if((isset($_SESSION['isProd']) && $_SESSION['isProd'])){?> 
                        <form action="addProfilPicture.php">
                            <input type="submit" value="Modifier la photo de profil">
                        </form>
                    <?php } ?>
                    <form action="">
                        <input type="submit" value="Supprimer le compte">
                    </form>
                </div>

            </div>

            <div class="basDePage">
                <form method="post">
                    <input type="submit" value="<?php echo $htmlSignalerDys ?>" class="lienPopup">
                    <input type="hidden" name="popup" value="contact_admin">
                </form>
                <form method="post">
                    <input type="submit" value="<?php echo $htmlCGU ?>" class="lienPopup">
                    <input type="hidden" name="popup" value="cgu">
                </form>
            </div>

        </div>
    </div>

</body>