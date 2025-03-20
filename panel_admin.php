<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    require "language.php";
    include_once __DIR__ . "/loadenv.php";
    ?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>

<body>

    <?php
    require "./popups/delete_account_warning.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    function dbConnect()
    {
        $utilisateur = $_ENV['DB_USER'];
        $serveur = $_ENV['DB_HOST'];
        $motdepasse = $_ENV['DB_PASS'];
        $basededonnees = $_ENV['DB_NAME'];
        // Connect to database
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    }

    $bdd = dbConnect();
    $utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);

    $filtreCategorie = 0;
    if (isset($_POST["typeCategorie"]) == true) {
        $filtreCategorie = htmlspecialchars($_POST["typeCategorie"]);
    }

    ?>

    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <div class="contenuBarre">

            </div>
        </div>
        <div class="rightColumn">
            <?php include 'topbanner.php'; ?>
            <div class="gallery-container">
                <?php
                // Connexion à la base de données 
                $utilisateur = $_ENV['DB_USER'];
                $serveur = $_ENV['DB_HOST'];
                $motdepasse = $_ENV['DB_PASS'];
                $basededonnees = $_ENV['DB_NAME'];
                $connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);
                // Vérifiez la connexion
                if ($connexion->connect_error) {
                    die("Erreur de connexion : " . $connexion->connect_error);
                }
                // Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
                $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti';
                $stmt = $connexion->prepare($requete);
                // "s" indique que la valeur est une chaîne de caractères
                $stmt->execute();
                $result = $stmt->get_result();

                if (($result->num_rows > 0) and ($_SESSION["isAdmin"] == true)) {
                    echo "<label>- producteurs :</label><br>";

                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="squarePanelAdmin">';

                        $targetID = $row["Id_Uti"];
                        $prenom = addslashes($row["Prenom_Uti"]);
                        $nom = addslashes($row["Nom_Uti"]);
                        $adresse = addslashes($row["Adr_Uti"]);
                        $profession = isset($row["Prof_Prod"]) ? addslashes($row["Prof_Prod"]) : "";
                        $imageSrc = file_exists("img_producteur/".$row["Id_Prod"].".png") ? "/img_producteur/".$row["Id_Prod"].".png" : "/img_producteur/default.png";
                    
                        echo '<button onclick="openPopup(\'' . $targetID . '\', \'' . $prenom . '\', \'' . $nom . '\', \'' . $adresse . '\', \'' . $profession . '\', \'' . $imageSrc . '\')">
                                '.$htmlSupprimerCompte.'
                              </button><br>';
                    
                        echo $htmlNomDeuxPoints, $row["Nom_Uti"] . "<br>";
                        echo $htmlPrénomDeuxPoints, $row["Prenom_Uti"] . "<br>";
                        echo $htmlMailDeuxPoints, $row["Mail_Uti"] . "<br>";
                        echo $htmlAdresseDeuxPoints, $row["Adr_Uti"] . "<br>";
                        echo $htmlProfessionDeuxPoints, $profession . "<br></div>";
                    }
                    echo '</div>';
                } else {
                    echo $htmlErrorDevTeam;
                }
                $stmt->close();
                $connexion->close();
                ?>
                <div class="gallery-container">
                    <?php
                    // Connexion à la base de données 
                    $utilisateur = $_ENV['DB_USER'];
                    $serveur = $_ENV['DB_HOST'];
                    $motdepasse = $_ENV['DB_PASS'];
                    $basededonnees = $_ENV['DB_NAME'];
                    $connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);
                    // Vérifiez la connexion
                    if ($connexion->connect_error) {
                        die("Erreur de connexion : " . $connexion->connect_error);
                    }
                    // Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
                    $requete = 'SELECT UTILISATEUR.Id_Uti, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti FROM UTILISATEUR WHERE UTILISATEUR.Id_Uti  NOT IN (SELECT PRODUCTEUR.Id_Uti FROM PRODUCTEUR) AND UTILISATEUR.Id_Uti NOT IN (SELECT ADMINISTRATEUR.Id_Uti FROM ADMINISTRATEUR) AND UTILISATEUR.Id_Uti<>0;';
                    $stmt = $connexion->prepare($requete);
                    // "s" indique que la valeur est une chaîne de caractères
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if (($result->num_rows > 0) and ($_SESSION["isAdmin"] == true)) {
                        echo "<label>" . $htmlUtilisateurs . "</label><br>";

                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="squarePanelAdmin">';
    
                            $targetID = $row["Id_Uti"];
                            $prenom = addslashes($row["Prenom_Uti"]);
                            $nom = addslashes($row["Nom_Uti"]);
                            $adresse = addslashes($row["Adr_Uti"]);
                            $imageSrc = "/img_producteur/default.png";
                        
                            echo '<button onclick="openPopup(\'' . $targetID . '\', \'' . $prenom . '\', \'' . $nom . '\', \'' . $adresse . '\', \'' . "" . '\', \'' . $imageSrc . '\')">
                                    '.$htmlSupprimerCompte.'
                                  </button><br>';
                        
                            echo $htmlNomDeuxPoints, $row["Nom_Uti"] . "<br>";
                            echo $htmlPrénomDeuxPoints, $row["Prenom_Uti"] . "<br>";
                            echo $htmlMailDeuxPoints, $row["Mail_Uti"] . "<br>";
                            echo $htmlAdresseDeuxPoints, $row["Adr_Uti"] . "<br></div>";
                        }
                        
                        echo '</div>';
                    } else {
                        echo $htmlErrorDevTeam;
                    }
                    $stmt->close();
                    $connexion->close();

                    ?>
                    <br>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
            <?php require "popups/gestion_popups.php"; ?>
</body>