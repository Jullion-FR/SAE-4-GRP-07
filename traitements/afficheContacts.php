<?php
include_once __DIR__ . "/../loadenv.php";
require "./language.php";

// Démarrer la session si elle n'est pas déjà active
if (!isset($_SESSION)) {
    session_start();
}

// Vérification de l'utilisateur connecté
if (!isset($_SESSION['Id_Uti'])) {
    echo "<p>Veuillez vous connecter pour voir vos contacts.</p>";
    exit;
}

$utilisateur = $_SESSION['Id_Uti'];

// Récupérer la liste des contacts récents via la procédure stockée
$contacts = $db->select('CALL listeContact(?);', 'i', [$utilisateur]);
?>

<div class="search-container">
    <input type="text" id="searchContacts" onkeyup="filterContacts()" placeholder="Rechercher un contact...">
</div>

<div id="contactsList">
    <?php
    if (count($contacts) == 0) {
        echo "<p>Aucun contact récent.</p>";
    } else {
        foreach ($contacts as $contact) {
            echo '<form method="get" action="messagerie.php" class="contact-item">';
            echo '<input type="hidden" name="Id_Interlocuteur" value="' . ($contact['Id_Uti']) . '">';
            echo '<button type="submit" class="contact-name">' . ($contact['Nom_Uti']) . ' ' . ($contact['Prenom_Uti']) . '</button>';
            echo '</form>';
        }
    }
    ?>
</div>

<script>
    function filterContacts() {
        let input = document.getElementById("searchContacts").value.toLowerCase();
        let contacts = document.querySelectorAll(".contact-item");

        contacts.forEach(contact => {
            let name = contact.querySelector(".contact-name").innerText.toLowerCase();
            if (name.includes(input)) {
                contact.style.display = "block";
            } else {
                contact.style.display = "none";
            }
        });
    }
</script>

