<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
    <?php
        session_start();
        if (isset($_GET["rechercheVille"])==true){
            $rechercheVille=$_GET["rechercheVille"];
          }
          else{
            $rechercheVille="";
        }
        if (isset($_GET["categorie"])==false){
            $_GET["categorie"]="Tout";
        }
        if (isset($_SESSION["Id_Uti"])==false){
            $utilisateur=-1;
        }
        else{
            $utilisateur=$_SESSION["Id_Uti"];
        }
        if (isset($_GET["rayon"])==false){
            $rayon=10;
        }
        else{
            $rayon=$_GET["rayon"];
        }

        // récupération adresse du client
        function dbConnect(){
            $utilisateur = "inf2pj02";
            $serveur = "localhost";
            $motdepasse = "ahV4saerae";
            $basededonnees = "inf2pj_02";
            // Connect to database
            return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
          }
        $mabdd=dbConnect();           
        $queryAdrUti = $mabdd->query(('SELECT Adr_Uti FROM UTILISATEUR WHERE Id_Uti=\''.$utilisateur.'\';'));
        $returnQueryAdrUti = $queryAdrUti->fetchAll(PDO::FETCH_ASSOC);
        if (count($returnQueryAdrUti)>0){
            $Adr_Uti_En_Cours=$returnQueryAdrUti[0]["Adr_Uti"];
        }
        else{
            $Adr_Uti_En_Cours='PARIS';
        }
    ?>
    <div class="container">
        <div class="left-column">
            <img class="logo" src="img/logo.png">
			<center><strong><p>Rechercher par</p></strong></center>
			<form method="get" action="index.php"> 
			<label>- Profession :</label>
            <br>
			<select name="categorie" id="categories">
                <option value="Tout" <?php if($_GET["categorie"]=="Tout") echo 'selected="selected"';?>>Tout</option>
				<option value="Agriculteur" <?php if($_GET["categorie"]=="Agriculteur") echo 'selected="selected"';?>>Agriculteur</option>
				<option value="Vigneron" <?php if($_GET["categorie"]=="Vigneron") echo 'selected="selected"';?>>Vigneron</option>
				<option value="Maraîcher" <?php if($_GET["categorie"]=="Maraîcher") echo 'selected="selected"';?>>Maraîcher</option>
				<option value="Apiculteur" <?php if($_GET["categorie"]=="Apiculteur") echo 'selected="selected"';?>>Apiculteur</option>
				<option value="Éleveur de volaille" <?php if($_GET["categorie"]=="Éleveur de volaille") echo 'selected="selected"';?>>Éleveur de volaille</option>
				<option value="Viticulteur" <?php if($_GET["categorie"]=="Viticulteur") echo 'selected="selected"';?>>Viticulteur</option>
				<option value="Pépiniériste" <?php if($_GET["categorie"]=="Pépiniériste") echo 'selected="selected"';?>>Pépiniériste</option>
			</select>
            <br>
            <br>- Par ville :
            <br>
            <input type="text" name="rechercheVille" pattern="[A-Za-z0-9 ]{0,100}"  value="<?php echo $rechercheVille?>" placeholder="Ville">
            <br>
            <br>
            <br>- Autour de chez moi :
            <br>
            <input type="text" name="autourDeChezMoi" value="<?php echo $Adr_Uti_En_Cours;?>" placeholder="Adresse physique" size="auto">
            <br>
            <br>
            <input name="rayon" type="range" value="<?php echo $rayon;?>" min="1" max="100" step="1" onchange="AfficheRange2(this.value)" onkeyup="AfficheRange2(this.value)">
            <span id="monCurseurKm">Rayon de <?php echo $rayon;?></span>
            <script>
                function AfficheRange2(newVal) {
                    var monCurseurKm = document.getElementById("monCurseurKm");
                    if (newVal >= 100) {
                        monCurseurKm.innerHTML = "Rayon de " + newVal + "+ ";
                    } else {
                        monCurseurKm.innerHTML = "Rayon de " + newVal + " ";
                    }
                }
            </script>
            Km
            <br>
            <br>
            <br>
			<center><input type="submit" value="Rechercher"></center>
			</form>
        </div>
        <div class="right-column">
            <div class="fixed-banner">
                <!-- Partie gauche du bandeau -->
                <div class="banner-left">
                    <div class="button-container">
                        <button class="button"><a href="index.php">Accueil</a></button>
                        <button class="button"><a href="message.php">Messagerie</a></button>                 
						<button class="button"><a href="commandes.php">Achats</a></button>
                        <?php
                            if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"]==true)){
                                echo '<button class="button"><a href="mes_produits.php">Mes produits</a></button>';
                                echo '<button class="button"><a href="delivery.php">Préparation des commandes</a></button>';
                            }
                        ?>
                    </div>
                </div>
                <!-- Partie droite du bandeau -->
                <div class="banner-right">
					<?php 
                    if (isset($_SESSION['Mail_Uti'])) {  
                    echo '<a class="fixed-size-button" href="user_informations.php" >';
					echo $_SESSION['Mail_Uti']; 
					}
					else {
                    echo '<a class="fixed-size-button" href="form_sign_in.php" >';
					echo "connection";
					}
					?>
					</a>    
                </div>
            </div>
			<div class="contenu">
            <!-- Contenu de la partie droite (sous le bandeau) -->
            <h1> PRODUCTEURS : </h1>
            <?php
             ?> 
				<div class="gallery-container">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "GET") {
                            if (isset($_GET["categorie"])) {
                                $categorie = $_GET["categorie"];
                                // Connexion à la base de données 
                                $utilisateur = "inf2pj02";
                                $serveur = "localhost";
                                $motdepasse = "ahV4saerae";
                                $basededonnees = "inf2pj_02";
                                $connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);
                                // Vérifiez la connexion
                                if ($connexion->connect_error) {
                                    die("Erreur de connexion : " . $connexion->connect_error);
                                }
                                // Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
                                if ($_GET["categorie"]=="Tout"){
                                    $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti WHERE PRODUCTEUR.Prof_Prod LIKE \'%\'';
                                }else{
                                    $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti WHERE PRODUCTEUR.Prof_Prod ="'.$categorie.'"';
                                    //$stmt->bind_param("s", $categorie);
                                }
                                if ($rechercheVille!=""){
                                    $requete=$requete.' AND Adr_Uti LIKE \'%, _____ %'.$rechercheVille.'%\'';
                                }
                                $stmt = $connexion->prepare($requete);
                                 // "s" indique que la valeur est une chaîne de caractères
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // récupère les coordonnées de l'utiliasteur
                                // URL vers l'API Nominatim
                                $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($Adr_Uti_En_Cours);
                                // Configurer les paramètres du proxy
/*
$context = array();
$proxy = getenv("http_proxy");
echo $proxy;
if ($proxy !== null) {

    $context['http'] = array(
        'proxy' => str_replace("http", "tcp", $proxy),
        'request_fulluri' => true
    );
}
$cxContext = stream_context_create($context);

//file_get_contents($file, false, $cxContext);


*/


				/*$opts = array ('http'=>array('proxy '=> 'tcp://proxy.univ-lemans.fr:3128', 'request_fulluri'=>true));
				$context=stream_context_create($opts);*/
				/*$proxy = 'tcp://proxy.univ-lemans.fr:3128'; // Remplacez avec votre adresse et port de proxy
                                $proxy_context = stream_context_create([
                                    'http' => [
                                        'proxy' => $proxy,
                                        'request_fulluri' => true,
                                    ],
                                    'https' => [
                                        'proxy' => $proxy,
                                        'request_fulluri' => true,
                                    ],
                                ]);*/
                                // Utiliser le flux contextuel avec file_get_contents
                                
//$response = file_get_contents("https://www.google.fr", false, $context);

$ch=curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$response = curl_exec($ch);
var_dump($response);
                                // Analyser la réponse JSON
                                $data = json_decode($response);
                                // Vérifier si la réponse a été correctement analysée
                                if (!empty($data) && is_array($data) && isset($data[0])) {
                                    // Récupérer la latitude et la longitude
                                    $latitude = $data[0]->lat;
                                    $longitude = $data[0]->lon;
                                    // Afficher les résultats
                                    echo "Latitude : $latitude<br>";
                                    echo "Longitude : $longitude";
                                } else {
                                    // En cas d'erreur ou si aucune correspondance n'est trouvée, afficher un message
                                    echo "Erreur lors de l'extraction des données de géocodage.";
                                }

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<a href="producteur.php?Id_Prod='. $row["Id_Uti"] . '" class="square"  >';
                                        echo "Nom : " . $row["Nom_Uti"] . "<br>";
                                        echo "Prénom : " . $row["Prenom_Uti"] . "<br>";
                                        echo "Adresse : " . $row["Adr_Uti"] . "<br>";
                                        echo '<img src="/~inf2pj02/img_producteur/' . $row["Id_Prod"]  . '.png" alt="Image utilisateur" style="width: 100%; height: 85%;" ><br>';
                                        echo '</a> ';                                        
                                    }
                                } else {
                                    echo "Aucun résultat ne correspond à ces critères";
                                }
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        
                                    }
                                }
                                $stmt->close();
                                $connexion->close();
                            }
                        }
                        ?>
                    
                </div>
					
				
			</div>
			
		</div>
    </div>
</body>
</html>
