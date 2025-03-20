<div class="topBanner">

    <!-- LES BOUTONS -->
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
        <form action="language.php" method="post" id="languageForm">
            <select name="language" id="languagePicker" onchange="submitForm()">
                <option value="fr" <?php if ($_SESSION["language"] == "fr") echo 'selected'; ?>>Français</option>
                <option value="en" <?php if ($_SESSION["language"] == "en") echo 'selected'; ?>>English</option>
                <option value="es" <?php if ($_SESSION["language"] == "es") echo 'selected'; ?>>Español</option>
                <option value="al" <?php if ($_SESSION["language"] == "al") echo 'selected'; ?>>Deutsch</option>
                <option value="ru" <?php if ($_SESSION["language"] == "ru") echo 'selected'; ?>>русский</option>
                <option value="ch" <?php if ($_SESSION["language"] == "ch") echo 'selected'; ?>>中國人</option>
            </select>
        </form>
    </div>

    
    <!-- CONNEXION -->
    <?php 
        if (isset($_SESSION['Mail_Uti'])) {
            $redirect = 'account.php';
            $text = $_SESSION['Mail_Uti'];
        } else {
            $currentUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $redirect = 'login.php?redirect=' . urlencode($currentUrl);
            $text = $htmlSeConnecter;
        }
    ?>
    <a class="connexion" href="<?= $redirect; ?>">
        <?= $text; ?>
        <img src="img/connexion.svg" alt="Profil utilisateur">
    </a>

</div>