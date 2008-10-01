<?php
/**
 * Class for adding or editing a system
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_SystemUI {

    function showAddSystem() {
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Query.php');
        $dpts = array();
        $dpts = Sysinventory_Query::getDepartmentsByUsername();
        if(empty($dpts)) {
            $error = "You are not the administrator of any departments.";
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($error);
            return;
        }
        $form = new PHPWS_Form('add_system');
        // Add form elements here for adding a new system.
    }
}
