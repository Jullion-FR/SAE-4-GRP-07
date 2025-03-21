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

                <input type="hidden" name="redirect" value="<?php echo $_GET['redirect'] ?? '' ?>">

                <input  style="margin-top: 30px; width: 30vw;" type="submit" value="<?= $htmlSeConnecter ?>">

                <?php
                    if (isset($_SESSION['erreur']) && $_SESSION['erreur'] != "") {
                        echo '<p class="error">' . $_SESSION['erreur'] . '</p>';
                        unset($_SESSION['erreur']);
                    }
                ?>

                <a style="margin-top: 40px;" href="signup.php">Créer un compte</a>
            
            </form>

            <?php include 'footer.php'; ?>

        </div>
    </div>

</body>