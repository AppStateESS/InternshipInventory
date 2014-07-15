<?php

namespace Intern;

/**
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */
// Make sure the source directory is defined
if (!defined('PHPWS_SOURCE_DIR')) {
    include '../../config/core/404.html';
    exit();
}

require_once(PHPWS_SOURCE_DIR . 'mod/intern/inc/defines.php');

// Check some permissions
if (!\Current_User::isLogged()) {
    // Fix by replacing the Users module
    \PHPWS_Core::reroute('../secure');
}

// This is wrong, but it'll have to do for now.
// TODO: some sort of command pattern
$content = null;
if(DEBUG){
    $inventory = new InternshipInventory();
    $inventory->handleRequest();
    $content = $inventory->getContent();
}else{
    try{
        $inventory = new InternshipInventory();
        $inventory->handleRequest();
        $content = $inventory->getContent();
    }catch(Exception $e){
        try{
            NQ::simple('intern', INTERN_ERROR, 'The Intern Inventory has experienced an error. The software engineers have been notified about this problem. We apologize for the inconvenience.');

            $message = formatException($e);
            emailError($message);
            
            NQ::close();
            Intern_NotifyUI::display();

            \PHPWS_Core::goBack();
        }catch(Exception $e){
            $message2 = formatException($e);
            echo "The Intern Inventory has experienced a major internal error.  Attempting to email an admin and then exit.";
            $message = "Something terrible has happened, and the exception catch-all threw an exception.\n\nThe first exception was:\n\n$message\n\nThe second exception was:\n\n$message2";
            mail('webmaster@tux.appstate.edu', 'A Major Intern Inventory Error Has Occurred', $message);
            exit();
        }
    }
}

/**
 * Plug content into TopUI. Show notifications. Add Style.
 */
if (isset($content)) {
    if ($content === false) {
        NQ::close();
        \PHPWS_Core::reroute('index.php?module=intern');
    }
}

// Add top menu bar to theme
UI\TopUI::plug();


// Get Notifications, add to layout
$nv = new UI\NotifyUI();
$notifications = $nv->display();
\Layout::add($notifications);


// Add content to Layout
\Layout::addStyle('intern', 'style.css');
\Layout::addStyle('intern', 'tango-icons.css');
\Layout::add($content);

function formatException(Exception $e)
{
    ob_start();
    echo "Ohes Noes!  Intern Inventory threw an exception that was not caught!\n\n";
    echo "Host: {$_SERVER['SERVER_NAME']}({$_SERVER['SERVER_ADDR']})\n";
    echo 'Request time: ' . date("D M j G:i:s T Y", $_SERVER['REQUEST_TIME']) . "\n";
    if(isset($_SERVER['HTTP_REFERER'])){
        echo "Referrer: {$_SERVER['HTTP_REFERER']}\n";
    }else{
        echo "Referrer: (none)\n";
    }
    echo "Remote addr: {$_SERVER['REMOTE_ADDR']}\n\n";

    $user = \Current_User::getUserObj();
    if(isset($user) && !is_null($user)){
        echo "User name: {$user->getUsername()}\n\n";
    }else{
        echo "User name: (none)\n\n";
    }

    echo "Here is the exception:\n\n";
    print_r($e);

    echo "\n\nHere is $_REQUEST:\n\n";
    print_r($_REQUEST);

    echo "\n\nHere is CurrentUser:\n\n";
    print_r(\Current_User::getUserObj());

    $message = ob_get_contents();
    ob_end_clean();

    return $message;
}

function emailError($message)
{
    $to = array('jb67803@appstate.edu', 'ticklejw@appstate.edu');

    $tags = array('MESSAGE' => $message);
    Email::sendTemplateMessage($to, 'Uncaught Exception', 'email/UncaughtException.tpl', $tags);
}
?>
