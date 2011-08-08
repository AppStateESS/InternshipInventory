<?php

if (PHPWS_Core::atHome() && Current_User::isLogged()) {
    $path = $_SERVER['SCRIPT_NAME'].'?module=intern';

    header('HTTP/1.1 303 See Other');
    header("Location: $path");
    exit();
}

?>
