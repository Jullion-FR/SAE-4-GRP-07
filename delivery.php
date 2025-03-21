<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    include_once __DIR__ . "/loadenv.php";
    require "language.php";
    ?>
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
    if (!isset($_SESSION['Id_Uti']) || $_SESSION['isProd'] == false ){
        header("Location: index.php");
        exit();
    }
  
    $utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);
    $filtreCategorie = isset($_POST["typeCategorie"]) ? htmlspecialchars($_POST["typeCategorie"]) : 0;
    ?>

    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" src="img/logo.png" alt="Logo"></a>
            <br><br>
            <div class="contenuBarre filtre-container">
                <center>
                    <p><strong><?php echo $htmlFiltrerParDeuxPoints; ?></strong></p>
                </center>

                <form action="delivery.php" method="post">
                    <label><input type="radio" name="typeCategorie" value="0" <?php if ($filtreCategorie == 0) echo 'checked'; ?>> <?php echo $htmlTOUT; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="1" <?php if ($filtreCategorie == 1) echo 'checked'; ?>> <?php echo $htmlENCOURS; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="2" <?php if ($filtreCategorie == 2) echo 'checked'; ?>> <?php echo $htmlPRETE; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="4" <?php if ($filtreCategorie == 4) echo 'checked'; ?>> <?php echo $htmlLIVREE; ?></label><br>
                    <label><input type="radio" name="typeCategorie" value="3" <?php if ($filtreCategorie == 3) echo 'checked'; ?>> <?php echo $htmlANNULEE; ?></label><br><br>
                    <center><input class="filtre-container-producteur" type="submit" value="<?php echo $htmlFiltrer; ?>"></center>
                </form>
            </div>
        </div>

        <div class="rightColumn">
            <?php include 'topbanner.php'; ?>

            <div class="contenuPage">
                <?php
                $query = 'SELECT Desc_Statut, Id_Commande, COMMANDE.Id_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Prenom_Uti, COMMANDE.Id_Statut 
                          FROM COMMANDE 
                          INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod 
                          INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut 
                          INNER JOIN UTILISATEUR ON COMMANDE.Id_Uti=UTILISATEUR.Id_Uti 
                          WHERE info_producteur.Id_Uti = ?';
                $requestTypes = 'i';
                $requestArgs = [$utilisateur];
                if ($filtreCategorie != 0) {
                    $query .= ' AND COMMANDE.Id_Statut = ?';
                    $requestTypes .= 'i';
                    array_push($requestArgs, $filtreCategorie);
                }

                $query .= ' ORDER BY COMMANDE.Id_Commande DESC';

                $returnQueryGetCommande = $db->select($query, $requestTypes, $requestArgs);

                if (count($returnQueryGetCommande) == 0) {
                    echo $htmlAucuneCommande;
                } else {
                    foreach ($returnQueryGetCommande as $commande) {
                        echo '<div class="commande">';
                        echo '<h3>Commande n°' . $commande["Id_Commande"] . ' : ' . $commande["Prenom_Uti"] . ' ' . $commande["Nom_Uti"] . '</h3>';
                        echo '<p class="statut">Statut : <strong>' . mb_strtoupper($commande["Desc_Statut"]) . '</strong></p>';

                        if ($commande["Id_Statut"] != 4 && $commande["Id_Statut"] != 3) {
                            echo '<form action="change_status_commande.php" method="post" class="status-form">';
                            echo '<select class="commande-affiche-stat" name="categorie">
                                    <option value="">' . $htmlModifierStatut . '</option>
                                    <option value="1">' . $htmlENCOURS . '</option>
                                    <option value="2">' . $htmlPRETE . '</option>
                                    <option value="3">' . $htmlANNULEE . '</option>
                                    <option value="4">' . $htmlLIVREE . '</option>
                                  </select>';
                            echo '<input type="hidden" name="idCommande" value="' . $commande["Id_Commande"] . '">';
                            echo '<button class="commande-affiche-stat" type="submit">' . $htmlConfirmer . '</button>';
                            echo '</form>';
                        }

                        $queryProduit = 'SELECT Nom_Produit, Qte_Produit_Commande, Nom_Unite_Prix 
                                         FROM produits_commandes 
                                         WHERE Id_Commande = ?';
                        $produitsCommande = $db->select($queryProduit, 'i', [$commande["Id_Commande"]]);

                        echo '<div class="produits">';
                        foreach ($produitsCommande as $produit) {
                            echo '<strong>' . $produit['Qte_Produit_Commande'] . ' ' . $produit['Nom_Unite_Prix'] . '</strong> de ' . $produit['Nom_Produit'] . '<br>';
                        }
                        echo '</div>';

                        echo '<div class="commande-buttons">';
                        echo '<input type="button" onclick="window.location.href=\'messagerie.php?Id_Interlocuteur=' . $commande["Id_Uti"] . '\'" value="' . $htmlEnvoyerMessage . '">';
                        ?>
                        <form action="download_pdf.php" method="post">
                            <input type="hidden" name="idCommande" value="<?php echo $commande["Id_Commande"]; ?>">
                            <button type="submit"><?php echo $htmlGenererPDF; ?></button>
                        </form>
                        <?php
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        
            <?php include 'footer.php'; ?>

    </div>
</div>

</body>
</html>