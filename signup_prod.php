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
                    <input type="hidden" name="type" value="prod">

                    <p>Adresse mail :</p>
                    <input type="email" name="email" placeholder="Adresse mail " required>

                    <p>Nom :</p>
                    <input type="text" name="nom" placeholder="Dupont" required>

                    <p>Prénom :</p>
                    <input type="text" name="prenom" placeholder="Jean" required>

                    <p><?= $htmlParProfession ?></p>
                    <select class="zoneDeTextePopup" name="profession" required>
                        <option value="Agriculteur" selected><?php echo $htmlAgriculteur; ?></option>
                        <option value="Vigneron"><?php echo $htmlVigneron; ?></option>
                        <option value="Maraîcher"><?php echo $htmlMaraîcher; ?></option>
                        <option value="Apiculteur"><?php echo $htmlApiculteur; ?></option>
                        <option value="Éleveur de volaille"><?php echo $htmlÉleveurdevolailles; ?></option>
                        <option value="Viticulteur"><?php echo $htmlViticulteur; ?></option>
                        <option value="Pépiniériste"><?php echo $htmlPépiniériste; ?></option>
                    </select>

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

            <?php include 'footer.php'; ?>

        </div>
    </div>

</body>