<?php
/**
 * Error Handling - displays a menu with a specified error message.
 **/

class Sysinventory_Error {
    function error($error='$nbsp;') {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_MenuUI.php');
        $menu = new Sysinventory_MenuUI($error);
        $menu->display();
        exit;
    }
}
?>
