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

            <form action="traitements/sigdys.php" class="content wrapped" method="post">

                <p style="text-align: center; font-size: 24px; transform: translateY(30px);">Signaler un dysfonctionnement</p>

                <?php
                if (!isset($_SESSION['Id_Uti'])) {
                    echo '<p class="cattitle">Adresse mail</p>';
                    echo '<input style="margin-top: 10px; width: 30vw;" type="email" name="email" placeholder="test@hello.com">';
                }
                ?>
                <p class="cattitle">Message</p>
                <textarea style="margin-top: 10px; width: 30vw;" rows="10" name="message" placeholder="Je suis un message"></textarea>

                <input type="hidden" name="redirect" value="<?php echo $_GET['redirect'] ?? '' ?>">
                
                <?php
                    if (isset($_SESSION['success']) && $_SESSION['success'] != "") {
                        echo '<p class="success">' . $_SESSION['success'] . '</p>';
                        unset($_SESSION['success']);
                    }
                ?>

                <input  style="margin-top: 30px; width: 30vw;" type="submit" value="<?= $htmlEnvoyer ?>">
            
            </form>

            <?php include 'footer.php'; ?>

        </div>
    </div>

</body>