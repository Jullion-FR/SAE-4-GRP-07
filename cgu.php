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
           
                <p class="cattitle"><?= $htmlCGU ?></p>
                <p style="padding: 0 10vw; margin-top: 40px; text-align: justify;"><?php echo $htmlTxtCGU; ?></p>   

            </div>

            <?php include 'footer.php'; ?>

        </div>
    </div>

</body>