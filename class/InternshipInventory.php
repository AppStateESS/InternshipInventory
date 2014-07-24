<?php

namespace Intern;

class InternshipInventory {

    private $content;

    public function __construct()
    {

    }

    public function getContent()
    {
        return $this->content;
    }

    public function handleRequest()
    {
        /* Check if it is time to insert more terms into DB */
        if (Term::isTimeToUpdate()) {
            Term::doTermUpdate();
        }

        // Fetch the action from the REQUEST.
        if (!isset($_REQUEST['action'])) {
            $req = "";
        } else {
            $req = $_REQUEST['action'];
        }

        // Show requested page.
        switch ($req) {
            case 'example_form':
                header('Content-type: application/pdf');
                readfile(PHPWS_SOURCE_DIR . 'mod/intern/pdf/Internship_Example.pdf');
                exit();
                break;

            case 'ShowInternship':
                $view = new UI\InternshipUI();
                $this->content = $view->display();
                break;
            case 'ShowAddInternship':
                $ctrl = new Command\ShowAddInternship();
                $response = $ctrl->execute();
                break;
            case 'AddInternship':
                $ctrl = new Command\AddInternship();
                $ctrl->execute();
            case 'SaveInternship':
                $ctrl = new Command\SaveInternship();
                $ctrl->execute();
                break;
            case 'search':
                $view = new UI\SearchUI();
                $this->content = $view->display();
                break;
            case 'results':
                $view = new UI\ResultsUI();
                $this->content = $view->display();
                break;
            case 'edit_dept':
                if (isset($_REQUEST['add'])) {
                    /* Add department with the name in REQUEST */
                    if (isset($_REQUEST['name'])) {
                        Department::add($_REQUEST['name']);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Department must have name.");
                    }
                } else if (isset($_REQUEST['rename'])) {
                    /* Rename dept with ID to new name that was passed in REQUEST */
                    if (isset($_REQUEST['id'])) {
                        $d = new Department($_REQUEST['id']);
                        $d->rename($_REQUEST['rename']);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot rename department.");
                    }
                } else if (isset($_REQUEST['hide'])) {
                    /* Hide/Show department with ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $d = new Department($_REQUEST['id']);
                        $d->hide($_REQUEST['hide'] == 1);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot hide department.");
                    }
                } else if (isset($_REQUEST['del'])) {
                    /* Delete department with same ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $d = new Department($_REQUEST['id']);
                        $d->del();
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot delete department.");
                    }
                } else if (isset($_REQUEST['fDel'])) {
                    /** for now... */
                    \NQ::simple('intern', \Intern\NotifyUI::WARNING, 'Sorry, cannot forcefully delete a department.');
                }
                $view = new DepartmentUI();
                $this->content = $view->display();
                break;
            case 'edit_grad':
                if (isset($_REQUEST['add'])) {
                    /* Add grad program with the name in REQUEST */
                    if (isset($_REQUEST['name'])) {
                        GradProgram::add($_REQUEST['name']);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Grad Program must have name.");
                    }
                } else if (isset($_REQUEST['rename'])) {
                    /* Rename program with ID to new name that was passed in REQUEST */
                    if (isset($_REQUEST['id'])) {
                        $g = new GradProgram($_REQUEST['id']);
                        $g->rename($_REQUEST['rename']);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot rename graduate program.");
                    }
                } else if (isset($_REQUEST['hide'])) {
                    /* Hide/Show program with ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $g = new GradProgram($_REQUEST['id']);
                        $g->hide($_REQUEST['hide'] == 1);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot hide graduate program.");
                    }
                } else if (isset($_REQUEST['del'])) {
                    /* Delete program with same ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $g = new GradProgram($_REQUEST['id']);
                        $g->del();
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot delete graduate program.");
                    }
                }
                $view = new GradProgramUI();
                $this->content = $view->display();
                break;
            case 'edit_major':

                if (isset($_REQUEST['add'])) {
                    /* Add major with the name passed in REQUEST. */
                    if (isset($_REQUEST['name'])) {
                        Major::add($_REQUEST['name']);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Major must have name.");
                    }
                } else if (isset($_REQUEST['rename'])) {
                    /* Rename major with ID to new name that was passed in REQUEST */
                    if (isset($_REQUEST['id'])) {
                        $m = new Major($_REQUEST['id']);
                        $m->rename($_REQUEST['rename']);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot rename major.");
                    }
                } else if (isset($_REQUEST['hide'])) {
                    /* Hide major with ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $m = new Major($_REQUEST['id']);
                        $m->hide($_REQUEST['hide'] == 1);
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot hide major.");
                    }
                } else if (isset($_REQUEST['del'])) {
                    /* Delete major with same ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $m = new Major($_REQUEST['id']);
                        $m->del();
                    } else {
                        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "No ID given. Cannot delete major.");
                    }
                }
                $view = new MajorUI();
                $this->content = $view->display();
                break;
                /**
                 * Matt additions!
                 */
            case 'add_state':
                if (!Current_User::allow('intern', 'edit_state')) {
                    disallow();
                }
                $state = new State($_GET['abbr']);
                $state->setActive(true);
                $state->save();
                exit();
                break;
            case 'remove_state':
                if (!Current_User::allow('intern', 'edit_state')) {
                    disallow();
                }
                $state = new State($_GET['abbr']);
                $state->setActive(false);
                $state->save();
                exit();
                break;
            case 'edit_states':
                if (!Current_User::allow('intern', 'edit_state')) {
                    disallow();
                }
                $view = new StateUI();
                $this->content = $view->display();
                break;
            case 'edit_admins':
                if (isset($_REQUEST['add'])) {
                    // Add user in REQUEST to administrator list for the department in REQUEST.
                    Admin::add($_REQUEST['username'], $_REQUEST['department_id']);
                } else if (isset($_REQUEST['del'])) {
                    // Delete the user in REQUEST from department in REQUEST.
                    Admin::del($_REQUEST['username'], $_REQUEST['department_id']);
                } else if (isset($_REQUEST['user_complete'])) {
                    $users = Admin::searchUsers($_REQUEST['term']);
                    echo json_encode($users);
                    exit();
                }
                $view = new AdminUI();
                $this->content = $view->display();
                break;
            case 'pdf':
                $i = InternshipFactory::getInternshipById($_REQUEST['id']);
                $emgContacts = EmergencyContactFactory::getContactsForInternship($i);
                $pdfView = new InternshipContractPdfView($i, $emgContacts);
                $pdf = $pdfView->getPdf();
                $pdf->output();
                exit;
            case 'upload_document_form':
                $docManager = new Intern_Document_Manager();
                echo $docManager->edit();
                exit();
                break;
            case 'post_document_upload':
                $docManager = new Intern_Document_Manager();
                $docManager->postDocumentUpload();
                break;
            case 'delete_document':
                $doc = new InternDocument($_REQUEST['doc_id']);
                $doc->delete();
                \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Document deleted.');
                \NQ::close();
                PHPWS_Core::goBack();
                break;
            case 'addEmergencyContact':
                $ctrl = new Command\AddEmergencyContact();
                $ctrl->execute();
                break;
            case 'removeEmergencyContact':
                $ctrl = new RemoveEmergencyContact();
                $ctrl->execute();
                break;
            case 'edit_faculty':
                $facultyUI = new UI\FacultyUI();
                $this->content = $facultyUI->display();
                break;
            case 'getFacultyListForDept':
                $ctrl = new Command\GetFacultyListForDept();
                $ctrl->execute();
                break;
            case 'restFacultyById':
                $ctrl = new Command\RestFacultyById();
                $ctrl->execute();
                break;
            case 'facultyDeptRest':
                $ctrl = new Command\FacultyDeptRest();
                $ctrl->execute();
                break;
            default:
                $menu = new UI\InternMenu();
                $this->content = $menu->display();
                break;
        }
    }

}

?>
