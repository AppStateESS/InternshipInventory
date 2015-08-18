<?php

namespace Intern;

/**
 * This class holds the form for adding/editing an internship.
 */
class InternshipView {

    public static $requiredFields = array(
            'student_first_name',
            'student_last_name',
            'student_email',
            'agency_name',
            'department');

    private $intern;
    private $student;
    private $wfState;
    private $agency;
    private $docs;

    public function __construct(Internship $internship, Student $student, WorkflowState $wfState, Agency $agency, Array $docs)
    {
        $this->intern = $internship;
        $this->student = $student;
        $this->wfState = $wfState;
        $this->agency = $agency;
        $this->docs = $docs;
    }

    public function display()
    {
        $tpl = array();
        $tpl['TITLE'] = 'Edit Internship';

        // Setup the form
        $internshipForm = new EditInternshipFormView($this->intern, $this->student, $this->agency, $this->docs);

        // Get the Form object
        $form = $internshipForm->getForm();

        /*
         * If 'missing' is set then we have been redirected
         * back to the form because the user didn't type in something and
         * somehow got past the javascript.
         */
        if (isset($_REQUEST['missing'])) {
            $missing = explode(' ', $_REQUEST['missing']);

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
            $form->addHidden('id', $this->intern->id);
        }

        $form->mergeTemplate($tpl);

        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'internshipView.tpl');
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
