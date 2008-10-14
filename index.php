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

$error = NULL;

if(!isset($_REQUEST['action'])){
    $req = "";
}else{
    $req = $_REQUEST['action'];
}

switch ($req) {
    case 'edit_system':
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_SystemUI.php');
        Sysinventory_SystemUI::showEditSystem();
        break;
    //this one is for the AJAX delete on the report page, so it doesn't call a new UI class
    case 'delete_system':
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        echo Sysinventory_System::deleteSystem($_REQUEST['systemid']);
        exit;
    case 'report':
        PHPWS_Core::initModClass('sysinventory', 'UI/Sysinventory_ReportUI.php');
        Sysinventory_ReportUI::display();
        break;
    case 'build_query':
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_QueryUI.php');
        Sysinventory_QueryUI::showQueryBuilder();
        break;
    case 'edit_locations':
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_LocationUI.php');
        Sysinventory_LocationUI::showLocations();
        break;
    case 'edit_departments':
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        if (isset($_REQUEST['addDep']) && !empty($_REQUEST['description'])) {
            $todo = 'addDep';
            $thing = $_REQUEST['description'];
        }else if (isset($_REQUEST['delDep'])) {
            $todo = 'delDep';
            $thing = $_REQUEST['id'];
        }else{
            $todo = NULL;
            $thing = NULL;
        }
        Sysinventory_Department::showDepartments($todo,$thing);
        break;
    case 'addDep':
        Sysinventory_Department::AddDepartment($_REQUEST['description']);
        break;
    case 'edit_admins':
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_AdminUI.php');
        Sysinventory_AdminUI::showAdmins();
        break;
    default:
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
        Sysinventory_Menu::showMenu($error);
        break;
}

?>
