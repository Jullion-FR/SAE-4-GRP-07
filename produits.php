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
                            ?>
                            <div class="square2">
                                <p class="title"><?= $nomProduit; ?></p>
                                <img class="img-produit" src="img_produit/<?php echo $Id_Produit; ?>.png" alt="<?php echo $htmlImageNonFournie; ?>">
                                <p><span><?= $htmlTypeDeuxPoints ?></span> <?= $typeProduit ?></p>
                                <p><span><?= $htmlPrix ?></span> <?= $prixProduit . ' €/' . $unitePrixProduit; ?></p>
                                <p><span><?= $htmlStockDeuxPoints ?></span> <?=  $QteProduit . ' ' . $Nom_Unite_Stock; ?></p>
                                <div class="btns">
                                    <form action="product_modification.php" method="post">
                                        <input type="hidden" name="modifyIdProduct" value="<?php echo $Id_Produit; ?>">
                                        <button type="submit" name="action"><?php echo $htmlModifier; ?></button>
                                    </form>
                                    <form action="delete_product.php" method="post">
                                        <input type="hidden" name="deleteIdProduct" value="<?php echo $Id_Produit; ?>">
                                        <button type="submit" name="action"><?php echo $htmlSupprimer; ?></button>
                                    </form>
                                </div>
                            </div>
                            <?php
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