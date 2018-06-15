<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
    }catch(\Exception $e){

        $user = \Current_User::getUserObj();
        $e->username = $user->getUsername();

        if(isset($_SERVER['HTTP_REFERER'])){
            $e->referrer = $_SERVER['HTTP_REFERER'];
        }else{
            $e->referrer = 'None';
        }

        $e->remoteAddr = $_SERVER['REMOTE_ADDR'];

        if (extension_loaded('newrelic')) { // Ensure PHP agent is available
            newrelic_notice_error($e->getMessage(), $e);
        }

        try{
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'The Intern Inventory has experienced an error. The software engineers have been notified about this problem. We apologize for the inconvenience.');

            $email = new \Intern\Email\ExceptionEmail(\Intern\InternSettings::getInstance(), $e);
            $email->send();

            \NQ::close();

            $notifyUI = new \Intern\UI\NotifyUI();
            $notifyUI->display();

            \PHPWS_Core::goBack();
        }catch(\Exception $e){
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
        \NQ::close();
        \PHPWS_Core::reroute('index.php?module=intern');
    }
}

// Add top menu bar to theme
\PHPWS_Core::initModClass('intern', 'UI/TopUI.php');
UI\TopUI::plug();


// Get Notifications, add to layout
$nv = new UI\NotifyUI();
$notifications = $nv->display();
\Layout::add($notifications);


// Add content to Layout
\Layout::addStyle('intern', 'style.css');
\Layout::add($content);
