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
if (!Current_User::allow('sysinventory') {
    return;
}

switch ($_REQUEST['action']) {
    case 'report':
        PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Report.php');
        Sysinventory_Report::doReport();
        }
        break;
    default:
        //display default page
        break;
}

/*if (isset($_REQUEST['aop'])) {
    $skeleton->adminMenu();
} elseif (isset($_REQUEST['uop'])) {
    $skeleton->userMenu();
} elseif (isset($_REQUEST['id']) && isset($_REQUEST['bone_id'])) {
    $skeleton->userMenu('view_bone');
} elseif (isset($_REQUEST['id'])) {
    $skeleton->userMenu('view_skeleton');
} elseif (isset($_REQUEST['skeleton']) && isset($_REQUEST['bone'])) {
    $skeleton->userMenu('view_bone');
} elseif (isset($_REQUEST['skeleton'])) {
    $skeleton->userMenu('view_skeleton');
} else {
    PHPWS_Core::home();
}*/


?>
