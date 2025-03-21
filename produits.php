<?php
include_once __DIR__ . "/loadenv.php";
require "language.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
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
    if (!(isset($_SESSION['Id_Uti'])) || !isset($_SESSION['isProd'])){
        header("Location: index.php");
        exit();
    }
    $utilisateur = $_SESSION["Id_Uti"];
    htmlspecialchars($utilisateur);

    $returnQueryIdProd = $db->select('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti= ?;', 's', [$utilisateur]);
    $Id_Prod = $returnQueryIdProd[0]["Id_Prod"];
    ?>

    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <div class="contenuBarre filtre-container">
                <!-- some code -->



                <center>
                <p class="filtre-titre"><strong><?php echo $htmlAjouterProduit; ?></strong></p>
                <form action="insert_products.php" method="post" enctype="multipart/form-data">
                    <label for="pwd"><?php echo $htmlProduitDeuxPoints; ?> </label>
                    <input type="text" pattern="[A-Za-z0-9 ]{0,100}" name="nomProduit" placeholder="<?php echo $htmlNomDuProduit; ?>" required><br><br>

                    <select name="categorie">
                        <option value="6"><?php echo $htmlAnimaux; ?></option>
                        <option value="1"><?php echo $htmlFruit; ?></option>
                        <option value="3"><?php echo $htmlGraine; ?></option>
                        <option value="2"><?php echo $htmlLégume; ?></option>
                        <option value="7"><?php echo $htmlPlanche; ?></option>
                        <option value="4"><?php echo $htmlViande; ?></option>
                        <option value="5"><?php echo $htmlVin; ?></option>
                    </select>
                    <br>
                    <br><?php echo $htmlPrix; ?>
                    <input style="width: 50px;" type="number" min="0" name="prix" step="0.01" required>€
                    
                    <label>
                        <input type="radio" name="unitPrix" value="Kg" checked="true" onchange="updateStockUnit()"> <?php echo $htmlKg; ?>
                    </label>
                    <label>
                        <input type="radio" name="unitPrix" value="L" onchange="updateStockUnit()"> <?php echo $htmlL; ?>
                    </label>
                    <label>
                        <input type="radio" name="unitPrix" value="m²" onchange="updateStockUnit()"> <?php echo $htmlM2; ?>
                    </label>
                    <label>
                        <input type="radio" name="unitPrix" value="Pièce" onchange="updateStockUnit()"> <?php echo $htmlPiece; ?>
                    </label>
                    
                    <br>
                    <br><?php echo $htmlStockDeuxPoints; ?>
                    <input type="number" style="width: 50px;" min="0" name="quantite" step="0.01" required id="stockQuantity">
                    <span id="selectedStockUnit"><?php echo $htmlKg; ?></span>
                    
                    <br>
                    <br>
                    <strong><?php echo $htmlImageDeuxPoints; ?></strong>
                    <input type="file" name="image" accept=".png">
                    <br>
                    <br>
                    <br>
                    <input type="submit" value="<?php echo $htmlAjouterProduit; ?>">
                </form>

                <script>
                function updateStockUnit() {
                    let selectedUnit = document.querySelector('input[name="unitPrix"]:checked').value;
                    document.getElementById("selectedStockUnit").innerText = selectedUnit;

                    let stockInput = document.getElementById("stockQuantity");
                    if (selectedUnit === "Pièce") {
                        stockInput.setAttribute("step", "1");
                        stockInput.setAttribute("min", "1");
                    } else {
                        stockInput.setAttribute("step", "0.01");
                        stockInput.setAttribute("min", "0.01");
                    }
                }
                </script>
                </center>


            </div>
        </div>
        <div class="rightColumn">
            <?php include "topbanner.php"; ?>

            <!-- partie de gauche avec les produits -->
            <p>
                <center><U><?php echo $htmlMesProduitsEnStock; ?></U></center>
            </p>
            <div class="gallery-container">
                <?php
                $returnQueryGetProducts = $db->select('SELECT Id_Produit, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit, Nom_Unite_Stock FROM Produits_d_un_producteur WHERE Id_Prod= ?;', 'i', [$Id_Prod]);

                $i = 0;
                if (count($returnQueryGetProducts) == 0) {
                    echo "<?php echo $htmlAucunProduitEnStock; ?>";
                } else {
                    while ($i < count($returnQueryGetProducts)) {
                        $Id_Produit = $returnQueryGetProducts[$i]["Id_Produit"];
                        $nomProduit = $returnQueryGetProducts[$i]["Nom_Produit"];
                        $typeProduit = $returnQueryGetProducts[$i]["Desc_Type_Produit"];
                        $prixProduit = $returnQueryGetProducts[$i]["Prix_Produit_Unitaire"];
                        $QteProduit = $returnQueryGetProducts[$i]["Qte_Produit"];
                        $unitePrixProduit = $returnQueryGetProducts[$i]["Nom_Unite_Prix"];
                        $Nom_Unite_Stock = $returnQueryGetProducts[$i]["Nom_Unite_Stock"];
                        if ($QteProduit >= 0) {
                            echo '<style>';
                            echo 'form { display: inline-block; margin-right: 1px; }'; // Ajustez la marge selon vos besoins
                            echo 'button { display: inline-block; }';
                            echo '</style>';
                            echo '<div class="square1" >';
                            echo $htmlProduitDeuxPoints, $nomProduit . "<br>";
                            echo $htmlTypeDeuxPoints, $typeProduit . "<br><br>";
                            echo '<img class="img-produit" src="img_produit/' . $Id_Produit  . '.png" alt="' . $htmlImageNonFournie . '" style="width: 85%; height: 70%;" ><br>';
                            echo $htmlPrix, $prixProduit . ' €/' . $unitePrixProduit . "<br>";
                            echo $htmlStockDeuxPoints, $QteProduit . ' ' . $Nom_Unite_Stock . "<br>";
                            echo '<form action="product_modification.php" method="post">';
                            echo '<input type="hidden" name="modifyIdProduct" value="' . $Id_Produit . '">';
                            echo '<button type="submit" name="action">' . $htmlModifier . '</button>';
                            echo '</form>';
                            echo '<form action="delete_product.php" method="post">';
                            echo '<input type="hidden" name="deleteIdProduct" value="' . $Id_Produit . '">';
                            echo '<button type="submit" name="action">' . $htmlSupprimer . '</button>';
                            echo '</form>';
                            echo '</div> ';
                        }
                        $i++;
                    }
                }
                ?>
            </div>

            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>