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
            $transactionName = 'showHomepage';
        } else {
            $req = $_REQUEST['action'];
            $transactionName = $req;
        }

        // Tell NewRelic about the controller we're going to run, so we get
        // better transaction names than just all 'index.php'
        if (extension_loaded('newrelic')) { // Ensure PHP agent is available
            newrelic_name_transaction($transactionName);
        }

        // Show requested page.
        switch ($req) {
            case 'example_form':
                header('Content-type: application/pdf');
                readfile(\PHPWS_SOURCE_DIR . 'mod/intern/pdf/Internship_Example.pdf');
                exit();
                break;

            case 'ShowInternship':
                $ctrl = new Command\ShowInternship();
                $this->content = $ctrl->execute();
                break;
            case 'ShowAddInternship':
                $ctrl = new Command\ShowAddInternship();
                $this->content = $ctrl->execute()->getView()->render();
                break;
            case 'AddInternship':
                $ctrl = new Command\AddInternship();
                $ctrl->execute();
                break;
            case 'SaveInternship':
                $ctrl = new Command\SaveInternship();
                $ctrl->execute();
                break;
            case 'DeleteInternship':
                $ctrl = new Command\DeleteInternship();
                $ctrl->execute();
                break;
            case 'copyInternshipToNextTerm':
                $ctrl = new Command\CopyInternshipToNextTerm();
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
            case 'showEditDept':
                $view = new UI\DepartmentUI();
                $this->content = $view->display();
                break;
            case 'showEditMajors':
                $view = new UI\MajorUI();
                $this->content = $view->display();
                break;
            case 'showEditGradProgs':
                $view = new UI\GradProgramUI();
                $this->content = $view->display();
                break;

            case 'showAffiliateAgreement':
                $view = new UI\AffiliateAgreementUI();
                $this->content = $view->display();
                break;

            case 'addAgreementView':
                $view = new UI\AddAgreementUI();
                $this->content = $view->display();
                break;

            case 'addAffiliate':
                $ctrl = new Command\SaveAffiliate();
                $ctrl->execute();
                break;
            case 'showAffiliateEditView':
                $view = new UI\EditAgreementUI();
                $this->content = $view->display();
                break;
            case 'saveAffiliate':
                $ctrl = new Command\SaveAffiliate();
                $ctrl->execute();
                break;
            case 'AffiliateRest':
                $ctrl = new Command\AffiliateRest();
                $ctrl->execute();
                break;
            case 'AffiliateDeptRest':
                $ctrl = new Command\AffiliateDeptRest();
                $ctrl->execute();
                break;
            case 'AffiliateStateRest':
                $ctrl = new Command\AffiliateStateRest();
                $ctrl->execute();
                break;
            case 'uploadAffiliationAgreemenet':
                $docManager = new AffiliationContractDocumentManager();
                echo $docManager->edit();
                exit();
                break;
            case 'postAffiliationUpload':
                $docManager = new AffiliationContractDocumentManager();
                $docManager->postDocumentUpload();
                break;
            case 'delete_affiliation_contract':
                AffiliationContractFactory::deleteByDocId($_REQUEST['doc_id']);
                \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Document deleted.');
                \NQ::close();
                \PHPWS_Core::goBack();
                break;
            case 'edit_states':
                if (!\Current_User::allow('intern', 'edit_state')) {
                    disallow();
                }
                $view = new UI\StateUI();
                $this->content = $view->display();
                break;
            case 'showEditAdmins':
                $view = new UI\AdminUI();
                $this->content = $view->display();
                break;
            case 'pdf':
                $i = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
                $emgContacts = EmergencyContactFactory::getContactsForInternship($i);
                $pdfView = new InternshipContractPdfView($i, $emgContacts);
                $pdf = $pdfView->getPdf();
                $pdf->output();
                exit;
            case 'upload_document_form':
                $docManager = new DocumentManager();
                echo $docManager->edit();
                exit();
                break;
            case 'post_document_upload':
                $docManager = new DocumentManager();
                $docManager->postDocumentUpload();
                break;
            case 'delete_document':
                $doc = new InternDocument($_REQUEST['doc_id']);
                $doc->delete();
                \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Document deleted.');
                \NQ::close();
                \PHPWS_Core::goBack();
                break;
            case 'addEmergencyContact':
                $ctrl = new Command\AddEmergencyContact();
                $ctrl->execute();
                break;
            case 'removeEmergencyContact':
                $ctrl = new Command\RemoveEmergencyContact();
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
            case 'GetSearchSuggestions':
                $ctrl = new Command\GetSearchSuggestions();
                $ctrl->execute();
                break;
            case 'GetStates':
                $ctrl = new Command\GetStates();
                $ctrl->execute();
                break;
            case 'GetAvailableCountries':
                $ctrl = new Command\GetAvailableCountries();
                $ctrl->execute();
                break;
            case 'GetDepartments':
                $ctrl = new Command\GetDepartments();
                $ctrl->execute();
                break;
            case 'GetAvailableTerms':
                $ctrl = new Command\GetAvailableTerms();
                $ctrl->execute();
                break;
            case 'GetUndergradMajors':
                $ctrl = new Command\GetUndergradMajors();
                $ctrl->execute();
                break;
            case 'GetGraduateMajors':
                $ctrl = new Command\GetGraduateMajors();
                $ctrl->execute();
                break;
            case 'adminRest':
                $ctrl = new Command\AdminRest();
                $ctrl->execute();
                break;
            case 'majorRest':
                $ctrl = new Command\MajorRest();
                $ctrl->execute();
                break;
            case 'gradRest':
                $ctrl = new Command\GradRest();
                $ctrl->execute();
                break;
            case 'deptRest':
                $ctrl = new Command\DeptRest();
                $ctrl->execute();
                break;
            case 'stateRest':
                $ctrl = new Command\StateRest();
                $ctrl->execute();
                break;
            case 'emergencyContactRest':
                $ctrl = new Command\EmergencyContactRest();
                $ctrl->execute();
                break;
            case 'SendPendingEnrollmentReminders':
                $ctrl = new Command\SendPendingEnrollmentReminders();
                $ctrl->execute();
                break;
            default:
                $menu = new UI\InternMenu();
                $this->content = $menu->display();
                break;
        }
    }

}
