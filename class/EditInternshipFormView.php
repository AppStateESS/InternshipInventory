<?php

namespace Intern;


use Intern\EmergencyContactFormView;
use Intern\ChangeHistoryView;

/**
 * View class for showing the big internship form for
 * editing an existing internship.
 *
 * @see Internship
 * @author jbooker
 * @package intern
 *
 */
class EditInternshipFormView {

    private $form;
    private $intern;
    private $tpl;

    private $agency;
    private $department;

    private $formVals;

    /**
     * Constructor for the big Internship form.
     *
     * @param string $pagetitle
     * @param Internship $i
     */
    public function __construct($pageTitle, Internship $i, Agency $agency, Array $docs)
    {
        // Call parent constructor to setup form
        //parent::__construct($pageTitle);

        \Layout::addPageTitle($pageTitle);

        $this->intern = $i;

        $this->agency = $agency;
        $this->department = $this->intern->getDepartment();
        $this->docs = $docs;

        $this->form = new \PHPWS_Form('internship');
        $this->formVals = array();

        // Build all the form fields
        $this->buildInternshipForm();

        // Plug in the existing values from Internship object (sets default/selected values)
        $this->plugInternship();

        $this->setupContractButton();
        $this->setupDocumentList();
        $this->setupEmergencyContact();
        $this->setupChangeHistory();

        // Set a page title
        \Layout::addPageTitle($pageTitle);
    }

    public function getForm()
    {
        $this->form->mergeTemplate($this->getTemplateTags());
        return $this->form;
    }

    public function getTemplateTags()
    {
        return $this->tpl;
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
        $this->form->setAction('index.php?module=intern&action=SaveInternship');
        $this->form->addSubmit('submit', 'Save');


        /*********************
         * Workflow / Status *
        */
        // Check the Internship's state, and set a default state if it's a new internship
        /*
        $workflowState = $this->intern->getWorkflowState();
        if(is_null($workflowState)){
            $state = WorkflowStateFactory::getState('Intern\WorkflowState\CreationState');
            $this->intern->setState($state); // Set this initial value
        }
        */

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

        //$this->form->addText('banner');
        //$this->form->setLabel('banner', 'Banner ID'); // Digits only
        //$this->form->addCssClass('banner', 'form-control');

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
        if($this->intern->isDomestic() && !$this->intern->isInternational()) {
            $this->tpl['LOCATION'] = 'Domestic';
            $this->form->addHidden('location', 'domestic');
        } else if (!$this->intern->isDomestic() && $this->intern->isInternational()) {
            $this->tpl['LOCATION'] = 'International';
            $this->form->addHidden('location', 'international');
        }

        // Domestic fields
        $this->form->addText('loc_address');
        $this->form->setLabel('loc_address', 'Address');
        $this->form->addCssClass('loc_address', 'form-control');

        // City
        $this->form->addText('loc_city');
        $this->form->setLabel('loc_city', 'City');
        $this->form->addCssClass('loc_city', 'form-control');

        // State or Country & Province
        if ($this->intern->isDomestic()) {
            $states = State::getAllowedStates();
            $this->tpl['LOC_STATE'] = $states[$this->intern->getLocationState()];
        } else {
            $countres = CountryFactory::getCountries();
            $this->tpl['LOC_COUNTRY'] = $countries[$this->intern->getLocationCountry()];

            // Itn'l location fields
            $this->form->addText('loc_province');
            $this->form->setLabel('loc_province', 'Province/Territory');
            $this->form->addCssClass('loc_province', 'form-control');
        }

        // Zip
        $this->form->addText('loc_zip');
        $this->form->setLabel('loc_zip', 'Zip');
        $this->form->addCssClass('loc_zip', 'form-control');

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

    /**
     * Loads the form's fields with the internship's information.
     * TODO: Use getter methods instead of just accessing Internship member variables directly.
     */
    public function plugInternship()
    {
        $this->plugStudent();
        $this->plugDept();
        $this->plugFaculty();
        $this->plugAgency();
        $this->plugInternInfo();
        $this->plugCourseInfo();

        // Remove the term dropdown and repalce it
        $this->form->dropElement('term');
        $this->form->addSelect('term', array($this->intern->term => Term::rawToRead($this->intern->term)));
        $this->form->setLabel('term', 'Select Term');
        $this->form->addCssClass('term', 'form-control');
        $this->form->setMatch('term', $this->intern->term);

        $this->form->setMatch('experience_type', $this->intern->getExperienceType());

        // Plug
        $this->form->plugIn($this->formVals);

        /**
         * *
         * Emergency Contacts
         */
        //javascript('jquery');
        $contacts = EmergencyContactFactory::getContactsForInternship($this->intern);
        $emgContactJson = json_encode($contacts);
        \Layout::add(javascriptMod('intern', 'emergencyContact', array('existing_contacts_json' => $emgContactJson)));
    }

    private function plugStudent()
    {
        // Student
        $this->tpl['BANNER'] = $this->intern->getBannerId();

        $this->formVals['student_first_name'] = $this->intern->first_name;
        $this->formVals['student_middle_name'] = $this->intern->middle_name;
        $this->formVals['student_last_name'] = $this->intern->last_name;
        $this->formVals['student_phone'] = $this->intern->phone;
        $this->formVals['student_email'] = $this->intern->email;
        $this->formVals['student_level'] = $this->intern->level;
        $this->formVals['grad_prog'] = $this->intern->grad_prog;
        $this->formVals['ugrad_major'] = $this->intern->ugrad_major;
        $this->formVals['student_gpa'] = $this->intern->gpa;
        $this->formVals['campus'] = $this->intern->campus;

        // Student address
        $this->formVals['student_address'] = $this->intern->student_address;
        $this->formVals['student_city'] = $this->intern->student_city;
        $this->formVals['student_state'] = $this->intern->student_state;
        $this->formVals['student_zip'] = $this->intern->student_zip;

    }

    private function plugFaculty()
    {
        // Faculty Supervisor
        $facultyId = $this->intern->getFacultyId();
        if (isset($facultyId) && $facultyId != 0) {
            $this->formVals['faculty_id'] = $facultyId;
        }
    }

    private function plugAgency()
    {
        // Agency
        $this->form->addHidden('agency_id', $this->agency->id);
        $this->formVals['agency_name']            = $this->agency->name;
        $this->formVals['agency_address']         = $this->agency->address;
        $this->formVals['agency_city']            = $this->agency->city;
        $this->formVals['agency_state']           = $this->agency->state;
        $this->formVals['agency_zip']             = $this->agency->zip;
        $this->formVals['agency_province']        = $this->agency->province;
        $this->formVals['agency_country']         = $this->agency->country;
        $this->formVals['agency_phone']           = $this->agency->phone;
        $this->formVals['agency_sup_first_name']  = $this->agency->supervisor_first_name;
        $this->formVals['agency_sup_last_name']   = $this->agency->supervisor_last_name;
        $this->formVals['agency_sup_title']       = $this->agency->supervisor_title;
        $this->formVals['agency_sup_phone']       = $this->agency->supervisor_phone;
        $this->formVals['agency_sup_email']       = $this->agency->supervisor_email;
        $this->formVals['agency_sup_fax']         = $this->agency->supervisor_fax;
        $this->formVals['agency_sup_address']     = $this->agency->supervisor_address;
        $this->formVals['agency_sup_city']        = $this->agency->supervisor_city;
        $this->formVals['agency_sup_state']       = $this->agency->supervisor_state;
        $this->formVals['agency_sup_zip']         = $this->agency->supervisor_zip;
        $this->formVals['agency_sup_province']    = $this->agency->supervisor_province;
        $this->formVals['agency_sup_country']     = $this->agency->supervisor_country;
        $this->formVals['copy_address']           = $this->agency->address_same_flag == 't';
    }

    private function plugInternInfo()
    {
        // Internship
        $this->form->addHidden('internship_id', $this->intern->id);
        $this->formVals['start_date'] = $this->intern->start_date ? date('m/d/Y', $this->intern->start_date) : null;
        $this->formVals['end_date'] = $this->intern->end_date ? date('m/d/Y', $this->intern->end_date) : null;
        $this->formVals['credits'] = $this->intern->credits;
        $this->formVals['avg_hours_week'] = $this->intern->avg_hours_week;
        $this->formVals['loc_address'] = $this->intern->loc_address;
        $this->formVals['loc_city'] = $this->intern->loc_city;
        $this->formVals['loc_state'] = $this->intern->loc_state;
        $this->formVals['loc_zip'] = $this->intern->loc_zip;
        $this->formVals['loc_province'] = $this->intern->loc_province;

        // Other internship details
        if ($this->intern->domestic) {
            $this->form->setMatch('location', 'domestic');
        } else {
            $this->form->setMatch('location', 'internat');
        }
        if ($this->intern->paid) {
            $this->form->setMatch('payment', 'paid');
            $this->form->setMatch('stipend', $this->intern->stipend);
        } else {
            $this->form->setMatch('payment', 'unpaid');
        }

        $this->formVals['pay_rate'] = $this->intern->pay_rate;

        if ($this->intern->oied_certified) {
            $this->form->setMatch('oied_certified', true);
            $this->form->setValue('oied_certified_hidden', 'true');
        } else {
            $this->form->setValue('oied_certified_hidden', 'false');
        }       $this->formVals['loc_country'] = $this->intern->loc_country;
    }

    private function plugCourseInfo()
    {
        // Course Info

        // Remove the subject field and re-add it
        $this->form->dropElement('course_subj');
        $this->form->addSelect('course_subj', Subject::getSubjects($this->intern->course_subj));
        $this->form->setMatch('course_subj', $this->intern->course_subj);
        $this->formVals['course_no'] = $this->intern->course_no;
        $this->formVals['course_sect'] = $this->intern->course_sect;
        $this->formVals['course_title'] = $this->intern->course_title;

        if ($this->intern->isMultipart()) {
            $this->form->setMatch('multipart', '1');
        }

        if ($this->intern->isSecondaryPart()) {
            $this->form->setMatch('secondary_part', '1');
        }

        $this->formVals['corequisite_course_num'] = $this->intern->getCorequisiteNum();
        $this->formVals['corequisite_course_sect'] = $this->intern->getCorequisiteSection();

    }

    private function plugDept()
    {
        // Department
        $this->formVals['department'] = $this->intern->getDepartment()->getId();
    }

    private function setupContractButton()
    {
        $this->tpl['PDF'] = \PHPWS_Text::linkAddress('intern', array('action' => 'pdf', 'id' => $this->intern->getId()));
    }

    private function setupDocumentList()
    {
        // Document list
        if (!is_null($this->docs)) {
            foreach ($this->docs as $doc) {
                $this->tpl['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink('blah'),
                                             'DELETE' => $doc->getDeleteLink());
            }
        }

        // Document upload button
        $folder = new InternFolder(InternDocument::getFolderId());
        $this->tpl['UPLOAD_DOC'] = $folder->documentUpload($this->intern->id);
    }

    private function setupEmergencyContact()
    {
        $emgContactDialog = new EmergencyContactFormView($this->intern);

        $this->tpl['ADD_EMERGENCY_CONTACT'] = '<button type="button" class="btn btn-default btn-sm" id="add-ec-button"><i class="fa fa-plus"></i> Add Contact</button>';
        $this->tpl['EMERGENCY_CONTACT_DIALOG'] = $emgContactDialog->getHtml();
    }

    private function setupChangeHistory()
    {
        $historyView = new ChangeHistoryView($this->intern);
        $tpl['CHANGE_LOG'] = $historyView->show();
    }
}

?>
