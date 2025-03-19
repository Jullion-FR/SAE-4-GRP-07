<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['language'])) {
    $_SESSION["language"] = $_POST['language'];
    header("Location: index.php");
}
if (isset($_SESSION["language"])) {
    switch ($_SESSION["language"]) {
        case 'fr':
            require __DIR__ . "/languages/language_fr.php";
            break;

        case 'en':
            require __DIR__ . "/languages/language_en.php";
            break;

        case 'es':
            require __DIR__ . "/languages/language_es.php";
            break;

        case 'al':
            require __DIR__ . "/languages/language_al.php";
            break;

        case 'ru':
            require __DIR__ . "/languages/language_ru.php";
            break;
        case 'ch':
            require __DIR__ . "/languages/language_ch.php";
            break;

        default:
            require __DIR__ . "/languages/language_fr.php";
            break;
    }
} else {
    require __DIR__ . "/languages/language_fr.php";
}
