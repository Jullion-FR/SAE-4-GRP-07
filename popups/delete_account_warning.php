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
            width: 400px;
            text-align: center;
            position: relative;
        }
        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .popup-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .popup-profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }
        .popup-textarea {
            width: 100%;
            height: 60px;
            margin-top: 10px;
        }
        .popup-button {
            background: green;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
</style>

<div class="popup-overlay" id="popup">
    <div class="popup-container">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <h2>Supprimer le compte</h2>
        <div class="popup-profile">
            <img src="https://via.placeholder.com/60" alt="Profile Picture">
            <div>
                <strong>Calliope STICIDE</strong><br>
                agricultrice<br>
                2, rue du Champ d’or<br>
                53000 LAVAL
            </div>
        </div>
        <label>Motif :</label>
        <textarea class="popup-textarea" placeholder="Entrez votre motif"></textarea>
        <br>
        <a href="traitements/del_acc.php"><button class="popup-button"><?php echo "$htmlConfirmer $htmlSupprimerCompte"?></button></a>
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