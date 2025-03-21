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
   
    $utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);
    $Id_Produit_Update = htmlspecialchars($_POST["modifyIdProduct"]);
    $_SESSION["Id_Produit"] = $Id_Produit_Update;

    $returnQueryGetProducts = $db->select('SELECT * FROM PRODUIT WHERE Id_Produit = ?;', 'i', [$Id_Produit_Update]);
    //var_dump($returnQueryGetProducts);
    $IdProd = $returnQueryGetProducts[0]["Id_Prod"];
    $Nom_Produit = $returnQueryGetProducts[0]["Nom_Produit"];
    $Id_Type_Produit = $returnQueryGetProducts[0]["Id_Type_Produit"];
    $Qte_Produit = $returnQueryGetProducts[0]["Qte_Produit"];
    $Id_Unite_Stock = $returnQueryGetProducts[0]["Id_Unite_Stock"];
    $Prix_Produit_Unitaire = $returnQueryGetProducts[0]["Prix_Produit_Unitaire"];
    $Id_Unite_Prix = $returnQueryGetProducts[0]["Id_Unite_Prix"];
    //var_dump($Id_Type_Produit);
    ?>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <div class="contenuBarre">


                <center>
                    <p><strong><?php echo $htmlModifierProduit ?></strong></p>
                    <form action="modify_product.php" method="post" enctype="multipart/form-data">
                        <label for="nomProduit"><?php echo $htmlProduitDeuxPoints ?> </label>
                        <input type="hidden" name="IdProductAModifier" value="<?php echo $Id_Produit_Update ?>">
                        <input type="text" name="nomProduit" value="<?php echo htmlspecialchars($Nom_Produit) ?>" required><br><br>

                        <label for="categorie">Categorie :</label>
                        <select name="categorie">
                            <option value="1" <?= $Id_Type_Produit == 1 ? "selected" : "" ?>>Fruits</option>
                            <option value="2" <?= $Id_Type_Produit == 2 ? "selected" : "" ?>>Légumes</option>
                            <option value="3" <?= $Id_Type_Produit == 3 ? "selected" : "" ?>>Graines</option>
                            <option value="4" <?= $Id_Type_Produit == 4 ? "selected" : "" ?>>Viande</option>
                            <option value="5" <?= $Id_Type_Produit == 5 ? "selected" : "" ?>>Vin</option>
                            <option value="6" <?= $Id_Type_Produit == 6 ? "selected" : "" ?>>Animaux</option>
                            <option value="7" <?= $Id_Type_Produit == 7 ? "selected" : "" ?>>Planches</option>
                        </select>
                        <br><br>

                        <label><?php echo $htmlPrix ?> :</label>
                        <input style="width: 50px;" value="<?= $Prix_Produit_Unitaire ?>" type="number" min="0" name="prix" step="0.01" required> €

                        <label><input type="radio" name="unitPrix" value="1" <?= $Id_Unite_Prix == 1 ? "checked" : "" ?> onchange="updateStockUnit()"> Kg</label>
                        <label><input type="radio" name="unitPrix" value="2" <?= $Id_Unite_Prix == 2 ? "checked" : "" ?> onchange="updateStockUnit()"> L</label>
                        <label><input type="radio" name="unitPrix" value="3" <?= $Id_Unite_Prix == 3 ? "checked" : "" ?> onchange="updateStockUnit()"> m²</label>
                        <label><input type="radio" name="unitPrix" value="4" <?= $Id_Unite_Prix == 4 ? "checked" : "" ?> onchange="updateStockUnit()"> Pièce</label>

                        <br><br>

                        <label><?php echo $htmlStockDeuxPoints ?> :</label>
                        <input type="number" style="width: 50px;" name="quantite" id="stockQuantity" value="<?= $Qte_Produit ?>" step="0.01" required>
                        <input type="hidden" name="unitQuantite" id="unitQuantite" value="<?= $Id_Unite_Prix ?>">
                        <span id="selectedStockUnit">
                            <?php
                            switch ($Id_Unite_Prix) {
                                case 1: echo "Kg"; break;
                                case 2: echo "L"; break;
                                case 3: echo "m²"; break;
                                case 4: echo "Pièce"; break;
                            }
                            ?>
                        </span>

                        <br><br>
                        <input type="file" name="image" accept=".png"><br><br>
                        <input type="submit" value="<?php echo $htmlConfirmerModifProd ?>">
                    </form>

                    <script>
                    function updateStockUnit() {
                        let radios = document.querySelectorAll('input[name="unitPrix"]');
                        let selectedValue;
                        radios.forEach(radio => {
                            if (radio.checked) {
                                selectedValue = radio.value;
                            }
                        });

                        let stockInput = document.getElementById("stockQuantity");
                        let stockUnitText = document.getElementById("selectedStockUnit");
                        let unitQuantiteHidden = document.getElementById("unitQuantite");

                        switch (selectedValue) {
                            case "1":
                                stockUnitText.innerText = "Kg";
                                stockInput.step = "0.01";
                                stockInput.min = "0.01";
                                unitQuantiteHidden.value = "1";
                                break;
                            case "2":
                                stockUnitText.innerText = "L";
                                stockInput.step = "0.01";
                                stockInput.min = "0.01";
                                unitQuantiteHidden.value = "2";
                                break;
                            case "3":
                                stockUnitText.innerText = "m²";
                                stockInput.step = "0.01";
                                stockInput.min = "0.01";
                                unitQuantiteHidden.value = "3";
                                break;
                            case "4":
                                stockUnitText.innerText = "Pièce";
                                stockInput.step = "1";
                                stockInput.min = "1";
                                unitQuantiteHidden.value = "4";
                                break;
                        }
                    }
                    </script>

                    <br>
                    <?php
                    //echo '<img class="img-produit" src="/~inf2pj02/img_produit/' . $Id_Produit_Update  . '.png" alt="Image non fournie" style="width: 100%; height: 85%;" ><br>';
                    ?>
                    <br>
                    <br>
                </center>

            </div>
        </div>
        <div class="rightColumn">
        <?php include 'topbanner.php'; ?>




            <!-- partie de gauche avec les produits -->
            <p>
                <center><U><?php echo $htmlMesProduitsEnStock ?></U></center>
            </p>
            <div class="gallery-container">
                <?php
                $returnQueryIdProd = $db->select('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti = ?;', 'i', [$utilisateur]);
                $Id_Prod = $returnQueryIdProd[0]["Id_Prod"];

                $returnQueryGetProducts = $db->select('SELECT Id_Produit, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit, Nom_Unite_Stock FROM Produits_d_un_producteur WHERE Id_Prod= ?;', 'i', [$Id_Prod]);

                $i = 0;
                if (count($returnQueryGetProducts) == 0) {
                    echo $htmlAucunProduitEnStock;
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
                                    <?php
                                        if ($Id_Produit == $Id_Produit_Update) {
                                            echo '<input type="submit" disabled="disabled" value="' . $htmlModification . '"/></button>';
                                        } else {
                                            echo '<form action="product_modification.php" method="post">';
                                            echo '<input type="hidden" name="modifyIdProduct" value="' . $Id_Produit . '">';
                                            echo '<button type="submit" name="action">' . $htmlModifier . '</button>';
                                            echo '</form>';
                                        }
                                    ?>
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