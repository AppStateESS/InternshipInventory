<?php
    /**
    * @author Micah Carter <mcarter at tux dot appstate dot edu>
    */

// Make sure the source directory is defined
if (!defined('PHPWS_SOURCE_DIR')) {
    include '../../config/core/404.html';
    exit();
}

// Check some permissions
if (!Current_User::allow('sysinventory')) {
    return;
}

switch (isset($_REQUEST['action']) ? $_REQUEST['action'] : 42) {
    case 'report':
        PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Report.php');
        Sysinventory_Report::doReport();
        break;
    case 'build_query':
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Query.php');
        Sysinventory_Query::showQueryBuilder();
        break;
    case 'edit_locations':
        PHPWS_Core::initModClass('sysinventory','Sysinventory_EditLocations.php');
        Sysinventory_EditLocations::showLocations();
        break;
    case 'edit_offices':
        PHPWS_Core::initModClass('sysinventory','Sysinventory_EditOffices.php');
        Sysinventory_EditOffices::showOffices();
        break;
    case 'edit_departments':
        PHPWS_Core::initModClass('sysinventory','Sysinventory_EditDepartments.php');
        Sysinventory_EditDepartments::showDepartments();
        break;
    case 'edit_admins':
        PHPWS_Core::initModClass('sysinventory','Sysinventory_EditAdmins.php');
        Sysinventory_EditAdmins::showAdmins();
        break;
    default:
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
        Sysinventory_Menu::showMenu();
        break;
}

?>
