<?php

namespace Intern\UI;

use Intern\InternshipFormView;
use Intern\InternshipFactory;
use Intern\AgencyFactory;
use Intern\EditInternshipFormView;
use Intern\InternFolder;
use Intern\InternDocument;

use \PHPWS_DB;

/**
 * This class holds the form for adding/editing an internship.
 */
class InternshipUI implements UI {

    public static $requiredFields = array('student_first_name',
            'student_last_name',
            'student_phone',
            'student_email',
            'student_gpa',
            'campus',
            'student_level',
            'agency_name',
            'term',
            'department',
            'campus');

    private $intern;
    private $wfstate;

    public function display()
    {
        $tpl = array();
        $tpl['TITLE'] = 'Edit Internship';

        // Make sure an 'internship_id' key is set on the request
        if(!isset($_REQUEST['internship_id'])) {
            \NQ::simple('intern', NotifyUI::ERROR, 'No internship ID was given.');
            \NQ::close();
            \PHPWS_Core::reroute('index.php');
        }

        // Load the Internship
        try{
            $this->intern = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        }catch(InternshipNotFoundException $e){
            \NQ::simple('intern', NotifyUI::ERROR, 'Could not locate an internship with the given ID.');
            return;
        }

        // Load the agency
        $agency = AgencyFactory::getAgencyById($this->intern->getAgencyId());

        // Load the documents
        $docs = $this->intern->getDocuments();
        if($docs === null) {
            $docs = array(); // if no docs, setup an empty array
        }

        // Load the WorkflowState
        $this->wfState = $this->intern->getWorkflowState();

        // Setup the form
        $internshipForm = new EditInternshipFormView('Edit Internship', $this->intern, $agency, $docs);

        // Get the Form object
        $form = $internshipForm->getForm();

        /*
         * If 'missing' is set then we have been redirected
         * back to the form because the user didn't type in something and
         * somehow got past the javascript.
         */
        if (isset($_REQUEST['missing'])) {
            $missing = explode(' ', $_REQUEST['missing']);

            //javascriptMod('intern', 'missing');
            /*
             * Set classes on field we are missing.
            */
            foreach ($missing as $m) {
            	//$form->addCssClass($m, 'has-error');
            	$form->addExtraTag($m, 'data-has-error="true"');
            }


            /* Plug old values back into form fields. */
            $form->plugIn($_GET);

            /* Re-add hidden fields with object ID's */
            $i = InternshipFactory::getInternshipById($_GET['internship_id']);
            $a = $this->intern->getAgency();
            //$f = $this->intern->getFacultySupervisor();
            //$form->addHidden('agency_id', $a->id);
            //$form->addHidden('supervisor_id', $f->id);
            $form->addHidden('id', $this->intern->id);
        }

        $form->mergeTemplate($tpl);

        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_internship.tpl');
    }

    private function showWarnings()
    {
        // Show warning if no documents uploaded but workflow state suggests there should be documents
        if(($this->wfState instanceof SigAuthReadyState || $this->wfState instanceof SigAuthApprovedState || $this->wfState instanceof DeanApprovedState || $this->wfState instanceof RegisteredState) && ($docs < 1))
        {
            \NQ::simple('intern', NotifyUI::WARNING, "No documents have been uploaded yet. Usually a copy of the signed contract document should be uploaded.");
        }

        // Show a warning if in SigAuthReadyState, is international, and not OIED approved
        if ($this->wfState instanceof SigAuthReadyState && $this->intern->isInternational() && !$this->intern->isOiedCertified()) {
            \NQ::simple('intern', NotifyUI::WARNING, 'This internship can not be approved by the Signature Authority bearer until the internship is certified by the Office of International Education and Development.');
        }

        // Show a warning if in DeanApproved state and is distance_ed campus
        if ($this->wfState == 'DeanApprovedState' && $this->intern->isDistanceEd()) {
            \NQ::simple('intern', NotifyUI::WARNING, 'This internship must be registered by Distance Education.');
        }

        // Show warning & sanity check cource section #
        if ($this->intern->isDistanceEd() && ($this->intern->getCourseSection() < 300 || $this->intern->getCourseSection() > 399)) {
            NQ::simple('intern', NotifyUI::WARNING, "This is a distance ed internship, so the course section number should be between 300 and 399.");
        }

        // Show warning & Sanity check distance ed radio
        if (!$this->intern->isDistanceEd() && ($this->intern->getCourseSection() > 300 && $this->intern->getCourseSection() < 400)) {
            \NQ::simple('intern', NotifyUI::WARNING, "The course section number you entered looks like a distance ed course. Be sure to check the Distance Ed option, or double check the section number.");
        }
    }

}

?>
