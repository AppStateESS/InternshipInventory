<?php
/**
 * Class for building the report page
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_ReportUI {
    function display() {

        // Check Permissions
        if(!isset($_REQUEST['showreport']) || !Current_User::allow('sysinventory','admin')) {
           $error = 'You must be an administrator of at least one department and use the query builder to create a report.';
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($error);
            return;
        }

        // Get stuff from the pager
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Report.php');
        $report = Sysinventory_Report::generateReport();

        Layout::addStyle('sysinventory','style.css');
        Layout::add($report);
    }

}
?>
