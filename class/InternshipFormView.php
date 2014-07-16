<?php

namespace Intern;

/**
 * View class for showing the big internship form for
 * creating a new Internship.
 * 
 * @see Internship
 * @see EditInternshipFormView
 * @author jbooker
 * @package intern
 *
 */
class InternshipFormView {
    
    protected $form;
    protected $intern;
    
    /**
     * Constructor for the big Internship form.
     */
    public function __construct($pageTitle)
    {
        $this->form = new \PHPWS_Form('internship');
        $this->intern = new Internship();
        
        \Layout::addPageTitle($pageTitle);
    }

    public function getForm()
    {
        return $this->form;
    }
    
    /**
     * Builds the body of the internship form.
     */
    public function buildInternshipForm()
    {
        javascript('jquery');
        javascript('jquery_ui');
        javascriptMod('intern', 'spinner');
        javascriptMod('intern', 'formGoodies');
        
        // Form Submission setup
        $this->form->setAction('index.php?module=intern&action=add_internship');
        $this->form->addSubmit('submit', 'Save');


        /*********************
         * Workflow / Status *
        */
        // Check the Internship's state, and set a default state if it's a new internship
        $workflowState = $this->intern->getWorkflowState();
        if(is_null($workflowState)){
            $state = WorkflowStateFactory::getState('Intern\WorkflowState\CreationState');
            $this->intern->setState($state); // Set this initial value
        }
        
        // Workflow Transitions View, adds fields to the form by reference
        $transView = new WorkflowTransitionView($this->intern, $this->form);
        $transView->show();
        
        
        /*****************
         * OIED Approval *
         */
        $this->form->addCheck('oied_certified');
        $this->form->setLabel('oied_certified', 'Certified by Office of International Education and Development');

        // If the user is not allowed to do OIED certification, disable the checkbox
        if(!\Current_User::allow('intern', 'oied_certify') || $this->intern->isDomestic()){
            $this->form->setExtra('oied_certified', 'disabled="disabled" disabled');
        }

        // Hidden field that shadows the real field, to ensure a value is always submitted,
        // because disabled fields are not submitted
        $this->form->addHidden('oied_certified_hidden');
        
        /******************
         * Student fields *
         */
        $this->form->addText('student_first_name');
        $this->form->setLabel('student_first_name', 'First Name');
        $this->form->addCssClass('student_first_name', 'form-control');
        
        $this->form->addText('student_middle_name');
        $this->form->setLabel('student_middle_name', 'Middle Name/Initial');
        $this->form->addCssClass('student_middle_name', 'form-control');
        
        $this->form->addText('student_last_name');
        $this->form->setLabel('student_last_name', 'Last Name');
        $this->form->addCssClass('student_last_name', 'form-control');
        
        $this->form->addText('banner');
        $this->form->setLabel('banner', 'Banner ID'); // Digits only
        $this->form->addCssClass('banner', 'form-control');
        
        $this->form->addText('student_phone');
        $this->form->setLabel('student_phone', 'Phone');
        $this->form->addCssClass('student_phone', 'form-control');
        
        $this->form->addText('student_email');
        $this->form->setLabel('student_email', 'ASU Email');
        $this->form->addCssClass('student_email', 'form-control');
        
        /* Student Address */
        $this->form->addText('student_address');
        $this->form->setLabel('student_address','Address');
        $this->form->addCssClass('student_address', 'form-control');
        
        $this->form->addText('student_city');
        $this->form->setLabel('student_city','City');
        $this->form->addCssClass('student_city', 'form-control');
        
        $this->form->addDropBox('student_state', State::$UNITED_STATES);
        $this->form->setLabel('student_state','State');
        $this->form->addCssClass('student_state', 'form-control');
        
        $this->form->addText('student_zip');
        $this->form->setLabel('student_zip','Zip Code');
        $this->form->addCssClass('student_zip', 'form-control');
        
        // GPA
        $this->form->addText('student_gpa');
        $this->form->setLabel('student_gpa', 'GPA');
        $this->form->addCssClass('student_gpa', 'form-control');
        
        // Campus
        $this->form->addRadioAssoc('campus', array('main_campus'=>'Main Campus', 'distance_ed'=>'Distance Ed'));
        $this->form->setMatch('campus', 'main_campus');
        
        // Student level
        $levels = array('-1' => 'Choose level', 'ugrad' => 'Undergraduate', 'grad' => 'Graduate');
        $this->form->addDropBox('student_level', $levels);
        $this->form->setLabel('student_level', 'Level');
        $this->form->addCssClass('student_level', 'form-control');
        
        // Student Major dummy box (gets replaced by dropdowns below using JS when student_level is selected)
        $levels = array('-1' => 'Choose student level first');
        $this->form->addDropBox('student_major', $levels);
        $this->form->setLabel('student_major', 'Major / Program');
        $this->form->addCssClass('student_major', 'form-control');
        
        /*****************************
         * Undergrad Major Drop Down *
         */
        if (isset($this->intern)){
            $majors = Major::getMajorsAssoc($this->intern->ugrad_major);
        }else{
            $majors = Major::getMajorsAssoc();
        }
        
        $this->form->addSelect('ugrad_major', $majors);
        $this->form->setLabel('ugrad_major', 'Undergraduate Majors &amp; Certificate Programs');
        $this->form->addCssClass('ugrad_major', 'form-control');
        
        
        /****************************
         * Graduate Major Drop Down *
         */
        if (isset($this->intern)){
            $progs = GradProgram::getGradProgsAssoc($this->intern->grad_prog);
        }else{
            $progs = GradProgram::getGradProgsAssoc();
        }
        
        $this->form->addSelect('grad_prog', $progs);
        $this->form->setLabel('grad_prog', 'Graduate Majors &amp; Certificate Programs');
        $this->form->addCssClass('grad_prog', 'form-control');
        
        
        /************************
         * Department Drop Down *
         */
        if (\Current_User::isDeity()) {
            if (!is_null($this->intern)){
                $depts = Department::getDepartmentsAssoc($this->intern->department_id);
            } else {
                $depts = Department::getDepartmentsAssoc();
            }
        }else {
            if (!is_null($this->intern)){
                $depts = Department::getDepartmentsAssocForUsername(\Current_User::getUsername(), $this->intern->department_id);
            }else{
                $depts = Department::getDepartmentsAssocForUsername(\Current_User::getUsername());
            }
        }
        $this->form->addSelect('department', $depts);
        $this->form->setLabel('department', 'Department');
        $this->form->addCssClass('department', 'form-control');
        
        // If the user only has one department, select it for them
        // sizeof($depts) == 2 because of the 'Select Deparmtnet' option
        if(sizeof($depts) == 2){
            $keys = array_keys($depts);
            $this->form->setMatch('department', $keys[1]);
        }
        
        
        /********************
         * Faculty Member Dropdown
         * 
         * The options for this drop down are provided through AJAX on page-load and
         * when the user changes the department dropdown above.
         */
        $this->form->addSelect('faculty', array(-1=>'Select Faculty Advisor'));
        $this->form->setExtra('faculty', 'disabled');
        $this->form->setLabel('faculty', 'Faculty Advisor / Instructor of Record');
        $this->form->addCssClass('faculty', 'form-control');
        
        // Hidden field for selected faculty member
        $this->form->addHidden('faculty_id');
        
        
        /***************
         * Agency info *
         */
        $this->form->addText('agency_name');
        $this->form->setLabel('agency_name', 'Name');
        $this->form->addCssClass('agency_name', 'form-control');
        
        $this->form->addCheck('copy_address_agency');
        $this->form->setLabel('copy_address_agency', "Agency's address is same as Internship's");
        
        $this->form->addText('agency_address');
        $this->form->setLabel('agency_address', 'Address');
        $this->form->addCssClass('agency_address', 'form-control');
        
        $this->form->addText('agency_city');
        $this->form->setLabel('agency_city', 'City');
        $this->form->addCssClass('agency_city', 'form-control');
        
        $this->form->addSelect('agency_state', State::$UNITED_STATES);
        $this->form->setLabel('agency_state', 'State');
        $this->form->addCssClass('agency_state', 'form-control');
        
        $this->form->addText('agency_zip');
        $this->form->setLabel('agency_zip', 'Zip Code');
        $this->form->addCssClass('agency_zip', 'form-control');
        
        $this->form->addText('agency_province');
        $this->form->setLabel('agency_province', 'Province/Territory');
        $this->form->addCssClass('agency_province', 'form-control');
        
        $this->form->addText('agency_country');
        $this->form->setLabel('agency_country', 'Country');
        $this->form->addCssClass('agency_country', 'form-control');
        
        $this->form->addText('agency_phone');
        $this->form->setLabel('agency_phone', 'Phone');
        $this->form->addCssClass('agency_phone', 'form-control');
        
        /***
         * Agency supervisor info
        */
        $this->form->addText('agency_sup_first_name');
        $this->form->setLabel('agency_sup_first_name', 'First Name');
        $this->form->addCssClass('agency_sup_first_name', 'form-control');
        
        $this->form->addText('agency_sup_last_name');
        $this->form->setLabel('agency_sup_last_name', 'Last Name');
        $this->form->addCssClass('agency_sup_last_name', 'form-control');
        
        $this->form->addText('agency_sup_title');
        $this->form->setLabel('agency_sup_title', 'Title');
        $this->form->addCssClass('agency_sup_title', 'form-control');
        
        $this->form->addText('agency_sup_phone');
        $this->form->setLabel('agency_sup_phone', 'Phone');
        $this->form->addCssClass('agency_sup_phone', 'form-control');
        
        $this->form->addText('agency_sup_email');
        $this->form->setLabel('agency_sup_email', 'Email');
        $this->form->addCssClass('agency_sup_email', 'form-control');
        
        $this->form->addCheck('copy_address');
        $this->form->setLabel('copy_address', "Supervisor's address is same as agency's");
        
        $this->form->addText('agency_sup_address');
        $this->form->setLabel('agency_sup_address', 'Address');
        $this->form->addCssClass('agency_sup_address', 'form-control');
        
        $this->form->addText('agency_sup_city');
        $this->form->setLabel('agency_sup_city', 'City');
        $this->form->addCssClass('agency_sup_city', 'form-control');
        
        $this->form->addSelect('agency_sup_state', State::$UNITED_STATES);
        $this->form->setLabel('agency_sup_state', 'State');
        $this->form->addCssClass('agency_sup_state', 'form-control');
        
        $this->form->addText('agency_sup_zip');
        $this->form->setLabel('agency_sup_zip', 'Zip Code');
        $this->form->addCssClass('agency_sup_zip', 'form-control');
        
        $this->form->addText('agency_sup_province');
        $this->form->setLabel('agency_sup_province', 'Province');
        $this->form->addCssClass('agency_sup_province', 'form-control');
        
        $this->form->addText('agency_sup_country');
        $this->form->setLabel('agency_sup_country', 'Country');
        $this->form->addCssClass('agency_sup_country', 'form-control');
        
        $this->form->addText('agency_sup_fax');
        $this->form->setLabel('agency_sup_fax', 'Fax');
        $this->form->addCssClass('agency_sup_fax', 'form-control');
        
        
        /**********************
         * Internship details *
         */
        
        /***********************
         * Internship location *
         */
        $loc = array('domestic' => 'Domestic', 'internat' => 'International');
        $this->form->addRadioAssoc('location', $loc);
        //$this->form->setMatch('location', 'domestic'); // Default to domestic
        //$this->form->setRequired('location');
        
        // Domestic fields
        $this->form->addText('loc_address');
        $this->form->setLabel('loc_address', 'Address');
        $this->form->addCssClass('loc_address', 'form-control');
        
        $this->form->addText('loc_city');
        $this->form->setLabel('loc_city', 'City');
        $this->form->addCssClass('loc_city', 'form-control');
        
        $this->form->addSelect('loc_state', State::getAllowedStates());
        $this->form->setLabel('loc_state', 'State');
        $this->form->addCssClass('loc_state', 'form-control');
        
        $this->form->addText('loc_zip');
        $this->form->setLabel('loc_zip', 'Zip');
        $this->form->addCssClass('loc_zip', 'form-control');
        
        // Itn'l location fields
        $this->form->addText('loc_province');
        $this->form->setLabel('loc_province', 'Province/Territory');
        $this->form->addCssClass('loc_province', 'form-control');
        
        $this->form->addText('loc_country');
        $this->form->setLabel('loc_country', 'Country');
        $this->form->addCssClass('loc_country', 'form-control');
        
        /*************
         * Term Info *
         */
        $terms = Term::getFutureTermsAssoc();
        $terms[-1] = 'Select Term';
        $this->form->addSelect('term', $terms);
        $this->form->setLabel('term', 'Select Term');
        $this->form->addCssClass('term', 'form-control');
        
        $this->form->addText('start_date');
        $this->form->setLabel('start_date', 'Start Date');
        $this->form->addCssClass('start_date', 'form-control');
        
        $this->form->addText('end_date');
        $this->form->setLabel('end_date', 'End Date');
        $this->form->addCssClass('end_date', 'form-control');
        
        $this->form->addText('credits');
        $this->form->setLabel('credits', 'Credit Hours');
        $this->form->addCssClass('credits', 'form-control');
        
        $this->form->addText('avg_hours_week');
        $this->form->setLabel('avg_hours_week', 'Average Hours per Week');
        $this->form->addCssClass('avg_hours_week', 'form-control');
        
        $this->form->addCheck('multipart');
        $this->form->setLabel('multipart', 'This internship is part of a multi-part experience.');
        
        $this->form->addCheck('secondary_part');
        $this->form->setLabel('secondary_part', 'This is a secondary part (enrollment complete through primary part).');
        
        /***************
         * Course Info *
         */
        $subjects = Subject::getSubjects();
        $this->form->addSelect('course_subj', $subjects);
        $this->form->setLabel('course_subj', 'Subject');
        $this->form->addCssClass('course_subj', 'form-control');
        
        $this->form->addText('course_no');
        $this->form->setLabel('course_no', 'Number');
        $this->form->addCssClass('course_no', 'form-control');
        
        $this->form->addText('course_sect');
        $this->form->setLabel('course_sect', 'Section');
        $this->form->addCssClass('course_sect', 'form-control');
        
        $this->form->addText('course_title');
        $this->form->setLabel('course_title', 'Title');
        $this->form->setMaxSize('course_title',28); // Limit to 28 chars, per Banner
        $this->form->addCssClass('course_title', 'form-control');
        
        // Corequisite
        if (!is_null($this->intern)) {
        	$dept = $this->intern->getDepartment();
        	if ($dept->hasCorequisite()){
        		$this->form->addText('corequisite_course_num');
        		$this->form->addCssClass('corequisite_course_num', 'form-control');
        		
        		$this->form->addText('corequisite_course_sect');
        		$this->form->addCssClass('corequisite_course_sect', 'form-control');
        	}
        }
        
        /************
         * Pay Info *
         */
        $pay = array('unpaid' => 'Unpaid', 'paid' => 'Paid');
        $this->form->addRadioAssoc('payment', $pay);
        $this->form->setMatch('payment', 'unpaid'); // Default to unpaid
        $this->form->addCheck('stipend');
        $this->form->setLabel('stipend', 'Stipend');
        
        $this->form->addText('pay_rate');
        $this->form->setLabel('pay_rate', 'Pay Rate');
        $this->form->addCssClass('pay_rate', 'form-control');
        
        /*******************
         * Internship Type *
         */
        $this->form->addRadioAssoc('experience_type', Internship::getTypesAssoc());
        $this->form->setMatch('experience_type', 'internship');

        /*********
         * Notes *
         */
        $this->form->addTextArea('notes');
        $this->form->setLabel('notes', 'Notes');
        $this->form->addCssClass('notes', 'form-control');
    }
}

?>
