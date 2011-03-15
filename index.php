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
if (!Current_User::allow('intern')) {
    return;
}

$error =  NULL;

// firefox/opera/safari/whatever is preferred, but ie7 is okay.  ie<6 cannot use this module. deal with it.
$browser = $_SERVER['HTTP_USER_AGENT'];
$ie7 = "/MSIE [789]/";
$ie_fail = "/MSIE/";

if (preg_match($ie7,$browser)) {
    $error = 'It is STRONGLY reccommended that you use Mozilla Firefox to access the System Inventory';
}else if (preg_match($ie_fail,$browser)) {
    PHPWS_Core::initModClass('intern','Sysinventory_Error.php');
    Sysinventory_Error::browser_fail();
    return;
}

if(!isset($_REQUEST['action'])){
    $req = "";
}else{
    $req = $_REQUEST['action'];
}


// Show notifications, except when a redirect has occurred.
PHPWS_Core::initModClass('intern', 'Sysinventory_Util.php');
PHPWS_Core::initModClass('intern', 'UI/Sysinventory_NotifyUI.php');
Sysinventory_NotifyUI::display();

// Show requested page.
switch ($req) {
    case 'edit_system':
        PHPWS_Core::initModClass('intern','UI/Sysinventory_SystemUI.php');
        if(isset($_REQUEST['newsystem'])) {
            $sysid = 0;
            if (isset($_REQUEST['id'])) {
                $sysid    = $_REQUEST['id'];
                $whatWeDo = 'Edit System';
                }
            PHPWS_Core::initModClass('intern','Sysinventory_System.php');
            PHPWS_Core::initModClass('intern','UI/Sysinventory_ReportUI.php');
            Sysinventory_System::addSystem($sysid);
        }
        Sysinventory_SystemUI::showEditSystem();
        break;
    case 'delete_system':
        PHPWS_Core::initModClass('intern','Sysinventory_System.php');
        echo Sysinventory_System::deleteSystem($_REQUEST['systemid']);
        exit;
    case 'report':
        PHPWS_Core::initModClass('intern', 'UI/Sysinventory_ReportUI.php');
        Sysinventory_ReportUI::display();
        break;
    case 'build_query':
        PHPWS_Core::initModClass('intern','UI/Sysinventory_QueryUI.php');
        Sysinventory_QueryUI::showQueryBuilder();
        break;
    case 'edit_locations':
        if (isset($_REQUEST['newloc']) && !empty($_REQUEST['description'])) {
            PHPWS_Core::initModClass('intern','Sysinventory_Location.php');
            Sysinventory_Location::newLoc($_REQUEST['description']);
        }else if (isset($_REQUEST['delloc']) && !empty($_REQUEST['id'])) {
            PHPWS_Core::initModClass('intern','Sysinventory_Location.php');
            Sysinventory_Location::delLoc($_REQUEST['id']);
        }
        PHPWS_Core::initModClass('intern','UI/Sysinventory_LocationUI.php');
        Sysinventory_LocationUI::showLocations();
        break;
    case 'edit_departments':
        PHPWS_Core::initModClass('intern','Sysinventory_Department.php');
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
        if (isset($_REQUEST['newadmin'])) {
            PHPWS_Core::initModClass('intern','Sysinventory_Admin.php');
            Sysinventory_Admin::newAdmin($_REQUEST['department_id'],$_REQUEST['username']);
        }else if (isset($_REQUEST['deladmin'])) {
            PHPWS_Core::initModClass('intern','Sysinventory_Admin.php');
            Sysinventory_Admin::delAdmin($_REQUEST['id']);
        }
        PHPWS_Core::initModClass('intern','UI/Sysinventory_AdminUI.php');
        Sysinventory_AdminUI::showAdmins();
        break;
    case 'edit_default':
        if (isset($_REQUEST['newdefault']) && isset($_REQUEST['name']) && isset($_REQUEST['model'])) {
            PHPWS_Core::initModClass('intern','Sysinventory_Default.php');
            Sysinventory_Default::newDefault();
        }else if (isset($_REQUEST['deldefault']) && isset($_REQUEST['id'])) {
            PHPWS_Core::initModClass('intern','Sysinventory_Default.php');
            Sysinventory_Default::delDefault($_REQUEST['id']);
        }
        PHPWS_Core::initModClass('intern','UI/Sysinventory_DefaultUI.php');
        Sysinventory_DefaultUI::showDefaults();
        break;
    case 'pdf':
        $filename = $_SESSION['filename'];
        $path = '/tmp/';
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        readfile($path.$filename);
        exit;
    case 'upload_document_form':
        PHPWS_Core::initModClass('intern', 'Sysinventory_Document_Manager.php');
        $docManager = new Sysinventory_Document_Manager();
        $docManager->edit();
        break;
    case 'post_document_upload':
        PHPWS_Core::initModClass('intern', 'Sysinventory_Document_Manager.php');
        $docManager = new Sysinventory_Document_Manager();
        $docManager->postDocumentUpload();
        break;
    case 'delete_document':
        PHPWS_Core::initModClass('intern', 'Sysinventory_Document.php');
        $doc = new Sysinventory_Document($_REQUEST['doc_id']);
        $doc->delete();
        NQ::simple('intern', SYSI_SUCCESS, 'Document deleted.');
        Sysinventory_Util::reroute('index.php?module=sysinventory&action=report&redir=1');
        break;
    default:
        PHPWS_Core::initModClass('intern','Sysinventory_Menu.php');
        Sysinventory_Menu::showMenu($error);
        break;
}

?>
