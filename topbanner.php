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