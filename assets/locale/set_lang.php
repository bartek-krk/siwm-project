<?php
    require_once("./../config_provider.php");

    $getLang = $_GET["lang"];
    $lang = '';

    switch($getLang) {
        case 'en':
            $lang = 'en';
            break;
        case 'pl':
            $lang = 'pl';
            break;
        default:
            $lang = 'en';
            break;
    }

    $langCookieName = $configuration->getProperty("cookie.language.name", "homefirstaidkit_chosenapplicationlanguage");
    setcookie($langCookieName, $lang, time() + (86400*30), "/");

    $destination = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../../index.php";
    header('Location: '.$destination);
    
?>