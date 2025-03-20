<?php
    require "language.php";
    include_once __DIR__ . "/loadenv.php";
    if (!isset($_SESSION)) {
        session_start();
    }
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

                <form action="traitements/login.php" class="info" method="post">

                    <p>Nom :</p>
                    <input type="text" name="nom" placeholder="Dupont">

                    <p>Prénom :</p>
                    <input type="text" name="prenom" placeholder="Jean">

                    <p>Rue :</p>
                    <input type="text" name="rue" placeholder="20 Rue du seum">

                    <p>Code postal :</p>
                    <input type="text" name="code_postal" placeholder="53000">

                    <p>Ville :</p>
                    <input type="text" name="ville" placeholder="Laval">

                    <br>
                    <input type="submit" value="<?= $htmlModifier ?>">

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