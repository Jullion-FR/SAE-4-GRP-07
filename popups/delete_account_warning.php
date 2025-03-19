<style>
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        visibility: hidden;
    }
    .popup-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 800px;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .popup-container h2{
        text-align:center
    }
    .popup-close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 18px;
    }
    .popup-content {
        display: flex;
        flex-direction: row;
        gap: 20px;
    }
    .popup-right, .popup-left{
        background: #eef7eb;
        padding: 10px;
        border-radius: 5px;
    }
    .popup-right {
        width: 45%;
    }
    .popup-left{
        width: 55%;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .popup-textarea {
        width: 100%;
        height: 50%;
        border: 1px solid #ccc;
        border-radius: 5px;
 
        resize: none;
    }
    .popup-button {
        background: green;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
    }
    .popup-pp{
        width: 50%;
        border-radius: 5px;
        object-fit: cover;
    }
</style>

<?php
$user = $db->select('SELECT Id_Uti, Prenom_Uti, Nom_Uti, Mail_Uti, Adr_Uti FROM UTILISATEUR WHERE Mail_Uti = ?', "s",[$_SESSION['Mail_Uti']])[0];
$producteurInfo = $db->select('SELECT Id_Prod, Id_Uti, Prof_Prod FROM PRODUCTEUR where Id_Uti = ?', 's', [$user['Id_Uti']])[0];
?>

<div class="popup-overlay" id="popup">
    <div class="popup-container">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <h2>Supprimer le compte</h2>
        <div class="popup-content">
            <div class="popup-left">
                <?php if (!empty($producteurInfo)):?>
                    <img class="popup-pp" src="<?php echo "img_producteur/".$producteurInfo['Id_Prod'].".png"?>" alt="<?php echo $producteurInfo['Id_Prod']?>">
                <?php else:?>
                    <img class="popup-pp" src="img_producteur/default.png" alt="Profile Picture">
                <?php endif ?>
                <div>
                    <?php
                        echo "<strong>" . $user['Prenom_Uti'] . " " . $user['Nom_Uti'] . "</strong><br>";
                        if (!empty($producteurInfo)) echo $producteurInfo['Prof_Prod'] . "<br>";
                        echo "<br>" . $user["Adr_Uti"];
                    ?>
                </div>
            </div>
            <div class="popup-right">
                <label for="motif">Motif :</label>
                <textarea id="motif" class="popup-textarea" placeholder="Entrez votre motif"></textarea>
            </div>
        </div>
        <a href="/traitements/del_acc.php">
            <button class="popup-button"><?php echo "$htmlConfirmer $htmlSupprimerCompte"?></button>
        </a>
    </div>
</div>

<script>
    function openPopup() {
        document.getElementById("popup").style.visibility = "visible";
    }
    function closePopup() {
        document.getElementById("popup").style.visibility = "hidden";
    }
</script>
