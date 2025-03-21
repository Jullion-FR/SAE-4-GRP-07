<?php
function tryLogin(string $email, string $password, string $redirect){
    include __DIR__ . "/../language.php";
    include __DIR__ . "/../loadenv.php";

    // Vérification de l'existence de l'utilisateur
    $user = $db->select("SELECT Id_Uti FROM UTILISATEUR WHERE Mail_Uti = ?", "s", [$email]);
    if (!$user) {
        $_SESSION['erreur'] = $htmlAdresseMailInvalide;
        header('Location: ../login.php');
        exit();
    }
    $idUti = $user[0]['Id_Uti'];


    // Vérification du mot de passe via une procédure stockée
    $result = $db->select("CALL verifMotDePasse(?, ?)", "is", [$idUti, $password]);
    if (!empty($result) && (isset($result[0]["1"]) && $result[0]["1"] == 1 || isset($result[0]["0"]) && $result[0]["0"] == 1)) {

        // Bon mot de passe
        $_SESSION['Mail_Uti'] = $email;
        $_SESSION['Id_Uti'] = $idUti;
        
        // Vérification du statut d'administrateur
        $isAdmin = $db->select("SELECT COUNT(*) as count FROM ADMINISTRATEUR WHERE Id_Uti = ?", "i", [$idUti]);
        $_SESSION["isAdmin"] = $isAdmin[0]['count'] > 0;
        $_SESSION['erreur'] = '';
        
        // Vérification du statut de producteur
        $isProd = $db->select("SELECT COUNT(*) FROM PRODUCTEUR WHERE PRODUCTEUR.Id_Uti = ?;", "i", [$idUti]);
        $_SESSION["isProd"] = $isProd[0]['COUNT(*)'] > 0;
        
        $redirect = $_POST['redirect'];
        if (empty($redirect)) {
            $redirect = "../index.php";
        }
    
        header('Location: ' . $redirect);
        exit;

    } else {

        // Mauvais mot de passe
        $_SESSION['test_pwd']--;
        $_SESSION['erreur'] = $htmlMauvaisMdp . $_SESSION['test_pwd'] . $htmlTentatives;
        header('Location: ../login.php');
        exit();

    }

}