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

            <form action="traitements/login.php" class="content wrapped" method="post">

                <p style="font-size: 24px; font-weight: bold;">Adresse mail</p>
                <input style="margin-top: 10px; width: 30vw;" type="email" name="email" placeholder="un20svp@gmail.com">

                <p style="font-size: 24px; margin-top: 30px; font-weight: bold;">Mot de passe</p>
                <input  style="margin-top: 10px; width: 30vw;" type="password" name="password" placeholder="azerty123">

                <input type="hidden" name="redirect" value="<?php echo $_GET['redirect'] ?? ('http://' . $_SERVER['HTTP_HOST'] . '/index.php'); ?>">

                <input  style="margin-top: 30px; width: 30vw;" type="submit" value="<?= $htmlSeConnecter ?>">

                <?php
                    if (isset($_SESSION['erreur'])) {
                        echo '<p class="error">' . $_SESSION['erreur'] . '</p>';
                        unset($_SESSION['erreur']);
                    }
                ?>
            
            </form>

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