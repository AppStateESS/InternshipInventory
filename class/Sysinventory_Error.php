<?php
/**
 * Error Handling - displays a menu with a specified error message.
 **/

class Sysinventory_Error {
    function error($error='$nbsp;') {
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
        Sysinventory_Menu::showMenu($error);
    }

    function browser_fail() {
        $tags['ERROR_MSG'] = 'System Inventory is not compatible with your browser.  Please use Mozilla Firefox or another secure browser to access the database.';
        Layout::addStyle('sysinventory','style.css');
        Layout::add(PHPWS_Template::process($tags,'sysinventory','menu.tpl'));
    }
}
?>
