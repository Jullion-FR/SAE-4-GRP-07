<?php
if (isset($_POST['popup'])){
    switch ($_POST['popup']) {
        case '':
            unset($_POST['popup']);
            unset($_SESSION['tempIsProd']);
            unset($_SESSION['tempIsAdmin']);
            break;

        case 'sign_up_client':
            $_SESSION['tempIsProd'] = false;
            $_POST['popup'] = 'sign_up';
            require $_POST['popup'].".php";
            break;

        case 'sign_up_prod':
            $_SESSION['tempIsProd'] = true;
            $_POST['popup'] = 'sign_up';
            require $_POST['popup'].".php";
            break;

        case 'sign_in':
            if(isset($_SESSION['Mail_Uti'])){
                $_POST['popup'] = 'info_perso';
            }else{
            $_SESSION['tempIsAdmin'] = false;
            }
            require $_POST['popup'].".php";
            break;

        case 'sign_in_admin':
            $_SESSION['tempIsAdmin'] = true;
            $_POST['popup'] = 'sign_in';
            require $_POST['popup'].".php";
            break;
        
        default:
            require $_POST['popup'].".php";
            break;
    }
    if (isset($_POST['formClicked'])){
        unset($_POST['formClicked']);
        $_SESSION['tempPost'] = $_POST;
        @header('refresh:0');
       
    }
}
?>