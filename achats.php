<?php
include_once __DIR__ . "/loadenv.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require "language.php"; ?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>

<?php
if (!isset($_SESSION)) {
    session_start();
}

function dbConnect() {
    $utilisateur = $_ENV['DB_USER'];
    $serveur = $_ENV['DB_HOST'];
    $motdepasse = $_ENV['DB_PASS'];
    $basededonnees = $_ENV['DB_NAME'];
    return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
}

$bdd = dbConnect();
$utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);
$filtreCategorie = isset($_POST["typeCategorie"]) ? htmlspecialchars($_POST["typeCategorie"]) : 0;
?>

<div class="container">
    <div class="leftColumn">
        <a href="index.php"><img class="logo" src="img/logo.png" alt="Logo"></a>
        <br><br>
        <div class="contenuBarre filtre-container">
            <center><p class="filtre-titre"><strong><?php echo $htmlFiltrerParDeuxPoints; ?></strong></p></center>
            <form action="achats.php" method="post" class="filtre-form">
                <label class="filtre-label">
                    <input type="radio" name="typeCategorie" value="0" <?php if ($filtreCategorie == 0) echo 'checked'; ?>> 
                    <?php echo $htmlTOUT; ?>
                </label>
                <label class="filtre-label">
                    <input type="radio" name="typeCategorie" value="1" <?php if ($filtreCategorie == 1) echo 'checked'; ?>> 
                    <?php echo $htmlENCOURS; ?>
                </label>
                <label class="filtre-label">
                    <input type="radio" name="typeCategorie" value="2" <?php if ($filtreCategorie == 2) echo 'checked'; ?>> 
                    <?php echo $htmlPRETE; ?>
                </label>
                <label class="filtre-label">
                    <input type="radio" name="typeCategorie" value="4" <?php if ($filtreCategorie == 4) echo 'checked'; ?>> 
                    <?php echo $htmlLIVREE; ?>
                </label>
                <label class="filtre-label">
                    <input type="radio" name="typeCategorie" value="3" <?php if ($filtreCategorie == 3) echo 'checked'; ?>> 
                    <?php echo $htmlANNULEE; ?>
                </label>
                <center>
                    <input type="submit" value="<?php echo $htmlFiltrer; ?>" class="filtre-bouton">
                </center>
            </form>
        </div>
    </div>

    <div class="rightColumn">
        <div class="topBanner">
            <div class="divNavigation">
                <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                <?php
                if (isset($_SESSION["Id_Uti"])) {
                    echo '<a class="bontonDeNavigation" href="messagerie.php">'.$htmlMessagerie.'</a>';
                    echo '<a class="bontonDeNavigation" href="achats.php">'.$htmlAchats.'</a>';
                }
                if (isset($_SESSION["isProd"]) && $_SESSION["isProd"]) {
                    echo '<a class="bontonDeNavigation" href="produits.php">'.$htmlProduits.'</a>';
                    echo '<a class="bontonDeNavigation" href="delivery.php">'.$htmlCommandes.'</a>';
                }
                if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
                    echo '<a class="bontonDeNavigation" href="panel_admin.php">'.$htmlPanelAdmin.'</a>';
                }
                ?>
            </div>
        </div>

        <div class="contenuPage">
            <?php
            
            
            $query = 'SELECT PRODUCTEUR.Id_Uti, Desc_Statut, Id_Commande, Nom_Uti, Prenom_Uti, Adr_Uti, COMMANDE.Id_Statut 
                      FROM COMMANDE 
                      INNER JOIN PRODUCTEUR ON COMMANDE.Id_Prod=PRODUCTEUR.Id_Prod 
                      INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod 
                      INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut 
                      WHERE COMMANDE.Id_Uti = :utilisateur';






            $query = "SELECT PRODUCTEUR.Id_Uti, Desc_Statut, Id_Commande, Nom_Uti, Prenom_Uti, Adr_Uti, COMMANDE.Id_Statut 
                                    FROM COMMANDE 
                                    INNER JOIN PRODUCTEUR ON COMMANDE.Id_Prod=PRODUCTEUR.Id_Prod 
                                    INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod 
                                    INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut 
                                    WHERE COMMANDE.Id_Uti = ?";
            
            $types = "i";
            $values = [$utilisateur];


            if ($filtreCategorie != 0) {
                $query .= ' AND COMMANDE.Id_Statut = ?';  /*filtreCategorie*/
                $types .= "i";
                $values[] = $filtreCategorie; /*à verif"*/
            }

            $query .= ' ORDER BY COMMANDE.Id_Commande DESC';


            $returnQueryGetCommande = $db->select($query, $types, $values);

            /*
            $queryGetCommande = $bdd->prepare($query);


            $queryGetCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
            if ($filtreCategorie != 0) {
                $queryGetCommande->bindParam(":filtreCategorie", $filtreCategorie, PDO::PARAM_STR);
            }
            $queryGetCommande->execute();
            $returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);
            */





            if (count($returnQueryGetCommande) == 0) {
                ?><div class="aucuneCommande"><?php
                echo $htmlAucuneCommande;
                echo '<br><input type="button" onclick="window.location.href=\'index.php\'" value="'.$htmlDecouverteProducteurs.'">';
                ?></div><?php
            } else {
                foreach ($returnQueryGetCommande as $commande) {
                    echo '<div class="commande">';
                    echo '<h3>Commande n°' . $commande["Id_Commande"] . ' chez ' . $commande["Prenom_Uti"] . ' ' . mb_strtoupper($commande["Nom_Uti"]) . '</h3>';
                    echo '<p class="statut">Statut : <strong>' . mb_strtoupper($commande["Desc_Statut"]) . '</strong></p>';



                    /*$queryProduit = 'SELECT Nom_Produit, Qte_Produit_Commande, Nom_Unite_Prix, Prix_Produit_Unitaire 
                                     FROM produits_commandes 
                                     WHERE Id_Commande = :idCommande';
                    $queryGetProduitCommande = $bdd->prepare($queryProduit);
                    $queryGetProduitCommande->bindParam(':idCommande', $commande["Id_Commande"], PDO::PARAM_INT);
                    $queryGetProduitCommande->execute();
                    $produitsCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);*/


                    $produitsCommande = $db->select("SELECT Nom_Produit, Qte_Produit_Commande, Nom_Unite_Prix, Prix_Produit_Unitaire 
                                                     FROM produits_commandes 
                                                     WHERE Id_Commande = ?", "i", [$commande["Id_Commande"]]);



                    echo '<div class="produits">';
                    $total = 0;
                    foreach ($produitsCommande as $produit) {
                        $sousTotal = $produit['Prix_Produit_Unitaire'] * $produit['Qte_Produit_Commande'];
                        $total += $sousTotal;
                        echo '<p>' . $produit['Qte_Produit_Commande'] . ' ' . $produit['Nom_Unite_Prix'] . ' de ' . $produit['Nom_Produit'] . ' = ' . $sousTotal . '€</p>';
                    }
                    echo '</div>';
                    echo '<p class="total">Total : <strong>' . $total . '€</strong></p>';

                    echo '<div class="commande-buttons">';
                    echo '<input type="button" onclick="window.location.href=\'messagerie.php?Id_Interlocuteur=' . $commande["Id_Uti"] . '\'" value="' . $htmlEnvoyerMessage . '">';
                    echo '<form action="download_pdf.php" method="post">
                              <input type="hidden" name="idCommande" value="' . $commande["Id_Commande"] . '">
                              <button type="submit">' . $htmlGenererPDF . '</button>
                          </form>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <br><br>
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
</html>
