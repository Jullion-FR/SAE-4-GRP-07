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
           
                <p class="cattitle">S'inscrire en temps que</p>
                <div class="row">
                    <button onclick="window.location.href='signup_user.php'">Utilisateur</button>
                    <button onclick="window.location.href='signup_prod.php'">Producteur</button>
                </div>
                <a style="margin-top: 40px;" href="login.php">Se connecter</a>

            </div>            

            <?php include 'footer.php'; ?>

        </div>
    </div>

</body>