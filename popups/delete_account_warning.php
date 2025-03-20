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
        z-index: 9999;
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

<div class="popup-overlay" id="popup">
    <div class="popup-container">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <h2>Supprimer le compte</h2>
        <div class="popup-content">
            <div class="popup-left">
                <img id="popup-image" class="popup-pp" src="img/default_producteur.png" alt="Profile Picture">
                <div>
                    <p id="popup-nom"><strong>Nom Prénom</strong></p>
                    <p id="popup-profession"></p>
                    <p id="popup-adresse"></p>
                </div>
            </div>
            <div class="popup-right">
                <label for="motif">Motif :</label>
                <textarea id="motif" class="popup-textarea" placeholder="Entrez votre motif"></textarea>
            </div>
        </div>
        <form action="/traitements/del_acc.php" method="post">
            <input type="hidden" name="targetID" id="popup-targetID">
            <button type="submit" class="popup-button">
                <?php echo "$htmlConfirmer $htmlSupprimerCompte" ?>
            </button>
        </form>
    </div>
</div>


<script>
    function openPopup(id, prenom, nom, adresse, profession, imageSrc) {
        document.getElementById("popup").style.visibility = "visible";
        
        // Met à jour les champs avec les données de l'utilisateur
        document.getElementById("popup-nom").innerHTML = `<strong>${prenom} ${nom}</strong>`;
        document.getElementById("popup-adresse").innerText = adresse;
        document.getElementById("popup-profession").innerText = profession || "";
        document.getElementById("popup-image").src = imageSrc;

        // Met à jour l'ID utilisateur dans le formulaire
        document.getElementById("popup-targetID").value = id;
    }
    function closePopup() {
        document.getElementById("popup").style.visibility = "hidden";
    }
</script>