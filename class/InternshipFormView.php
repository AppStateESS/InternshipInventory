<?php

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
        $this->form = new PHPWS_Form('internship');
        $this->intern = new Internship();
        
        Layout::addPageTitle($pageTitle);
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
        PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');
        PHPWS_Core::initModClass('intern', 'WorkflowTransitionView.php');
        
        // Check the Internship's state, and set a default state if it's a new internship
        $workflowState = $this->intern->getWorkflowState();
        if(is_null($workflowState)){
            $state = WorkflowStateFactory::getState('CreationState');
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
        
        if(!Current_User::allow('intern', 'oied_certify') || $this->intern->isDomestic()){
            $this->form->setExtra('oied_certified', 'disabled="disabled" disabled');
        }
        
        $this->form->addHidden('oied_certified_hidden');
        
        /******************
         * Student fields *
         */
        $this->form->addText('student_first_name');
        $this->form->setLabel('student_first_name', 'First Name');
        $this->form->addText('student_middle_name');
        $this->form->setLabel('student_middle_name', 'Middle Name/Initial');
        $this->form->addText('student_last_name');
        $this->form->setLabel('student_last_name', 'Last Name');
        $this->form->addText('banner');
        $this->form->setLabel('banner', 'Banner ID'); // Digits only
        $this->form->addText('student_phone');
        $this->form->setLabel('student_phone', 'Phone');
        $this->form->addText('student_email');
        $this->form->setLabel('student_email', 'ASU Email');
        
        /* Student Address */
        $this->form->addText('student_address');
        $this->form->setLabel('student_address','Address');
        $this->form->addText('student_city');
        $this->form->setLabel('student_city','City');
        $this->form->addDropBox('student_state', State::$UNITED_STATES);
        $this->form->setLabel('student_state','State');
        $this->form->addText('student_zip');
        $this->form->setLabel('student_zip','Zip Code');
        
        // GPA
        $this->form->addText('student_gpa');
        $this->form->setLabel('student_gpa', 'GPA');
        
        // Campus
        $this->form->addRadioAssoc('campus', array('main_campus'=>'Main Campus', 'distance_ed'=>'Distance Ed'));
        $this->form->setMatch('campus', 'main_campus');
        
        // Student level radio button
        $levels = array('ugrad' => 'Undergraduate', 'grad' => 'Graduate');
        $this->form->addRadioAssoc('student_level', $levels);
        
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
        
        
        
        /***************************
         * Faculty supervisor info *
         */
        $this->form->addText('supervisor_first_name');
        $this->form->setLabel('supervisor_first_name', 'First Name');
        $this->form->addText('supervisor_last_name');
        $this->form->setLabel('supervisor_last_name', 'Last Name');
        $this->form->addText('supervisor_email');
        $this->form->setLabel('supervisor_email', 'Email');
        
        $this->form->addText('supervisor_phone', '828-262-');
        $this->form->setLabel('supervisor_phone', 'Phone');
        
        /************************
         * Department Drop Down *
         */
        if (Current_User::isDeity()) {
            if (!is_null($this->intern)){
                $depts = Department::getDepartmentsAssoc($this->intern->department_id);
            } else {
                $depts = Department::getDepartmentsAssoc();
            }
        }else {
            if (!is_null($this->intern)){
                $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername(), $this->intern->department_id);
            }else{
                $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
            }
        }
        $this->form->addSelect('department', $depts);
        $this->form->setLabel('department', 'Department');
        
        // If the user only has one department, select it for them
        // sizeof($depts) == 2 because of the 'Select Deparmtnet' option
        if(sizeof($depts) == 2){
            $keys = array_keys($depts);
            $this->form->setMatch('department', $keys[1]);
        }
        
        /***************
         * Agency info *
         */
        $this->form->addText('agency_name');
        $this->form->setLabel('agency_name', 'Name');
        $this->form->addText('agency_address');
        $this->form->setLabel('agency_address', 'Address');
        $this->form->addText('agency_city');
        $this->form->setLabel('agency_city', 'City');
        $this->form->addSelect('agency_state', State::$UNITED_STATES);
        $this->form->setLabel('agency_state', 'State');
        
        /*
        if (!is_null($this->intern)) {
            if (!$this->intern->isDomestic()) {
                
                // International. Need to add the location as extra
                // to the form element. Hackz
                
                $this->form->setExtra('agency_state', "where='{$this->agency->state}'");
            }
        }
        */
        
        $this->form->addText('agency_zip');
        $this->form->setLabel('agency_zip', 'Zip Code');
        $this->form->addText('agency_country');
        $this->form->setLabel('agency_country', 'Country');
        $this->form->addText('agency_phone');
        $this->form->setLabel('agency_phone', 'Phone');
        
        /***
         * Agency supervisor info
        */
        $this->form->addText('agency_sup_first_name');
        $this->form->setLabel('agency_sup_first_name', 'First Name');
        $this->form->addText('agency_sup_last_name');
        $this->form->setLabel('agency_sup_last_name', 'Last Name');
        $this->form->addText('agency_sup_title');
        $this->form->setLabel('agency_sup_title', 'Title');
        $this->form->addText('agency_sup_phone');
        $this->form->setLabel('agency_sup_phone', 'Phone');
        $this->form->addText('agency_sup_email');
        $this->form->setLabel('agency_sup_email', 'Email');
        $this->form->addCheck('copy_address');
        $this->form->setLabel('copy_address', "Supervisor's address is same as agency's");
        $this->form->addText('agency_sup_address');
        $this->form->setLabel('agency_sup_address', 'Address');
        $this->form->addText('agency_sup_city');
        $this->form->setLabel('agency_sup_city', 'City');
        $this->form->addSelect('agency_sup_state', State::$UNITED_STATES);
        $this->form->setLabel('agency_sup_state', 'State');
        
        /*
        if (!is_null($this->intern)) {
            if (!$this->intern->isDomestic()) {
                 //International. Need to add the location as extra
                 // to the form element. Hackz
                $this->form->setExtra('agency_sup_state', "where='{$this->agency->state}'");
            }
        }*/
        
        $this->form->addText('agency_sup_zip');
        $this->form->setLabel('agency_sup_zip', 'Zip Code');
        $this->form->addText('agency_sup_country');
        $this->form->setLabel('agency_sup_country', 'Country');
        $this->form->addText('agency_sup_fax');
        $this->form->setLabel('agency_sup_fax', 'Fax');
        
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
        $this->form->addText('loc_city');
        $this->form->setLabel('loc_city', 'City');
        $this->form->addSelect('loc_state', State::getAllowedStates());
        $this->form->setLabel('loc_state', 'State');
        $this->form->addText('loc_zip');
        $this->form->setLabel('loc_zip', 'Zip');
        
        // Itn'l location fields
        $this->form->addText('loc_province');
        $this->form->setLabel('loc_province', 'Province/Territory');
        $this->form->addText('loc_country');
        $this->form->setLabel('loc_country', 'Country');
        
        /*************
         * Term Info *
         */
        $terms = Term::getTermsAssoc();
        $terms[-1] = 'Select Term';
        $this->form->addSelect('term', $terms);
        $this->form->setLabel('term', 'Select Term');
        $this->form->addText('start_date');
        $this->form->setLabel('start_date', 'Start Date');
        $this->form->addText('end_date');
        $this->form->setLabel('end_date', 'End Date');
        $this->form->addText('credits');
        $this->form->setLabel('credits', 'Credit Hours');
        $this->form->addText('avg_hours_week');
        $this->form->setLabel('avg_hours_week', 'Average Hours per Week');
        
        $this->form->addCheck('multipart');
        $this->form->setLabel('multipart', 'This internship is part of a multi-part experience.');
        
        $this->form->addCheck('secondary_part');
        $this->form->setLabel('secondary_part', 'This is a secondary part (enrollment complete through first part).');
        
        /***************
         * Course Info *
         */
        $subjects = Subject::getSubjects();
        $this->form->addSelect('course_subj', $subjects);
        $this->form->setLabel('course_subj', 'Subject');
        
        $this->form->addText('course_no');
        $this->form->setLabel('course_no', 'Number');
        
        $this->form->addText('course_sect');
        $this->form->setLabel('course_sect', 'Section');
        
        $this->form->addText('course_title');
        $this->form->setLabel('course_title', 'Title');
        $this->form->setMaxSize('course_title',28); // Limit to 28 chars, per Banner
        
        
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
        
        /*******************
         * Internship Type *
         */
        $this->form->addCheck('internship_default_type');
        $this->form->setLabel('internship_default_type', 'Internship');
        $this->form->setMatch('internship_default_type', true); // Internship is checked by default
        $this->form->addCheck('student_teaching_type');
        $this->form->setLabel('student_teaching_type', 'Student Teaching');
        $this->form->addCheck('clinical_practica_type');
        $this->form->setLabel('clinical_practica_type', 'Clinical Practicum');
        
        /*********
         * Notes *
         */
        $this->form->addTextArea('notes');
        $this->form->setLabel('notes', 'Notes');
    }
}

?>