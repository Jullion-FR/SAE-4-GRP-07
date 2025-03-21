<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

function envoyerMessage($id_user, $id_other_people, $content){
    include __DIR__ . "/../loadenv.php";

    $query = $db->query('CALL envoyerMessage(?, ?, ?)',"iis",[$id_user, $id_other_people, htmlspecialchars($content)]);
}


if (isset($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], $_POST['content'])){
    if ($_POST['content']!=""){
        envoyerMessage($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], htmlspecialchars($_POST['content']));
    }
    unset($_POST['content']);
    
    //vérifier si les en-têtes peuvvent être modifiés
    if(!headers_sent()){
        header('Location: messagerie.php?Id_Interlocuteur='.$_GET['Id_Interlocuteur']);
        exit;
    }else{
        echo('<script>window.location.href = "messagerie.php?Id_Interlocuteur='.$_GET['Id_Interlocuteur'].'";</script>');
        exit;
    }
}
    
?>