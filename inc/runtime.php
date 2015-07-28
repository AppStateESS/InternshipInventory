<?php

/***
 * Commenting this out for now.. Need to test more extensively when we can get r6test updated to latest phpws
if(!Current_User::isLogged() && isset($_SERVER['HTTP_SHIB_EP_PRINCIPALNAME'])) {
    NQ::simple('intern', INTERN_ERROR, "You have successfully signed in, but we have not setup your Internship Inventory account. Please contact the Career Development Center at 828-262-2180.");
    NQ::close();
}
*/

if (PHPWS_Core::atHome() && Current_User::isLogged()) {
    $path = $_SERVER['SCRIPT_NAME'].'?module=intern';

    header('HTTP/1.1 303 See Other');
    header("Location: $path");
    exit();
}
