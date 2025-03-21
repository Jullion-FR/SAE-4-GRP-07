<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    require "language.php" ; 
?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link rel="stylesheet" type="text/css" href="css/messagerie.css">
</head>
<body>
    <?php
        if(!isset($_SESSION)){
            session_start();
        }
        if (!isset($_SESSION['Id_Uti']) || !isset($_SESSION['isAdmin'])){
            header("Location: index.php");
            exit();
        }
    ?>
    <div class="container">
        <div class="leftColumn">
        <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <center>
                <p><strong><?php echo $htmlConversations; ?></strong></p>
            </center>
            
            <div style="margin-left:9%">
                <?php if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"] == true)) { ?>
                <p><?php echo $htmlBroadcast ?></p>
                <?php
                    echo '<form method="post" action="broadcastuser.php" style="display:block; margin-bottom: 10px;width:90%;">
                            <input class="filtre-container-producteur" type="submit" value="' . $htmlbroadcastuser . '">
                        </form>';
                    echo '<form method="post" action="broadcastprod.php" style="display:block;width:90%;">
                            <input class="filtre-container-producteur" type="submit" value="' . $htmlbroadcastprod . '">
                        </form>';
                }?>
                <p><?php echo $htmlContactsRecentsDeuxPoints ?></p>
                <?php
                require 'traitements/afficheContacts.php';
                ?>
            </div>
        </div>
        <div class="rightColumn">
        <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil ?></a>
                    <?php
                    if (isset($_SESSION["Id_Uti"])) {
                        echo '<a class="bontonDeNavigation" href="messagerie.php">' . $htmlMessagerie . '</a>';
                        echo '<a class="bontonDeNavigation" href="achats.php">' . $htmlAchats . '</a>';
                    }
                    if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"] == true)) {
                        echo '<a class="bontonDeNavigation" href="produits.php">' . $htmlProduits . '</a>';
                        echo '<a class="bontonDeNavigation" href="delivery.php">' . $htmlCommandes . '</a>';
                    }
                    if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"] == true)) {
                        echo '<a class="bontonDeNavigation" href="panel_admin.php">' . $htmlPanelAdmin . '</a>';
                    }
                    ?>
                </div>
                <form method="post">
                    <?php
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if (isset($_SESSION, $_SESSION['tempPopup'])) {
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>
                    <input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])) {/*$_SESSION = array()*/;
                                                    echo ($htmlSeConnecter);
                                                } else {
                                                    echo '' . $_SESSION['Mail_Uti'] . '';
                                                } ?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if (isset($_SESSION['Mail_Uti'])) {
                                                                echo '"info_perso"';
                                                            } else {
                                                                echo '"sign_in"';
                                                            } ?>>
                </form>
            </div>
            <div class="contenuPage">
                <form action="traitements/traitement_broadcast_user.php" method="post" style="margin: 20px;">
                    <label for="message" style="text-decoration: underline;"><?php echo $htmlVotreMessage; ?></label>
                    <div style="text-align: center;">
                        <textarea id="message" name="message" rows="10" cols="80" maxlength="5000" required></textarea>
                    </div>
                    <div style="text-align: center;">
                        <input class="send-message-br" type="submit" value="<?php echo $htmlEnvoyerMessageATousUti; ?>" style="margin-top: 20px;">
                    </div>
                </form>
            </div>
            <div class="basDePage">
                <form method="post">
                    <input type="submit" value="<?php echo $htmlSignalerDys?>" class="lienPopup">
                    <input type="hidden" name="popup" value="contact_admin">
                </form>
                <form method="post">
                    <input type="submit" value="<?php echo $htmlCGU?>" class="lienPopup">
                    <input type="hidden" name="popup" value="cgu">
                </form>
            </div>
        </div>
    </div>
</body>
</html>