<?php
/**
 * class for building queries, running them and displaying the result
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

class Sysinventory_Query {

    function showQueryBuilder() {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_QueryUI.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        $disp = new Sysinventory_QueryUI;
        $disp->departments = Sysinventory_Department::getDepartmentsByUsername();
        
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }

}
?>
