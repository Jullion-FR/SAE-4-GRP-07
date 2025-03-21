<?php
require_once "./language.php" ; 

error_reporting(E_ALL);
ini_set("display_errors", 1);

if(!isset($_SESSION)){
    session_start();
}

function afficheMessages($id_user, $id_other_people){
    include __DIR__ . "/../loadenv.php";
    $messages = $db->select('CALL conversation(?, ?);', 'ii', [$id_user, $id_other_people]);
    foreach($messages as $message){
        afficheMessage($message);
    }
}

function afficheMessage($message){
    $contenu = $message['Contenu_Msg'];
    $date = $message['Date_Msg'];
    if ($message['Emetteur']==$_SESSION['Id_Uti']){
        echo('<div><div class="messageEnvoye message"><a>'.$contenu.'</a></div></div>');
    }else {
        echo('<div><div class="messageRecu message"><a>'.$contenu.'</a></div></div>');
    }
    
}


if (isset($_SESSION['Id_Uti'])){
    if (isset($_GET['Id_Interlocuteur'])){
        afficheMessages($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur']);
        $formDisabled=false;
    }else {
        echo('<div style="text-align: center; font-size: 28px; margin-top: 20%;">'.$htmlSelectConversation.'</div>');
        $formDisabled=true;
    }
}else{
    echo($htmlPasAccesPageContactAdmin);
    $formDisabled=true;
}
?>