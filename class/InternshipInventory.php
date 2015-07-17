<?php

PHPWS_Core::initModClass('intern', 'Term.php');
PHPWS_Core::initModClass('intern', 'Major.php');
PHPWS_Core::initModClass('intern', 'State.php');
PHPWS_Core::initModClass('intern', 'GradProgram.php');
PHPWS_Core::initModClass('intern', 'Department.php');

define('STATE_EDIT', 'edit_states');

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

            case 'edit_internship':
                PHPWS_Core::initModClass('intern', 'UI/InternshipUI.php');
                $view = new InternshipUI();
                $this->content = $view->display();
                break;
            case 'add_internship':
                PHPWS_Core::initModClass('intern', 'command/SaveInternship.php');
                $ctrl = new SaveInternship();
                $ctrl->execute();
                test('finished execute',1);
                break;
            case 'search':
                PHPWS_Core::initModClass('intern', 'UI/SearchUI.php');
                $view = new SearchUI();
                $this->content = $view->display();
                break;
            case 'results':
                PHPWS_Core::initModClass('intern', 'UI/ResultsUI.php');
                $view = new ResultsUI();
                $this->content = $view->display();
                break;
            case DEPT_EDIT:
                PHPWS_Core::initModClass('intern', 'UI/DepartmentUI.php');
                PHPWS_Core::initModClass('intern', 'Department.php');
                if (isset($_REQUEST['add'])) {
                    /* Add department with the name in REQUEST */
                    if (isset($_REQUEST['name'])) {
                        Department::add($_REQUEST['name']);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "Department must have name.");
                    }
                } else if (isset($_REQUEST['rename'])) {
                    /* Rename dept with ID to new name that was passed in REQUEST */
                    if (isset($_REQUEST['id'])) {
                        $d = new Department($_REQUEST['id']);
                        $d->rename($_REQUEST['rename']);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot rename department.");
                    }
                } else if (isset($_REQUEST['hide'])) {
                    /* Hide/Show department with ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $d = new Department($_REQUEST['id']);
                        $d->hide($_REQUEST['hide'] == 1);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot hide department.");
                    }
                } else if (isset($_REQUEST['del'])) {
                    /* Delete department with same ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $d = new Department($_REQUEST['id']);
                        $d->del();
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete department.");
                    }
                } else if (isset($_REQUEST['fDel'])) {
                    /** for now... */
                    NQ::simple('intern', INTERN_WARNING, 'Sorry, cannot forcefully delete a department.');
                }
                $view = new DepartmentUI();
                $this->content = $view->display();
                break;

            case GRAD_PROG_EDIT:
                PHPWS_Core::initModClass('intern', 'UI/GradProgramUI.php');
                if (isset($_REQUEST['add'])) {
                    // Add grad program with the name in REQUEST
                    if (isset($_REQUEST['name'])) {
                        GradProgram::add($_REQUEST['name']);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "Grad Program must have name.");
                    }
                } else if (isset($_REQUEST['rename'])) {
                    // Rename program with ID to new name that was passed in REQUEST
                    if (isset($_REQUEST['id'])) {
                        $g = new GradProgram($_REQUEST['id']);
                        $g->rename($_REQUEST['rename']);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot rename graduate program.");
                    }
                } else if (isset($_REQUEST['hide'])) {
                    // Hide/Show program with ID passed in REQUEST.
                    if (isset($_REQUEST['id'])) {
                        $g = new GradProgram($_REQUEST['id']);
                        $g->hide($_REQUEST['hide'] == 1);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot hide graduate program.");
                    }
                } else if (isset($_REQUEST['del'])) {
                    // Delete program with same ID passed in REQUEST.
                    if (isset($_REQUEST['id'])) {
                        $g = new GradProgram($_REQUEST['id']);
                        $g->del();
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete graduate program.");
                    }
                }
                $view = new GradProgramUI();
                $this->content = $view->display();
                break;

            case MAJOR_EDIT:
                PHPWS_Core::initModClass('intern', 'UI/MajorUI.php');

                if (isset($_REQUEST['add'])) {
                    /* Add major with the name passed in REQUEST. */
                    if (isset($_REQUEST['name'])) {
                        Major::add($_REQUEST['name']);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "Major must have name.");
                    }
                } else if (isset($_REQUEST['rename'])) {
                    /* Rename major with ID to new name that was passed in REQUEST */
                    if (isset($_REQUEST['id'])) {
                        $m = new Major($_REQUEST['id']);
                        $m->rename($_REQUEST['rename']);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot rename major.");
                    }
                } else if (isset($_REQUEST['hide'])) {
                    /* Hide major with ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $m = new Major($_REQUEST['id']);
                        $m->hide($_REQUEST['hide'] == 1);
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot hide major.");
                    }
                } else if (isset($_REQUEST['del'])) {
                    /* Delete major with same ID passed in REQUEST. */
                    if (isset($_REQUEST['id'])) {
                        $m = new Major($_REQUEST['id']);
                        $m->del();
                    } else {
                        NQ::simple('intern', INTERN_ERROR, "No ID given. Cannot delete major.");
                    }
                }
                $view = new MajorUI();
                $this->content = $view->display();
                break;

                /**
                 * Chris additions
                 */
            case 'AFFIL_AGREE_EDIT':
                PHPWS_Core::initModClass('intern', 'UI/AffiliateAgreementUI.php');
                $this->content = AffiliateAgreementUI::display();
                break;
            case 'add_agreement_view':
                PHPWS_Core::initModClass('intern', 'UI/AddAgreementUI.php');
                $this->content = AddAgreementUI::display();
                break;
                /**
                 * Matt additions!
                 */
            case 'add_state':
                if (!Current_User::allow('intern', 'edit_state')) {
                    disallow();
                }
                PHPWS_Core::initModClass('intern', 'State.php');
                $state = new State($_GET['abbr']);
                $state->setActive(true);
                $state->save();
                exit();
                break;
            case 'remove_state':
                if (!Current_User::allow('intern', 'edit_state')) {
                    disallow();
                }
                PHPWS_Core::initModClass('intern', 'State.php');
                $state = new State($_GET['abbr']);
                $state->setActive(false);
                $state->save();
                exit();
                break;
            case STATE_EDIT:
                if (!Current_User::allow('intern', 'edit_state')) {
                    disallow();
                }
                PHPWS_Core::initModClass('intern', 'UI/StateUI.php');
                $view = new StateUI();
                $this->content = $view->display();
                break;
            case 'edit_admins':
                PHPWS_Core::initModClass('intern', 'UI/AdminUI.php');

                $view = new AdminUI();
                $this->content = $view->display();
                break;
            case 'get_dept':
                PHPWS_Core::initModClass('intern','command/GetDepartments.php');
                $deptData = new GetDepartments();
                $deptData->getData();
                break;
            case 'pdf':
                PHPWS_Core::initModClass('intern', 'InternshipFactory.php');
                PHPWS_Core::initModClass('intern', 'InternshipContractPdfView.php');
                PHPWS_Core::initModClass('intern', 'EmergencyContactFactory.php');
                $i = InternshipFactory::getInternshipById($_REQUEST['id']);
                $emgContacts = EmergencyContactFactory::getContactsForInternship($i);
                $pdfView = new InternshipContractPdfView($i, $emgContacts);
                $pdf = $pdfView->getPdf();
                $pdf->output();
                exit;
            case 'upload_document_form':
                PHPWS_Core::initModClass('intern', 'Intern_Document_Manager.php');
                $docManager = new Intern_Document_Manager();
                echo $docManager->edit();
                exit();
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
            case 'addEmergencyContact':
                PHPWS_Core::initModClass('intern', 'command/AddEmergencyContact.php');
                $ctrl = new AddEmergencyContact();
                $ctrl->execute();
                break;
            case 'removeEmergencyContact':
                PHPWS_Core::initModClass('intern', 'command/RemoveEmergencyContact.php');
                $ctrl = new RemoveEmergencyContact();
                $ctrl->execute();
                break;
            case 'edit_faculty':
                PHPWS_Core::initModClass('intern', 'FacultyUI.php');
                $facultyUI = new FacultyUI();
                $this->content = $facultyUI->display();
                break;
            case 'getFacultyListForDept':
                PHPWS_Core::initModClass('intern', 'command/GetFacultyListForDept.php');
                $ctrl = new GetFacultyListForDept();
                $ctrl->execute();
                break;
            case 'restFacultyById':
                PHPWS_Core::initModClass('intern', 'command/RestFacultyById.php');
                $ctrl = new RestFacultyById();
                $ctrl->execute();
                break;
            case 'facultyDeptRest':
                PHPWS_Core::initModClass('intern', 'command/FacultyDeptRest.php');
                $ctrl = new FacultyDeptRest();
                $ctrl->execute();
                break;
            case 'adminRest':
                PHPWS_Core::initModClass('intern', 'command/AdminRest.php');
                $ctrl = new AdminRest();
                $ctrl->execute();
                break;
            case 'majorRest':
                PHPWS_Core::initModClass('intern', 'command/MajorRest.php');
                $ctrl = new MajorRest();
                $ctrl->execute();
                break;
            case 'gradRest':
                PHPWS_Core::initModClass('intern', 'command/GradRest.php');
                $ctrl = new GradRest();
                $ctrl->execute();
                break;
            case 'deptRest':
                PHPWS_Core::initModClass('intern', 'command/DeptRest.php');
                $ctrl = new DeptRest();
                $ctrl->execute();
                break;
            case 'stateRest':
                PHPWS_Core::initModClass('intern', 'command/StateRest.php');
                $ctrl = new StateRest();
                $ctrl->execute();
                break;
            case 'emergencyContactRest':
                PHPWS_Core::initModClass('intern', 'command/EmergencyContactRest.php');
                $ctrl = new EmergencyContactRest();
                $ctrl->execute();
                break;
            default:
                PHPWS_Core::initModClass('intern', 'UI/InternMenu.php');
                $menu = new InternMenu();
                $this->content = $menu->display();
                break;
        }
    }

}

?>
