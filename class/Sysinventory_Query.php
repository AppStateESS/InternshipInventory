<?php
/**
 * class for building queries, running them and displaying the result
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

class Sysinventory_Query {

    function showQueryBuilder() {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_Query.php');
        $disp = new Sysinventory_QueryUI;
        $disp->departments = getDepartmentsByUsername();
        
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }

}
?>
