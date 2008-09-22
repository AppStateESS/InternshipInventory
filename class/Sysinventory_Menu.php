<?php
/**
 * handle the menu options based on who is logged in
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Sysinventory_Menu {

    function showMenu() {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_MenuUI.php');
        $disp = &new Sysinventory_MenuUI;
        Layout::add($disp->display());
    }
}
?>
