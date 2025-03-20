<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>

<body>
    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    ?>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <div class="contenuBarre">
                <!-- some code -->
            </div>
        </div>
        <div class="rightColumn">
            
            <?php include "topbanner.php"; ?>

                <!-- some code -->

            <?php include "footer.php"; ?>

        </div>
    </div>
</body>