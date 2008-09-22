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
    default:
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
        Sysinventory_Menu::showMenu();
        break;
}

?>
