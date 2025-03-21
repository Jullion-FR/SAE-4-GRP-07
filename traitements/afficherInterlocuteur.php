<?php
if (isset($_GET['Id_Interlocuteur'])){

    $interlocuteur = $db->select("SELECT Nom_Uti, Prenom_Uti FROM UTILISATEUR WHERE Id_Uti= ?", "i", [$_GET['Id_Interlocuteur']]);

    $is = $db->select('CALL isProducteur(?);', 'i', [$_GET['Id_Interlocuteur']]);

    echo ($interlocuteur[0]['Nom_Uti'].' '.$interlocuteur[0]['Prenom_Uti']. $is[0]['result']);

}

?>