<!DOCTYPE html>
<html lang="fr">
<?php
require "language.php";
?>

<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link rel="stylesheet" type="text/css" href="css/messagerie.css">
    <!-- name of the page -->
</head>

<body>
    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    ?>
    <div class="container">
        <div class="leftColumn" style="z-index: 6; overflow-y: auto;">
            
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <center>
                <p><strong><?php echo $htmlConversations; ?></strong></p>
            </center>
            
            <div style="margin-left:9%">
                <?php if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"] == true)) { ?>
                <p><?php echo $htmlBroadcast ?></p>
                <?php
                    echo '<form method="post" action="broadcastuser.php" style="display:block; margin-bottom: 10px;">
                            <input type="submit" value="' . $htmlbroadcastuser . '">
                        </form>';
                    echo '<form method="post" action="broadcastprod.php" style="display:block;">
                            <input type="submit" value="' . $htmlbroadcastprod . '">
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
                <?php if (isset($_GET['Id_Interlocuteur'])): ?>
                    <div class="interlocuteur">
                        <?php
                        require "traitements/afficherInterlocuteur.php";
                        ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div class="contenuMessagerie" style="max-height: 75vh; overflow-y: scroll; padding-bottom: 10px;">
                        <?php
                        require 'traitements/afficheMessages.php';
                        $formDisabled = !isset($_GET['Id_Interlocuteur']);
                        ?>
                    </div>
                    <?php if (isset($_GET['Id_Interlocuteur'])): ?>
                    <div style="position: fixed; bottom: 0; width: 83%; z-index: 5; right: 0; padding-bottom: 35px;">
                        <form method="post" id="zoneDEnvoi">
                            <input type="text" name="content" id="zoneDeTexte" <?php if ($formDisabled) {echo 'disabled';} ?>>
                            <input type="submit" value="" id="boutonEnvoyerMessage" <?php if ($formDisabled) {echo 'disabled';} ?>>
                        </form>
                    </div>
                    <?php endif;?>
                    <?php require 'traitements/envoyerMessage.php';?>
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
    <?php require "popups/gestion_popups.php" ?>


    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var messagesContainer = document.querySelector(".contenuMessagerie");
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    });
</script>
</body>
</html>