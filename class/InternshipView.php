<?php

namespace Intern;

use Intern\InternSettings;

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
    private $term;
    private $studentExistingCreditHours;

    private $termInfo;
    private $settings;

    public function __construct(Internship $internship, Student $student = null, WorkflowState $wfState, Agency $agency, Array $docs, Term $term, $studentExistingCreditHours, InternSettings $settings)
    {
        $this->intern = $internship;
        $this->student = $student;
        $this->wfState = $wfState;
        $this->agency = $agency;
        $this->docs = $docs;
        $this->term = $term;
        $this->studentExistingCreditHours = $studentExistingCreditHours;
        $this->settings = $settings;
    }

    public function display()
    {
        $tpl = array();

        // Setup the form
        $internshipForm = new EditInternshipFormView($this->intern, $this->student, $this->agency, $this->docs, $this->term, $this->studentExistingCreditHours, $this->settings);

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

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'emergencyContact');

        $form->mergeTemplate($tpl);

        $this->showWarnings();
        $this->showStudentWarnings();

        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'internshipView.tpl');
    }

    private function showWarnings()
    {

        // Show warning if no documents uploaded but workflow state suggests there should be documents
        if(($this->wfState instanceof WorkflowState\SigAuthReadyState || $this->wfState instanceof WorkflowState\SigAuthApprovedState || $this->wfState instanceof WorkflowState\DeanApprovedState || $this->wfState instanceof WorkflowState\RegisteredState) && (sizeof($this->docs) < 1) && (!$this->intern->isSecondaryPart()))
        {
            \NQ::simple('intern', UI\NotifyUI::WARNING, "No documents have been uploaded yet. Usually a copy of the signed contract document should be uploaded.");
        }

        // Show a warning if International certification required, internship is in SigAuthReadyState, is international, and not OIED approved
        if($this->settings->getRequireIntlCertification()){
            if ($this->wfState instanceof WorkflowState\SigAuthReadyState && $this->intern->isInternational() && !$this->intern->isOiedCertified()) {
                \NQ::simple('intern', UI\NotifyUI::WARNING, 'This internship can not be approved by the Signature Authority bearer until the internship is certified by the Office of International Education and Development.');
            }
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

        // Show a warning if the start date selected is outside of the term start date
        if($this->intern->start_date != 0 && ($this->intern->start_date < $this->term->getStartTimestamp() || $this->intern->start_date > $this->term->getEndTimestamp())){
          \NQ::simple('intern', UI\NotifyUI::WARNING, "The start date you selected is ouside the dates of the term. If correct, fill out the <a target='_blank' href=\"https:\/\/registrar.appstate.edu\/\/sites/registrar.appstate.edu/files/academic_course_meeting_dates_exception_form_102416_1.pdf\">Meeting Dates Exception Form</a>.");
        }

        // Show a warning if the ending date selected is outside of the term end date
        if($this->intern->end_date != 0 && ($this->intern->end_date > $this->term->getEndTimestamp() || $this->intern->end_date < $this->term->getStartTimestamp())){
          \NQ::simple('intern', UI\NotifyUI::WARNING, "The end date you selected is ouside the dates of the term. If correct, fill out the <a target='_blank' href=\"https:\/\/registrar.appstate.edu\/\/sites/registrar.appstate.edu/files/academic_course_meeting_dates_exception_form_102416_1.pdf\">Meeting Dates Exception Form</a>.");
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

        if(isset($internHours) && $this->student->isCreditHourLimited($internHours, $this->studentExistingCreditHours, $this->term)) {
            \NQ::simple('intern', UI\NotifyUI::WARNING, 'This internship will cause the student to exceed the semester credit hour limit. This student will need an Overload Permit from their Dean\'s Office.');
        }

        // Show warning if GPA is below the minimum
        if($this->student->getGpa() < Internship::GPA_MINIMUM) {
            $minGpa = sprintf('%.2f', Internship::GPA_MINIMUM);
            \NQ::simple('intern', UI\NotifyUI::WARNING, "This student's GPA is less than the required minimum of {$minGpa}.");
        }

    }
}
