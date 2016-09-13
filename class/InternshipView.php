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
            'department',
            'agency_name');

    private $intern;
    private $student;
    private $wfState;
    private $agency;
    private $docs;

    public function __construct(Internship $internship, Student $student = null, WorkflowState $wfState, Agency $agency, Array $docs)
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

        $this->showWarnings();
        $this->showStudentWarnings();

        javascript('jquery');

        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'internshipView.tpl');
    }

    private function showWarnings()
    {

        // Show warning if no documents uploaded but workflow state suggests there should be documents
        if(($this->wfState instanceof WorkflowState\SigAuthReadyState || $this->wfState instanceof WorkflowState\SigAuthApprovedState || $this->wfState instanceof WorkflowState\DeanApprovedState || $this->wfState instanceof WorkflowState\RegisteredState) && (sizeof($this->docs) < 1) && (!$this->intern->isSecondaryPart()))
        {
            \NQ::simple('intern', UI\NotifyUI::WARNING, "No documents have been uploaded yet. Usually a copy of the signed contract document should be uploaded.");
        }

        // Show a warning if in SigAuthReadyState, is international, and not OIED approved
        if ($this->wfState instanceof WorkflowState\SigAuthReadyState && $this->intern->isInternational() && !$this->intern->isOiedCertified()) {
            \NQ::simple('intern', UI\NotifyUI::WARNING, 'This internship can not be approved by the Signature Authority bearer until the internship is certified by the Office of International Education and Development.');
        }

        // Show a warning if in DeanApproved state and is distance_ed campus
        if ($this->wfState instanceof WorkflowState\DeanApprovedState && $this->intern->isDistanceEd()) {
            \NQ::simple('intern', UI\NotifyUI::WARNING, 'This internship must be registered by Distance Education.');
        }

        // Show warning & sanity check cource section #
        if ($this->intern->isDistanceEd() && ($this->intern->getCourseSection() < 300 || $this->intern->getCourseSection() > 399)) {
            \NQ::simple('intern', UI\NotifyUI::WARNING, "This is a distance ed internship, so the course section number should be between 300 and 399.");
        }

        // Show warning & Sanity check distance ed radio
        if (!$this->intern->isDistanceEd() && ($this->intern->getCourseSection() > 300 && $this->intern->getCourseSection() < 400)) {
            \NQ::simple('intern', UI\NotifyUI::WARNING, "The course section number you entered looks like a distance ed course. Be sure to check the Distance Ed option, or double check the section number.");
        }

        // Show a warning if the student's type is invalid in the student data (from Banner)
        if($this->student instanceof Student && $this->student->getLevel() == null){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "This student's 'level' is not set in Banner. This could mean this student is not currently enrolled.");
        }
    }

    private function showStudentWarnings()
    {
        // Check if we have a student object. If we don't, then bail immediately.
        if(!isset($this->student)){
            return;
        }

        // Show warning if graduation date is prior to start date
        $gradDate = $this->student->getGradDate();
        if(isset($gradDate) && $gradDate < $this->intern->getStartDate())
        {
            \NQ::simple('intern', UI\NotifyUI::WARNING, 'This student\'s graduation date is prior to the internship\'s start date.');
        }

        // Show warning if graduation date is prior to end date
        if(isset($gradDate) && $gradDate < $this->intern->getEndDate())
        {
            \NQ::simple('intern', UI\NotifyUI::WARNING, 'This student\'s graduation date is prior to the internship\'s completion date.');
        }

        // Show warning if student is enrolled for more than the credit hour limit for the term
        $internHours = $this->intern->getCreditHours();
        if(isset($internHours) && $this->student->isCreditHourLimited($internHours, $this->intern->getTerm())) {
            \NQ::simple('intern', UI\NotifyUI::WARNING, 'This internship will cause the student to exceed the semester credit hour limit. This student will need an Overload Permit from their Dean\'s Office.');
        }

        // Show warning if GPA is below the minimum
        if($this->student->getGpa() < Internship::GPA_MINIMUM) {
            $minGpa = sprintf('%.2f', Internship::GPA_MINIMUM);
            \NQ::simple('intern', UI\NotifyUI::WARNING, "This student's GPA is less than the required minimum of {$minGpa}.");
        }

    }
}
