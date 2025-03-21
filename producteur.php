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

    function dbConnect()
    {
        $utilisateur = $_ENV['DB_USER'];
        $serveur = $_ENV['DB_HOST'];
        $motdepasse = $_ENV['DB_PASS'];
        $basededonnees = $_ENV['DB_NAME'];

        // Connect to database
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    }



    // variable utilisée plusieurs fois par la suite
    $Id_Prod = htmlspecialchars($_GET["Id_Prod"]);
    /*partie de droite avec les infos producteur*/
    $bdd = dbConnect();
    $queryInfoProd = $bdd->prepare(('SELECT UTILISATEUR.Id_Uti, UTILISATEUR.Adr_Uti, Prenom_Uti, Nom_Uti, Prof_Prod FROM UTILISATEUR INNER JOIN PRODUCTEUR ON UTILISATEUR.Id_Uti = PRODUCTEUR.Id_Uti WHERE PRODUCTEUR.Id_Prod= :Id_Prod ;'));
    $queryInfoProd->bindParam(":Id_Prod", $Id_Prod, PDO::PARAM_STR);
    $queryInfoProd->execute();
    $returnQueryInfoProd = $queryInfoProd->fetchAll(PDO::FETCH_ASSOC);

    // recupération des paramètres de la requête qui contient 1 élément
    $idUti = $returnQueryInfoProd[0]["Id_Uti"];
    $address = $returnQueryInfoProd[0]["Adr_Uti"];
    $nom = $returnQueryInfoProd[0]["Nom_Uti"];
    $prenom = $returnQueryInfoProd[0]["Prenom_Uti"];
    $profession = $returnQueryInfoProd[0]["Prof_Prod"];

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_GET["filtreType"]) == true) {
        $filtreType = htmlspecialchars($_GET["filtreType"]);
    } else {
        $filtreType = "TOUT";
    }
    if (isset($_GET["tri"]) == true) {
        $tri = htmlspecialchars($_GET["tri"]);
    } else {
        $tri = "No";
    }
    if (isset($_GET["rechercheNom"]) == true) {
        $rechercheNom = htmlspecialchars($_GET["rechercheNom"]);
    } else {
        $rechercheNom = "";
    }
    ?>
    <div class="container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>
            <br><br>
            <div class="contenuBarre filtre-container">

                <center>
                    <p class="filtre-titre"><strong><?php echo $htmlRechercherPar; ?></strong></p>
                </center>
                <br>
                <form action="producteur.php" method="get">
                    Nom : 
                    <br>
                    <input class="filtre-container-producteur" type="text" name="rechercheNom" value="<?php echo $rechercheNom ?>" placeholder="<?php echo $htmlNom; ?>">
                    <br>
                    <br>
                    Type de produit :
                    <br>
                    <input type="hidden" name="Id_Prod" value="<?php echo $Id_Prod ?>">
                    <label>
                        <input type="radio" name="filtreType" value="TOUT" <?php if ($filtreType == "TOUT") echo 'checked="true"'; ?>> <?php echo $htmlTout; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="ANIMAUX" <?php if ($filtreType == "ANIMAUX") echo 'checked="true"'; ?>> <?php echo $htmlAnimaux; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="FRUITS" <?php if ($filtreType == "FRUITS") echo 'checked="true"'; ?>> <?php echo $htmlFruits; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="GRAINS" <?php if ($filtreType == "GRAINS") echo 'checked="true"'; ?>> <?php echo $htmlGraines; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="LÉGUMES" <?php if ($filtreType == "LÉGUMES") echo 'checked="true"'; ?>> <?php echo $htmlLégumes; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="PLANCHES" <?php if ($filtreType == "PLANCHES") echo 'checked="true"'; ?>> <?php echo $htmlPlanches; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="VIANDE" <?php if ($filtreType == "VIANDE") echo 'checked="true"'; ?>> <?php echo $htmlViande; ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="filtreType" value="VIN" <?php if ($filtreType == "VIN") echo 'checked="true"'; ?>> <?php echo $htmlVin; ?>
                    </label>
                    <br>
                    <br>
                    <br>
                    <?php echo $htmlTri; ?>
                    <select class="filtre-container-producteur" name="tri">
                        <option value="No" <?php if ($tri == "No") echo 'selected="selected"'; ?>><?php echo $htmlAucunTri; ?></option>
                        <option value="PrixAsc" <?php if ($tri == "PrixAsc") echo 'selected="selected"'; ?>><?php echo $htmlPrixCroissant; ?></option>
                        <option value="PrixDesc" <?php if ($tri == "PrixDesc") echo 'selected="selected"'; ?>><?php echo $htmlPrixDecroissant; ?></option>
                        <option value="Alpha" <?php if ($tri == "Alpha") echo 'selected="selected"'; ?>><?php echo $htmlOrdreAlpha; ?></option>
                        <option value="AntiAlpha" <?php if ($tri == "AntiAlpha") echo 'selected="selected"'; ?>><?php echo $htmlOrdreAntiAlpha; ?></option>
                    </select>
                    <br>
                    <br>
                    <center>
                        <input class="filtre-container-producteur" type="submit" value="<?php echo $htmlRechercher; ?>">
                    </center>
                </form>
                <br>
                <br>


            </div>
        </div>
        <div class="rightColumn">
            
            <?php include "topBanner.php"; ?>

            <form method="get" action="insert_commande.php">
                <input type="hidden" name="Id_Prod" value="<?php echo $Id_Prod ?>">

                <div class="content-container">
                    <div class="product">
                        <!-- partie de gauche avec les produits -->
                        <p>
                            <center><U><?php echo $htmlProduitsProposesDeuxPoints; ?></U></center>
                        </p>
                        <div class="boutique-container">
                            <?php
                            $bdd = dbConnect();
                            // Filtrage du type de produit
                            $query = 'SELECT Id_Produit, Id_Prod, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit 
                                    FROM Produits_d_un_producteur WHERE Id_Prod= :Id_Prod';

                            if ($filtreType != "TOUT") {
                                $query .= ' AND Desc_Type_Produit= :filtreType';
                            }
                            if ($rechercheNom != "") {
                                $query .= ' AND Nom_Produit LIKE CONCAT("%", :rechercheNom, "%")';
                            }

                            // Tri des résultats
                            if ($tri == "PrixAsc") {
                                $query .= ' ORDER BY Prix_Produit_Unitaire ASC';
                            } elseif ($tri == "PrixDesc") {
                                $query .= ' ORDER BY Prix_Produit_Unitaire DESC';
                            } elseif ($tri == "Alpha") {
                                $query .= ' ORDER BY Nom_Produit ASC';
                            } elseif ($tri == "AntiAlpha") {
                                $query .= ' ORDER BY Nom_Produit DESC';
                            }

                            $queryGetProducts = $bdd->prepare($query);
                            $queryGetProducts->bindParam(":Id_Prod", $Id_Prod, PDO::PARAM_STR);
                            if ($filtreType != "TOUT") {
                                $queryGetProducts->bindParam(":filtreType", $filtreType, PDO::PARAM_STR);
                            }
                            if ($rechercheNom != "") {
                                $queryGetProducts->bindParam(":rechercheNom", $rechercheNom, PDO::PARAM_STR);
                            }

                            $queryGetProducts->execute();
                            $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

                            if (count($returnQueryGetProducts) == 0) {
                                echo $htmlAucunProduitEnStock;
                            } else {
                                foreach ($returnQueryGetProducts as $produit) {
                                    $Id_Produit = $produit["Id_Produit"];
                                    $nomProduit = $produit["Nom_Produit"];
                                    $typeProduit = $produit["Desc_Type_Produit"];
                                    $prixProduit = $produit["Prix_Produit_Unitaire"];
                                    $QteProduit = $produit["Qte_Produit"];
                                    $unitePrixProduit = $produit["Nom_Unite_Prix"];

                                    // Définition des attributs step et min en fonction de l'unité
                                    $step = ($unitePrixProduit == "Kg" || $unitePrixProduit == "L" || $unitePrixProduit == "m²") ? "0.01" : "1";
                                    $min = ($unitePrixProduit == "Kg" || $unitePrixProduit == "L" || $unitePrixProduit == "m²") ? "0.01" : "1";

                                    echo '<div class="squareProduct">';
                                    echo '<img class="img-produit" src="img_produit/' . $Id_Produit . '.png" alt="' . $htmlImageNonFournie . '" ><br>';
                                    echo '<div class="product-info"><span>' . $htmlProduitDeuxPoints . '</span><span class="value">' . $nomProduit . '</span></div>';
                                    echo '<div class="product-info"><span>' . $htmlTypeDeuxPoints . '</span><span class="value">' . $typeProduit . '</span></div>';
                                    echo '<div class="product-info"><span>' . $htmlPrix . '</span><span class="value">' . $prixProduit . ' €/' . $unitePrixProduit . '</span></div>';

                                    if (isset($_SESSION["Id_Uti"])) {
                                        echo '<div class="quantity-container">';
                                        echo '<input type="number" class="quantity-input" name="quantite_' . $Id_Produit . '" 
                                                    placeholder="max ' . $QteProduit . '" 
                                                    max="' . $QteProduit . '" 
                                                    step="' . $step . '" 
                                                    data-unit="' . $unitePrixProduit . '">';
                                        echo '<span class="unit"> ' . $unitePrixProduit . '</span>';
                                        echo '</div>';
                                    }

                                    echo '</div>';
                                }
                            }
                            ?>
                            <?php if (sizeof($returnQueryGetProducts) > 0) { ?>
                                <br>
                                <div class="commande-container">
                                    <?php if (isset($_SESSION["Id_Uti"])) { ?>
                                        <button type="submit" class="commande-btn"><?php echo $htmlPasserCommande; ?></button>
                                    <?php } else { ?>
                                        <a href="login.php" class="commande-btn login-btn"><?php echo $htmlSeConnecter; ?></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="producteur">
                        <div class="info-container">
                            <div class="img-prod">
                                <img src="img_producteur/<?php echo $Id_Prod; ?>.png" alt="<?php echo $htmlImgProducteur; ?>" style="width: 99%; border-radius: 20px;">
                            </div>
                            <div class="text-info">
                                <?php
                                echo '</br>' . $prenom . ' ' . strtoupper($nom) . '</br></br><strong>' . $profession . '</strong></br></br>' . $address . '</br></br>';
                                ?>
                            </div>
                        </div>


                        <?php
                        if (isset($_SESSION["Id_Uti"])  && $idUti != $_SESSION["Id_Uti"]) {
                        ?>
                            <input class="filtre-container-producteur" type="button" onclick="window.location.href='messagerie.php?Id_Interlocuteur=<?php echo $idUti; ?>'" value="<?php echo $htmlEnvoyerMessage; ?>">
                            <br>
                        <?php
                        } ?>


                        <?php
                        if (isset($address)) {
                            $address = str_replace(" ", "+", $address);
                        ?>
                            <iframe class="map-frame" src="https://maps.google.com/maps?&q=<?php echo $address; ?>&output=embed "
                                width="100%" height="100%" style="border-radius: 10px; border: 3px solid #EBF4EC; outline: none;"></iframe>
                        <?php } ?>
            </form>
        </div>
    </div>


    <?php include "footer.php"; ?>
  
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("form").forEach(function (form) {
                form.addEventListener("keypress", function (event) {
                    if (event.key === "Enter" && event.target.tagName !== "TEXTAREA") {
                        event.preventDefault();
                    }
                });
            });
        });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll('.quantity-input');
    const submitButton = document.querySelector('.commande-container button');

    function checkQuantities() {
        let hasQuantity = false;

        inputs.forEach(input => {
            let quantity = parseFloat(input.value);
            if (!isNaN(quantity) && quantity > 0) {
                hasQuantity = true;
            }
        });

        submitButton.disabled = !hasQuantity;
        if (hasQuantity) {
            submitButton.classList.remove("disabled-btn");
        } else {
            submitButton.classList.add("disabled-btn");
        }
    }

    checkQuantities(); // Vérifier au chargement de la page
    inputs.forEach(input => {
        input.addEventListener("input", checkQuantities);
    });
});

</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll('.quantity-input');

    function updateInputType() {
        inputs.forEach(input => {
            let unit = input.dataset.unit;
            if (unit === "Kg" || unit === "L" || unit === "m²") {
                input.setAttribute("step", "0.01");
                input.setAttribute("min", "0.01");
            } else {
                input.setAttribute("step", "1");
                input.setAttribute("min", "1");
            }
        });
    }
    updateInputType();

    inputs.forEach(input => {
        input.addEventListener("change", updateInputType);
    });
});
</script>

</body>