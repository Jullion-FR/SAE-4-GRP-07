<?php
include_once __DIR__ . "/loadenv.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    require "language.php";
    $htmlFrançais = "Français";
    $htmlAnglais = "English";
    $htmlEspagnol = "Español";
    $htmlAllemand = "Deutch";
    $htmlRusse = "русский";
    $htmlChinois = "中國人";
    ?>
    <title> <?php echo $htmlMarque; ?> </title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>

<body>
    <?php
    //var_dump($_SESSION);
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_GET["rechercheVille"]) == true) {
        $rechercheVille = htmlspecialchars($_GET["rechercheVille"]);
    } else {
        $rechercheVille = "";
    }
    if (isset($_GET["categorie"]) == false) {
        $_GET["categorie"] = "Tout";
    }
    if (isset($_SESSION["Id_Uti"]) == false) {
        $utilisateur = -1;
    } else {
        $utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);
    }
    if (isset($_GET["rayon"]) == false) {
        $rayon = 100;
    } else {
        $rayon = htmlspecialchars($_GET["rayon"]);
    }
    if (isset($_GET["tri"]) == true) {
        $tri = htmlspecialchars($_GET["tri"]);
    } else {
        $tri = "nombreDeProduits";
    }
    if (isset($_SESSION["language"]) == false) {
        $_SESSION["language"] = "fr";
    }

    // récupération adresse du client
    function dbConnect()
    {
        $utilisateur = $_ENV['DB_USER'];
        $serveur = $_ENV['DB_HOST'];
        $motdepasse = $_ENV['DB_PASS'];
        $basededonnees = $_ENV['DB_NAME'];
        // Connect to database
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    }

    function latLongGps($url)
    {
        // Configuration de la requête cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Permet de suivre les redirections
        // Ajout du User Agent
        $customUserAgent = "LEtalEnLigne/1.0"; // Remplacez par le nom et la version de votre application
        curl_setopt($ch, CURLOPT_USERAGENT, $customUserAgent);
        // Ajout du Referrer
        $customReferrer = "https://proxy.univ-lemans.fr:3128"; // Remplacez par l'URL de votre application
        curl_setopt($ch, CURLOPT_REFERER, $customReferrer);
        // Exécution de la requête
        $response = curl_exec($ch);
        // Vérifier s'il y a eu une erreur cURL
        if (curl_errno($ch)) {
            echo 'Erreur cURL : ' . curl_error($ch);
        } else {
            // Analyser la réponse JSON
            $data = json_decode($response);

            // Vérifier si la réponse a été correctement analysée
            if (!empty($data) && is_array($data) && isset($data[0])) {
                // Récupérer la latitude et la longitude
                $latitude = $data[0]->lat;
                $longitude = $data[0]->lon;
                return [$latitude, $longitude];
            }
            return [0, 0];
        }
        // Fermeture de la session cURL
        curl_close($ch);
    }

    ?>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <div class="contenuBarre">

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
                <form method="post">

                    <script>
                        function submitForm() {
                            document.getElementById("languageForm").submit();
                        }
                    </script>
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

            <div class="profile-picture-container">
                <h1><?php echo $htmlChangementPPMaj ?></h1>
                <img id="image-preview" class="image-preview" src="#" alt="Aperçu de l'image" style="display: none;"/>

                <form action="upload.php" method="post" enctype="multipart/form-data" class="profile-form">
                    <label for="profile-image-upload" class="custom-file-upload">
                        Choisir une image
                    </label>
                    <input type="file" id="profile-image-upload" name="image" accept=".png" required onchange="previewImage(event)">
                    
                    <button type="submit" class="upload-btn">Mettre à jour</button>
                </form>
            </div>

            <br>
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
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var imagePreview = document.getElementById('image-preview');
        imagePreview.src = reader.result;
        imagePreview.style.display = "block"; 
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
</body>
</html>
