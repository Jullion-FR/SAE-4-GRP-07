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
    <style>

        .topBanner{
            width: 100%;
            position: absolute;
            top: 0;
            right: 0;
        }

        .content{
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .content p{
            font-size: 24px;
            font-weight: bold;
            margin-top: 40px;
            margin-bottom: 0;
        }

        .content input{
            margin-top: 20px;
            outline: none;
            border: 2px solid #CECECE;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            width: 30%;
            background-color: white;
            transition-duration: 0.2s;
        }
        .content input:hover{
            border-color: #669D31;
        }
        .content input:focus{
            box-shadow: 0 0 0 4px #669D3155;
        }

        .content input:last-child{
            margin-top: 40px;
        }

        .content .error{
            font-size: 18px;
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
        </div>
        <div class="rightColumn">
            <?php include 'topbanner.php'; ?>

            <form action="traitements/login.php" class="content" method="post">

                <p>Adresse mail</p>
                <input type="email" name="email">

                <p>Mot de passe</p>
                <input type="password" name="password">

                <input type="hidden" name="redirect" value="<?php echo $_GET['redirect'] ?? 'index.php'; ?>">

                <input type="submit" value="<?= $htmlSeConnecter ?>">

                <?php

                    if (isset($_SESSION['erreur'])) {
                        echo '<p class="error">' . $_SESSION['erreur'] . '</p>';
                        unset($_SESSION['erreur']);
                    }

                ?>
            
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