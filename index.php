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


    /*---------------------------------------------------------------*/
    /*
            Titre : Calcul la distance entre 2 points en km                                                                       
                                                                                                                                
            URL   : https://phpsources.net/code_s.php?id=1091
            Auteur           : sheppy1                                                                                            
            Website auteur   : https://lejournalabrasif.fr/qwanturank-concours-seo-qwant/                                         
            Date édition     : 05 Aout 2019                                                                                       
            Date mise à jour : 16 Aout 2019                                                                                      
            Rapport de la maj:                                                                                                    
            - fonctionnement du code vérifié                                                                                    
        */
    /*---------------------------------------------------------------*/

    function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797; // rayon moyen de la Terre en km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin(
            $dlng / 2
        ) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return ($miles ? ($km * 0.621371192) : $km);
    }
    ?>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <div class="contenuBarre">

                <center><strong>
                        <p><?php echo $htmlRechercherPar; ?></p>
                    </strong></center>
                <form method="get" action="index.php">
                    <label><?php echo $htmlParProfession ?></label>
                    <br>
                    <select name="categorie" id="categories">
                        <option value="Tout" <?php if ($_GET["categorie"] == "Tout") echo 'selected="selected"'; ?>><?php echo $htmlTout ?></option>
                        <option value="Agriculteur" <?php if ($_GET["categorie"] == "Agriculteur") echo 'selected="selected"'; ?>><?php echo $htmlAgriculteur ?></option>
                        <option value="Vigneron" <?php if ($_GET["categorie"] == "Vigneron") echo 'selected="selected"'; ?>><?php echo $htmlVigneron ?></option>
                        <option value="Maraîcher" <?php if ($_GET["categorie"] == "Maraîcher") echo 'selected="selected"'; ?>><?php echo $htmlMaraîcher ?></option>
                        <option value="Apiculteur" <?php if ($_GET["categorie"] == "Apiculteur") echo 'selected="selected"'; ?>><?php echo $htmlApiculteur ?></option>
                        <option value="Éleveur de volaille" <?php if ($_GET["categorie"] == "Éleveur de volaille") echo 'selected="selected"'; ?>><?php echo $htmlÉleveurdevolailles ?></option>
                        <option value="Viticulteur" <?php if ($_GET["categorie"] == "Viticulteur") echo 'selected="selected"'; ?>><?php echo $htmlViticulteur ?></option>
                        <option value="Pépiniériste" <?php if ($_GET["categorie"] == "Pépiniériste") echo 'selected="selected"'; ?>><?php echo $htmlPépiniériste ?></option>
                    </select>
                    <br>
                    <br><?php echo $htmlParVille ?>
                    <br>
                    <input type="text" name="rechercheVille" pattern="[A-Za-z0-9 ]{0,100}" value="<?php echo $rechercheVille ?>" placeholder="<?php echo $htmlVille; ?>">
                    <br>
                    <br><?php echo 'Par producteur :' ?>
                    <br>
                    <input type="text" name="rechercheProdNom" pattern="[A-Za-z]{0,100}" value="" placeholder="Nom">
                    <input type="text" name="rechercheProdPrenom" pattern="[A-Za-z]{0,100}" value="" placeholder="Prénom">
                    <br>
                    <?php
                   
                    $mabdd = dbConnect();
                    $queryAdrUti = $mabdd->prepare(('SELECT Adr_Uti FROM UTILISATEUR WHERE Id_Uti= :utilisateur;'));
                    $queryAdrUti->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
                    $queryAdrUti->execute();
                    $returnQueryAdrUti = $queryAdrUti->fetchAll(PDO::FETCH_ASSOC);
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
                    if (count($returnQueryAdrUti) > 0) {
                        $Adr_Uti_En_Cours = $returnQueryAdrUti[0]["Adr_Uti"];
                    ?>
                        <br>
                        <br><?php echo $htmlAutourDeChezMoi . ' (' . $Adr_Uti_En_Cours . ')'; ?>
                        <br>
                        <br>
                        <input name="rayon" type="range" value="<?php echo $rayon; ?>" min="1" max="100" step="1" onchange="AfficheRange2(this.value)" onkeyup="AfficheRange2(this.value)">
                        <span id="monCurseurKm"><?php echo $htmlRayonDe ?> <?php echo $rayon;
                                                                            if ($rayon >= 100) echo '+'; ?></span>
                        <script>
                            function AfficheRange2(newVal) {
                                var monCurseurKm = document.getElementById("monCurseurKm");
                                if ((newVal >= 100)) {
                                    monCurseurKm.innerHTML = "Rayon de " + newVal + "+ ";
                                } else {
                                    monCurseurKm.innerHTML = "Rayon de " + newVal + " ";
                                }
                            }
                        </script>
                        <?php echo $htmlKm ?>
                        <br>
                        <br>
                    <?php

                    } else {
                        $Adr_Uti_En_Cours = 'France';
                    }
                    ?>
                    <br>


                    <label><?php echo $htmlTri ?></label>
                    <br>
                    <select name="tri" required>
                        <option value="nombreDeProduits" <?php if ($tri == "nombreDeProduits") echo 'selected="selected"'; ?>><?php echo $htmlNombreDeProduits ?></option>
                        <option value="ordreNomAlphabétique" <?php if ($tri == "ordreNomAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParNomAl ?></option>
                        <option value="ordreNomAntiAlphabétique" <?php if ($tri == "ordreNomAntiAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParNomAntiAl ?></option>
                        <option value="ordrePrenomAlphabétique" <?php if ($tri == "ordrePrenomAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParPrenomAl ?></option>
                        <option value="ordrePrenomAntiAlphabétique" <?php if ($tri == "ordrePrenomAntiAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParPrenomAntiAl ?></option>
                    </select>
                    <br>
                    <br>
                    <br>


                    <center><input type="submit" value="<?php echo $htmlRechercher ?>"></center>
                </form>


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

            <h1> <?php echo $htmlProducteursEnMaj ?> </h1>

            <div class="gallery-container">
                <?php


                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    // Définition des variables
                    $categorie = isset($_GET["categorie"]) ? htmlspecialchars($_GET["categorie"]) : "Tout";
                    $rechercheVille = isset($_GET["rechercheVille"]) ? htmlspecialchars($_GET["rechercheVille"]) : "";
                    $rechercheProdNom = isset($_GET["rechercheProdNom"]) ? htmlspecialchars($_GET["rechercheProdNom"]) : "";
                    $rechercheProdPrenom = isset($_GET["rechercheProdPrenom"]) ? htmlspecialchars($_GET["rechercheProdPrenom"]) : "";
                    $rayon = isset($_GET["rayon"]) ? htmlspecialchars($_GET["rayon"]) : 100;
                    $tri = isset($_GET["tri"]) ? htmlspecialchars($_GET["tri"]) : "nombreDeProduits";

                    // Construction de la requête SQL
                    $query = "
                        SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, 
                            UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti, 
                            COUNT(PRODUIT.Id_Produit) AS nombre_produits
                        FROM PRODUCTEUR 
                        JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti
                        LEFT JOIN PRODUIT ON PRODUCTEUR.Id_Prod = PRODUIT.Id_Prod
                        WHERE 1=1 ";

                    $params = [];
                    $types = "";

                    // Filtre par catégorie
                    if ($categorie !== "Tout") {
                        $query .= " AND PRODUCTEUR.Prof_Prod = ? ";
                        $params[] = $categorie;
                        $types .= "s";
                    }

                    // Filtre par ville
                    if (!empty($rechercheVille)) {
                        $query .= " AND UTILISATEUR.Adr_Uti LIKE ? ";
                        $params[] = "%" . $rechercheVille . "%";
                        $types .= "s";
                    }

                    // Filtre par nom
                    if (!empty($rechercheProdNom)) {
                        $query .= " AND UTILISATEUR.Nom_Uti LIKE ? ";
                        $params[] = "%" . $rechercheProdNom . "%";
                        $types .= "s";
                    }

                    // Filtre par prénom
                    if (!empty($rechercheProdPrenom)) {
                        $query .= " AND UTILISATEUR.Prenom_Uti LIKE ? ";
                        $params[] = "%" . $rechercheProdPrenom . "%";
                        $types .= "s";
                    }

                    // Groupement des résultats
                    $query .= " GROUP BY PRODUCTEUR.Id_Prod ";

                    // Tri des résultats
                    $triOptions = [
                        "nombreDeProduits" => "COUNT(PRODUIT.Id_Produit) DESC",
                        "ordreNomAlphabétique" => "UTILISATEUR.Nom_Uti ASC",
                        "ordreNomAntiAlphabétique" => "UTILISATEUR.Nom_Uti DESC",
                        "ordrePrenomAlphabétique" => "UTILISATEUR.Prenom_Uti ASC",
                        "ordrePrenomAntiAlphabétique" => "UTILISATEUR.Prenom_Uti DESC"
                    ];

                    // Assurer un tri valide
                    $query .= " ORDER BY " . ($triOptions[$tri] ?? "COUNT(PRODUIT.Id_Produit) ASC");

                    // Exécution de la requête avec l'objet `$db`
                    $results = $db->select($query, $types, $params);

                    // Récupération des coordonnées utilisateur pour le filtrage par rayon
                    $Adr_Uti_En_Cours = $rechercheVille ?: 'France'; // Valeur par défaut si ville non renseignée
                    $urlUti = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($Adr_Uti_En_Cours);
                    $coordonneesUti = latLongGps($urlUti);
                    $latitudeUti = $coordonneesUti[0];
                    $longitudeUti = $coordonneesUti[1];

                    // Affichage des résultats
                    if (!empty($results)) {
                        foreach ($results as $row) {
                            $adresseProd = $row["Adr_Uti"];
                            $urlProd = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($adresseProd);
                            $coordonneesProd = latLongGps($urlProd);
                            $latitudeProd = $coordonneesProd[0];
                            $longitudeProd = $coordonneesProd[1];
                            $distance = distance($latitudeUti, $longitudeUti, $latitudeProd, $longitudeProd);

                            // Affichage des producteurs en fonction du rayon
                            if ($rayon >= 100 || $distance < $rayon) {
                                echo '<a href="producteur.php?Id_Prod=' . $row["Id_Prod"] . '" class="square1">';
                                echo '<img src="img_producteur/' . $row["Id_Prod"] . '.png" alt="Image producteur" style="width: 100%; height: 85%;" ><br>';
                                echo "<strong>" . $row["Prof_Prod"] . "</strong><br>";
                                echo "Nom : " . strtoupper($row["Nom_Uti"]) . "<br>";
                                echo "Prénom : " . $row["Prenom_Uti"] . "<br>";
                                echo "Adresse : " . $row["Adr_Uti"] . "<br>";
                                echo '</a>';
                            }
                        }
                    } else {
                        echo "<p>Aucun producteur correspondant à vos critères.</p>";
                    }

                }

                ?>
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
    <?php require "popups/gestion_popups.php"; ?>
</body>