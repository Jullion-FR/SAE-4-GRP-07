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
           
                <p class="cattitle">S'inscire en temps que producteur</p>
                <form action="traitements/adduser.php" class="info" method="post">
                    <input type="hidden" name="type" value="user">

                    <p>Adresse mail :</p>
                    <input type="email" name="email" placeholder="Adresse mail " required>

                    <p>Nom :</p>
                    <input type="text" name="nom" placeholder="Dupont" required>

                    <p>Prénom :</p>
                    <input type="text" name="prenom" placeholder="Jean" required>

                    <p>Rue :</p>
                    <input type="text" name="rue" placeholder="20 Rue du seum" >

                    <p>Code postal :</p>
                    <input type="text" name="code_postal" placeholder="53000" pattern="[0-9]{5}">

                    <p>Ville :</p>
                    <input type="text" name="ville" placeholder="Laval">

                    <p>Mot de passe :</p>
                    <input type="password" name="password" placeholder="********" required>

                    <br>
                    <input type="submit" value="Créer le compte">

                    <?php
                    if (isset($_SESSION['erreur']) && $_SESSION['erreur'] != "") {
                        echo '<p class="error">' . $_SESSION['erreur'] . '</p>';
                        unset($_SESSION['erreur']);
                    }
                    ?>
                    
                </form>
                <a style="margin-top: 40px;" href="login.php">Se connecter</a>

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