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
if(!Current_User::isLogged()){
    PHPWS_Core::reroute('/admin');
 }

$error =  NULL;

// firefox/opera/safari/whatever is preferred, but ie7 is okay.  ie<6 cannot use this module. deal with it.
$browser = $_SERVER['HTTP_USER_AGENT'];
$ie7 = "/MSIE [789]/";
$ie_fail = "/MSIE/";

PHPWS_Core::initModClass('intern', 'UI/Intern_NotifyUI.php');
PHPWS_Core::initModClass('intern', 'Term.php');
PHPWS_Core::initModClass('intern', 'Major.php');
PHPWS_Core::initModClass('intern', 'GradProgram.php');
PHPWS_Core::initModClass('intern', 'Department.php');

/* Check if it is time to insert more terms into DB */
if(Term::isTimeToUpdate()){
    Term::doTermUpdate();
 }

// Check that user has compat. browser
if (preg_match($ie7,$browser)) {
    $error = 'It is STRONGLY reccommended that you use Mozilla Firefox to access the Intern Inventory';
}else if (preg_match($ie_fail,$browser)) {
    NQ::simple('intern', INTERN_ERROR, 'Intern Inventory is not compatible with your browser. Please use Mozilla Firefox or another secure browser to access the database.');
    // @hack
    Intern_NotifyUI::display();
    Layout::addStyle('intern', 'style.css');
    return;
}

// Fetch the action from the REQUEST.
if(!isset($_REQUEST['action'])){
    $req = "";
}else{
    $req = $_REQUEST['action'];
}

// Show requested page.
switch ($req) {
    case 'edit_internship':
        PHPWS_Core::initModClass('intern','UI/InternshipUI.php');
        $content = InternshipUI::display();
        break;
    case 'add_internship':
        PHPWS_Core::initModClass('intern', 'Internship.php');
        Internship::addInternship();// NOTIFY
        break;
    case 'internship_details':
        PHPWS_Core::initModClass('intern', 'UI/InternshipDetailsUI.php');
        echo InternshipDetailsUI::display();
        exit;
        break;
    case 'search':
        PHPWS_Core::initModClass('intern', 'UI/SearchUI.php');
        $content = SearchUI::display();
        break;
    case 'results':
        PHPWS_Core::initModClass('intern', 'UI/ResultsUI.php');
        $content = ResultsUI::display();
        break;
    case DEPT_EDIT:
        PHPWS_Core::initModClass('intern','UI/DepartmentUI.php');
        PHPWS_Core::initModClass('intern','Department.php');
        if (isset($_REQUEST['add'])) {
            /* Add department with the name in REQUEST */
            if(isset($_REQUEST['name'])){
                Department::add($_REQUEST['name']);
            }else{
                NQ::simple('intern', INTERN_ERROR, "Department must have name.");
            }
        }else if (isset($_REQUEST['rename'])){
            /* Rename dept with ID to new name that was passed in REQUEST */
            if(isset($_REQUEST['id'])){
                $d = new Department($_REQUEST['id']);
                $d->rename($_REQUEST['rename']);
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot rename department.");
            }

        }else if(isset($_REQUEST['hide'])){
            /* Hide/Show department with ID passed in REQUEST. */
            if(isset($_REQUEST['id'])){
                $d = new Department($_REQUEST['id']);
                $d->hide($_REQUEST['hide'] == 1);
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot hide department.");
            }
        }else if(isset($_REQUEST['del'])){
            /* Delete department with same ID passed in REQUEST. */
            if(isset($_REQUEST['id'])){
                $d = new Department($_REQUEST['id']);
                $d->del();
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete department.");
            }
        }else if(isset($_REQUEST['fDel'])){
            /** for now... */
            NQ::simple('intern', INTERN_WARNING, 'Sorry, cannot forcefully delete a department.');
        }
        $content = DepartmentUI::display();
        break;
    case GRAD_PROG_EDIT:
        PHPWS_Core::initModClass('intern', 'GradProgram.php');
        PHPWS_Core::initModClass('intern', 'UI/GradProgramUI.php');
        if(isset($_REQUEST['add'])){
            /* Add grad program with the name in REQUEST */
            if(isset($_REQUEST['name'])){
                GradProgram::add($_REQUEST['name']);
            }else{
                NQ::simple('intern', INTERN_ERROR, "Grad Program must have name.");
            }
        }else if(isset($_REQUEST['rename'])){
            /* Rename program with ID to new name that was passed in REQUEST */
            if(isset($_REQUEST['id'])){
                $g = new GradProgram($_REQUEST['id']);
                $g->rename($_REQUEST['rename']);
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot rename graduate program.");
            }
        }else if(isset($_REQUEST['hide'])){
            /* Hide/Show program with ID passed in REQUEST. */
            if(isset($_REQUEST['id'])){
                $g = new GradProgram($_REQUEST['id']);
                $g->hide($_REQUEST['hide'] == 1);
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot hide graduate program.");
            }
        }else if(isset($_REQUEST['del'])){
            /* Delete program with same ID passed in REQUEST. */
            if(isset($_REQUEST['id'])){
                $g = new GradProgram($_REQUEST['id']);
                $g->del();
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete graduate program.");
            }
        }else if(isset($_REQUEST['fDel'])){
            /* Forcefully delete a grad program */
            if(isset($_REQUEST['id'])){
                $g = new GradProgram($_REQUEST['id']);
                $g->forceDelete();
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete graduate program.");
            }
        }
        $content = GradProgramUI::display();
        break;
    case MAJOR_EDIT:
        PHPWS_Core::initModClass('intern', 'UI/MajorUI.php');

        if(isset($_REQUEST['add'])){
            /* Add major with the name passed in REQUEST. */
            if(isset($_REQUEST['name'])){
                Major::add($_REQUEST['name']);
            }else{
                NQ::simple('intern', INTERN_ERROR, "Major must have name.");
            }
        }else if(isset($_REQUEST['rename'])){
            /* Rename major with ID to new name that was passed in REQUEST */
            if(isset($_REQUEST['id'])){
                $m = new Major($_REQUEST['id']);
                $m->rename($_REQUEST['rename']);
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot rename major.");
            }
        }else if(isset($_REQUEST['hide'])){
            /* Hide major with ID passed in REQUEST. */
            if(isset($_REQUEST['id'])){
                $m = new Major($_REQUEST['id']);
                $m->hide($_REQUEST['hide'] == 1);
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot hide major.");
            }
        }else if(isset($_REQUEST['del'])){
            /* Delete major with same ID passed in REQUEST. */
            if(isset($_REQUEST['id'])){
                $m = new Major($_REQUEST['id']);
                $m->del();
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete major.");
            }
        }else if(isset($_REQUEST['fDel'])){
            /* Forcefully delete a major */
            if(isset($_REQUEST['id'])){
                $m = new Major($_REQUEST['id']);
                $m->forceDelete();
            }else{
                NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete major.");
            }
        }
        $content = MajorUI::display();
        break;
    case 'edit_admins':
        PHPWS_Core::initModClass('intern', 'UI/AdminUI.php');
        PHPWS_Core::initModClass('intern', 'Admin.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        
        if(isset($_REQUEST['add'])){
            // Add user in REQUEST to administrator list for the department in REQUEST.
            Admin::add($_REQUEST['username'], $_REQUEST['department_id']);
        }else if(isset($_REQUEST['del'])){
            // Delete the user in REQUEST from department in REQUEST.
            Admin::del($_REQUEST['username'], $_REQUEST['department_id']);
        }
        $content = AdminUI::display();
        break;
    case 'pdf':
        PHPWS_Core::initModClass('intern', 'Internship.php');
        $i = new Internship($_REQUEST['id']);
        $i->getPDF();
        exit;
    case 'csv':
        PHPWS_Core::initModClass('intern', 'Internship.php');
        $filename = Internship::getCSVFile($_REQUEST['ids']);
        header('Content-type: text/csv');
        header('Content-Disposition: attachment: filename="InternshipsExport.csv"');
        readfile($filename);
        unlink($filename);
        exit;
    case 'upload_document_form':
        PHPWS_Core::initModClass('intern', 'Intern_Document_Manager.php');
        $docManager = new Intern_Document_Manager();
        $docManager->edit();
        break;
    case 'post_document_upload':
        PHPWS_Core::initModClass('intern', 'Intern_Document_Manager.php');
        $docManager = new Intern_Document_Manager();
        $docManager->postDocumentUpload();
        break;
    case 'delete_document':
        PHPWS_Core::initModClass('intern', 'Intern_Document.php');
        $doc = new Intern_Document($_REQUEST['doc_id']);
        $doc->delete();
        NQ::simple('intern', INTERN_SUCCESS, 'Document deleted.');
        NQ::close();
        PHPWS_Core::goBack();
        break;
    default:
        PHPWS_Core::initModClass('intern','Intern_Menu.php');
        // @hack
        NQ::simple('intern', INTERN_ERROR, $error);
        Intern_NotifyUI::display();
        Intern_Menu::showMenu();
        break;
}

/** 
 * Plug content into TopUI. Show notifications. Add Style.
 */
PHPWS_Core::initModClass('intern', 'UI/TopUI.php');
if(isset($content)){
    if($content === false){
        NQ::close();
        PHPWS_Core::reroute('index.php?module=intern');
    }
    Intern_NotifyUI::display();
    $content = TopUI::plug($content);
    Layout::add($content);
 }
Layout::addStyle('intern', 'style.css');
?>
