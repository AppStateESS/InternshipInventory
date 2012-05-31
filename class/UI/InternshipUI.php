<?php

/**
 * This class holds the form for adding/editing an internship.
 */
PHPWS_Core::initModClass('intern', 'UI/UI.php');

class InternshipUI implements UI {

    public static $requiredFields = array('student_first_name',
            'student_last_name',
            'banner',
            'student_phone',
            'student_email',
            'student_gpa',
            'campus',
            'student_level',
            'agency_name',
            'term',
            'department',
            'campus',
            'emergency_contact_name',
            'emergency_contact_relation',
            'emergency_contact_phone',
            'location');

    public static function display()
    {
        PHPWS_Core::initModClass('intern', 'Internship.php');
        PHPWS_Core::initModClass('intern', 'Intern_Document.php');
        PHPWS_Core::initModClass('intern', 'Intern_Folder.php');
        PHPWS_Core::initModClass('intern', 'Agency.php');

        $tpl = array();

        if (isset($_REQUEST['internship_id'])) {
            /* Attempting to edit internship */
            $internship = new Internship($_REQUEST['internship_id']);
            if ($internship->id == 0) {
                /* Intership failed to load */
                NQ::simple('intern', INTERN_ERROR, 'Failed to get internship.');
                return false;
            }
            $form = self::getInternshipForm($internship, $tpl);
            //$tpl['PDF'] = PHPWS_Text::moduleLink('Generate Contract', 'intern', array('action' => 'pdf', 'id' => $internship->id), null, null, 'button contract-button');
            $tpl['PDF'] = PHPWS_Text::linkAddress('intern', array('action' => 'pdf', 'id' => $internship->id));

            self::plugInternship($form, $internship);
            /* Plug in document list */
            $docs = $internship->getDocuments();
            if (!is_null($docs)) {
                foreach ($docs as $doc) {
                    $tpl['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink('blah'),
                            'DELETE' => $doc->getDeleteLink());
                }
            }
            $folder = new Intern_Folder(Intern_Document::getFolderId());
            $tpl['UPLOAD_DOC'] = $folder->documentUpload($internship->id);
            $tpl['TITLE'] = 'Edit Student';
            
            Layout::addPageTitle('Edit Internship');
        } else {
            
            if(!Current_User::allow('intern', 'create_internship')){
                NQ::simple('intern', INTERN_ERROR, 'You do not have permission to create new internships.');
                NQ::close();
                PHPWS_Core::home();
            }
            
            Layout::addPageTitle('Add Internship');
            
            /* Show form with empty fields. */
            $form = self::getInternshipForm(null, $tpl);
            // Show a disabled button in document list if we are adding an internship.
            $tpl['UPLOAD_DOC'] = "<input type='button' disabled='disabled' class='disabled' title='Must save internship first.' value='Add Document'/>";
            $tpl['TITLE'] = 'Add Student';
            
        }
        /*
         * If 'missing' is set then we have been redirected
        * back to the form because the user didn't type in something and
        * somehow got past the javascript.
        */
        if (isset($_REQUEST['missing'])) {
            $missing = explode(' ', $_REQUEST['missing']);

            javascriptMod('intern', 'missing');
            /*
             * Set classes on field we are missing.
            */
            foreach ($missing as $m) {
                if ($m == 'location') {
                    $form->addTplTag('LOC_HIGHLIGHT', ' style="background-color : #FF5D5D"');
                } else {
                    $form->setClass($m, 'missing');
                }
            }

            /* Plug old values back into form fields. */
            $form->plugIn($_GET);

            // If internship is being edited...
            if (isset($_REQUEST['internship_id'])) {
                /* Re-add hidden fields with object ID's */
                $i = new Internship($_GET['internship_id']);
                $a = $i->getAgency();
                $f = $i->getFacultySupervisor();
                $form->addHidden('agency_id', $a->id);
                $form->addHidden('supervisor_id', $f->id);
                $form->addHidden('id', $i->id);
            }
        }

        $form->mergeTemplate($tpl);
        
        //test($form->getTemplate(),1);
        
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'add_internship.tpl');
    }

    /**
     * Build the form for adding/editing an internship.
     *
     * If there is an Internship obj passed as parameter
     * then fill in the form with that Internship's fields.
     */
    public static function getInternshipForm(Internship $i=NULL, &$tpl)
    {
        PHPWS_Core::initModClass('intern', 'Term.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'Major.php');
        PHPWS_Core::initModClass('intern', 'GradProgram.php');
        PHPWS_Core::initModClass('intern', 'Subject.php');

        $form = new PHPWS_Form('internship');
        if (!is_null($i)) {
            $a = $i->getAgency();
        } else {
            $i = new Internship;
            $a = new Agency;
        }
        $form->setAction('index.php?module=intern&action=add_internship');
        $form->addSubmit('submit', 'Save');

        /*********************
         * Workflow / Status *
         */
        PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');
        PHPWS_Core::initModClass('intern', 'WorkflowTransitionView.php');
        if(!is_null($i->state)){
            $state = WorkflowStateFactory::getState($i->state);
        }else{
            $state = WorkflowStateFactory::getState('CreationState');
            $i->setState($state); // Set this initial value
        }

        $transView = new WorkflowTransitionView($state, $form, $i);
        $transView->show();
        
        if(!is_null($i->id)){
            PHPWS_Core::initModClass('intern', 'ChangeHistoryView.php');
            $historyView = new ChangeHistoryView($i);
            $tpl['CHANGE_LOG'] = $historyView->show();
        }
        
        // Show a warning if in SigAuthReadyState, is international, and not OIED approved
        if($i->state == "SigAuthReadyState" && $i->international == 1 && $i->oied_certified !=1){
            NQ::simple('intern', INTERN_WARNING, 'This internship can not be approved by the Signature Authority bearer until the internship is certified by the Office of International Education and Development.');
        }
        
        // Show a warning if in DeanApproved state and is distance_ed campus
        if($i->state == 'DeanApprovedState' && $i->isDistanceEd()){
            NQ::simple('intern', INTERN_WARNING, 'This internship must be registered by Distance Education.');
        }
        
        /*****************
         * OIED Approval *
         */
        $form->addCheck('oied_certified');
        $form->setLabel('oied_certified', 'Certified by Office of International Education and Development');

        if(!Current_User::allow('intern', 'oied_certify') || $i->isDomestic()){
            $form->setExtra('oied_certified', 'disabled="disabled" disabled');
            //$form->setClass('oied_certified', 'disabled-check');
        }
        
        $form->addHidden('oied_certified_hidden');
        

        /**
         * Student fields
         */
        $form->addText('student_first_name');
        $form->setLabel('student_first_name', 'First Name');
        $form->addText('student_middle_name');
        $form->setLabel('student_middle_name', 'Middle Name/Initial');
        $form->addText('student_last_name');
        $form->setLabel('student_last_name', 'Last Name');
        $form->addText('banner');
        $form->setLabel('banner', 'Banner ID'); // Digits only
        $form->addText('student_phone');
        $form->setLabel('student_phone', 'Phone');
        $form->addText('student_email');
        $form->setLabel('student_email', 'ASU Email');

        /* Student Address */
        $form->addText('student_address');
        $form->setLabel('student_address','Address');
        $form->addText('student_city');
        $form->setLabel('student_city','City');
        $form->addDropBox('student_state', State::$UNITED_STATES);
        $form->setLabel('student_state','State');
        $form->addText('student_zip');
        $form->setLabel('student_zip','Zip Code');

        // GPA
        $form->addText('student_gpa');
        $form->setLabel('student_gpa', 'GPA');
        //$form->setRequired('student_gpa');
        
        // Campus
        $form->addRadioAssoc('campus',Array('main_campus'=>'Main Campus', 'distance_ed'=>'Distance Ed'));
        //$form->setRequired('campus');
        $form->setMatch('campus', 'main_campus');
        
        // Student level radio button
        $levels = array('ugrad' => 'Undergraduate', 'grad' => 'Graduate');
        $form->addRadioAssoc('student_level', $levels);
        //$form->setMatch('student_level', 'ugrad');
        //$form->setRequired('student_level');

        // Undergrad major drop down
        if (isset($i)){
            $majors = Major::getMajorsAssoc($i->ugrad_major);
        }else{
            $majors = Major::getMajorsAssoc();
        }

        $form->addSelect('ugrad_major', $majors);
        $form->setLabel('ugrad_major', 'Undergraduate Majors &amp; Certificate Programs');

        // Graduate major drop down
        if (isset($i)){
            $progs = GradProgram::getGradProgsAssoc($i->grad_prog);
        }else{
            $progs = GradProgram::getGradProgsAssoc();
        }

        $form->addSelect('grad_prog', $progs);
        $form->setLabel('grad_prog', 'Graduate Majors &amp; Certificate Programs');

        /* Emergency Contact */
        $form->addText('emergency_contact_name');
        $form->setClass('emergency_contact_name', 'form-text');
        $form->setLabel('emergency_contact_name', 'Name');
        //$form->setRequired('emergency_contact_name');
        
        $form->addText('emergency_contact_relation');
        $form->setClass('emergency_contact_relation', 'form-text');
        $form->setLabel('emergency_contact_relation', 'Relationship');
       //$form->setRequired('emergency_contact_relation');
        
        $form->addText('emergency_contact_phone');
        $form->setClass('emergency_contact_phone', 'form-text');
        $form->setLabel('emergency_contact_phone', 'Phone');
        //$form->setRequired('emergency_contact_phone');
        
        /***
         * Faculty supervisor info.
         */
        $form->addText('supervisor_first_name');
        $form->setLabel('supervisor_first_name', 'First Name');
        $form->addText('supervisor_last_name');
        $form->setLabel('supervisor_last_name', 'Last Name');
        $form->addText('supervisor_email');
        $form->setLabel('supervisor_email', 'Email');
        $form->addText('supervisor_phone');
        $form->setLabel('supervisor_phone', 'Phone');
        if (Current_User::isDeity()) {
            if (!is_null($i))
            $depts = Department::getDepartmentsAssoc($i->department_id);
            else
            $depts = Department::getDepartmentsAssoc();
        }else {
            if (!is_null($i))
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername(), $i->department_id);
            else
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
        }
        $form->addSelect('department', $depts);
        $form->setLabel('department', 'Department');

        /***
         * Agency info
         */
        $form->addText('agency_name');
        $form->setLabel('agency_name', 'Name');
        $form->addText('agency_address');
        $form->setLabel('agency_address', 'Address');
        $form->addText('agency_city');
        $form->setLabel('agency_city', 'City');
        $form->addSelect('agency_state', State::$UNITED_STATES);
        $form->setLabel('agency_state', 'State');
        if (!is_null($i)) {
            if (!$i->isDomestic()) {
                /*
                 * International. Need to add the location as extra
                * to the form element. Hackz
                */
                $form->setExtra('agency_state', "where='$a->state'");
            }
        }
        $form->addText('agency_zip');
        $form->setLabel('agency_zip', 'Zip Code');
        $form->addText('agency_country');
        $form->setLabel('agency_country', 'Country');
        $form->addText('agency_phone');
        $form->setLabel('agency_phone', 'Phone');

        /***
         * Agency supervisor info
         */
        $form->addText('agency_sup_first_name');
        $form->setLabel('agency_sup_first_name', 'First Name');
        $form->addText('agency_sup_last_name');
        $form->setLabel('agency_sup_last_name', 'Last Name');
        $form->addText('agency_sup_title');
        $form->setLabel('agency_sup_title', 'Title');
        $form->addText('agency_sup_phone');
        $form->setLabel('agency_sup_phone', 'Phone');
        $form->addText('agency_sup_email');
        $form->setLabel('agency_sup_email', 'Email');
        $form->addCheck('copy_address');
        $form->setLabel('copy_address', "Supervisor's address is same as agency's");
        $form->addText('agency_sup_address');
        $form->setLabel('agency_sup_address', 'Address');
        $form->addText('agency_sup_city');
        $form->setLabel('agency_sup_city', 'City');
        $form->addSelect('agency_sup_state', State::$UNITED_STATES);
        $form->setLabel('agency_sup_state', 'State');
        if (!is_null($i)) {
            if (!$i->isDomestic()) {
                /*
                 * International. Need to add the location as extra
                * to the form element. Hackz
                */
                $form->setExtra('agency_sup_state', "where='$a->state'");
            }
        }
        $form->addText('agency_sup_zip');
        $form->setLabel('agency_sup_zip', 'Zip Code');
        $form->addText('agency_sup_country');
        $form->setLabel('agency_sup_country', 'Country');
        $form->addText('agency_sup_fax');
        $form->setLabel('agency_sup_fax', 'Fax');

        /***
         * Internship details.
         */
        $terms = Term::getTermsAssoc();
        $terms[-1] = 'Select Term';
        $form->addSelect('term', $terms);
        $form->setLabel('term', 'Select Term');
        $form->addText('start_date');
        $form->setLabel('start_date', 'Start Date');
        $form->addText('end_date');
        $form->setLabel('end_date', 'End Date');
        $form->addText('credits');
        $form->setLabel('credits', 'Credit Hours');
        $form->addText('avg_hours_week');
        $form->setLabel('avg_hours_week', 'Average Hours per Week');

        /**
         * Internship location
         */
        $loc = array('domestic' => 'Domestic', 'internat' => 'International');
        $form->addRadioAssoc('location', $loc);
        //$form->setMatch('location', 'domestic'); // Default to domestic
        //$form->setRequired('location');

        // Domestic fields
        $form->addText('loc_address');
        $form->setLabel('loc_address', 'Address');
        $form->addText('loc_city');
        $form->setLabel('loc_city', 'City');
        $form->addSelect('loc_state', State::getAllowedStates());
        $form->setLabel('loc_state', 'State');
        $form->addText('loc_zip');
        $form->setLabel('loc_zip', 'Zip');

        // Itn'l location fields
        $form->addText('loc_province');
        $form->setLabel('loc_province', 'Province/Territory');
        $form->addText('loc_country');
        $form->setLabel('loc_country', 'Country');

        $pay = array('unpaid' => 'Unpaid', 'paid' => 'Paid');
        $form->addRadioAssoc('payment', $pay);
        $form->setMatch('payment', 'unpaid'); // Default to unpaid
        $form->addCheck('stipend');
        $form->setLabel('stipend', 'Stipend');

        $form->addText('pay_rate');
        $form->setLabel('pay_rate', 'Pay Rate');

        $form->addCheck('internship_default_type');
        $form->setLabel('internship_default_type', 'Internship');
        $form->setMatch('internship_default_type', true); // Internship is checked by default
        //        $form->addCheck('service_learning_type');
        //        $form->setLabel('service_learning_type', 'Service Learning');
        //        $form->addCheck('independent_study_type');
        //        $form->setLabel('independent_study_type', 'Independent Study');
        //        $form->addCheck('research_assist_type');
        //        $form->setLabel('research_assist_type', 'Research Assistant');
        $form->addCheck('student_teaching_type');
        $form->setLabel('student_teaching_type', 'Student Teaching');
        $form->addCheck('clinical_practica_type');
        $form->setLabel('clinical_practica_type', 'Clinical Practicum');
        //        $form->addCheck('special_topics_type');
        //        $form->setLabel('special_topics_type', 'Special Topics');
        //        $form->addCheck('check_other_type');
        //        $form->addText('other_type');
        //        $form->setLabel('other_type', 'Other Type');

        /*** Course Info ***/
        $subjects = Subject::getSubjects();
        $form->addSelect('course_subj', $subjects);
        $form->setLabel('course_subj', 'Subject');

        $form->addText('course_no');
        $form->setLabel('course_no', 'Number');

        $form->addText('course_sect');
        $form->setLabel('course_sect', 'Section');

        $form->addText('course_title');
        $form->setLabel('course_title', 'Title');
        $form->setMaxSize('course_title',28); // Limit to 28 chars, per Banner


        $form->addTextArea('notes');
        $form->setLabel('notes', 'Notes');

        // Label required fields
        foreach (self::$requiredFields as $field) {
            //$form->setRequired($field);
        }

        javascript('jquery');
        javascript('jquery_ui');
        javascriptMod('intern', 'spinner');
        javascriptMod('intern', 'formGoodies');

        return $form;
    }

    /**
     * Load up a form's fields with the internship's information.
     */
    private static function plugInternship(PHPWS_Form $form, Internship $i)
    {
        $vals = array();

        $a = $i->getAgency();
        $f = $i->getFacultySupervisor();
        $d = $i->getDepartment();

        // Student
        $vals['student_first_name']  = $i->first_name;
        $vals['student_middle_name'] = $i->middle_name;
        $vals['student_last_name']   = $i->last_name;
        $vals['banner']              = $i->banner;
        $vals['student_phone']       = $i->phone;
        $vals['student_email']       = $i->email;
        $vals['student_level']       = $i->level;
        $vals['grad_prog']           = $i->grad_prog;
        $vals['ugrad_major']         = $i->ugrad_major;
        $vals['student_gpa']         = $i->gpa;
        $vals['campus']              = $i->campus;

        // Student address
        $vals['student_address'] = $i->student_address;
        $vals['student_city']    = $i->student_city;
        $vals['student_state']   = $i->student_state;
        $vals['student_zip']     = $i->student_zip;

        // Emergency contact
        $vals['emergency_contact_name']     = $i->emergency_contact_name;
        $vals['emergency_contact_relation'] = $i->emergency_contact_relation;
        $vals['emergency_contact_phone']    = $i->emergency_contact_phone;

        // Agency
        $form->addHidden('agency_id', $a->id);
        $vals['agency_name'] = $a->name;
        $vals['agency_address'] = $a->address;
        $vals['agency_city'] = $a->city;
        $vals['agency_state'] = $a->state;
        $vals['agency_zip'] = $a->zip;
        $vals['agency_country'] = $a->country;
        $vals['agency_phone'] = $a->phone;
        $vals['agency_sup_first_name'] = $a->supervisor_first_name;
        $vals['agency_sup_last_name'] = $a->supervisor_last_name;
        $vals['agency_sup_title'] = $a->supervisor_title;
        $vals['agency_sup_phone'] = $a->supervisor_phone;
        $vals['agency_sup_email'] = $a->supervisor_email;
        $vals['agency_sup_fax'] = $a->supervisor_fax;
        $vals['agency_sup_address'] = $a->supervisor_address;
        $vals['agency_sup_city'] = $a->supervisor_city;
        $vals['agency_sup_state'] = $a->supervisor_state;
        $vals['agency_sup_zip'] = $a->supervisor_zip;
        $vals['agency_sup_country'] = $a->supervisor_country;
        $vals['copy_address'] = $a->address_same_flag == 't';

        // Faculty supervisor
        $form->addHidden('supervisor_id', $f->id);
        $vals['supervisor_first_name'] = $f->first_name;
        $vals['supervisor_last_name'] = $f->last_name;
        $vals['supervisor_email'] = $f->email;
        $vals['supervisor_phone'] = $f->phone;


        // Internship
        $form->addHidden('internship_id', $i->id);
        $vals['start_date'] = $i->start_date ? date('m/d/Y', $i->start_date) : null;
        $vals['end_date'] = $i->end_date ? date('m/d/Y', $i->end_date) : null;
        $vals['credits'] = $i->credits;
        $vals['avg_hours_week'] = $i->avg_hours_week;
        //$vals['other_type'] = $i->other_type;
        //$vals['notes'] = $i->notes;
        $vals['loc_address'] = $i->loc_address;
        $vals['loc_city'] = $i->loc_city;
        $vals['loc_state'] = $i->loc_state;
        $vals['loc_zip'] = $i->loc_zip;
        $vals['loc_province'] = $i->loc_province;
        $vals['loc_country'] = $i->loc_country;

        //$vals['course_subj'] = $i->course_subj;
        $form->setMatch('course_subj', $i->course_subj);
        $vals['course_no'] = $i->course_no;
        $vals['course_sect'] = $i->course_sect;
        $vals['course_title'] = $i->course_title;


        // Department
        $vals['department'] = $i->department_id;

        // Other internship details
        if ($i->domestic) {
            $form->setMatch('location', 'domestic');
        } else {
            $form->setMatch('location', 'internat');
        }
        if ($i->paid) {
            $form->setMatch('payment', 'paid');
            $form->setMatch('stipend', $i->stipend);
        } else {
            $form->setMatch('payment', 'unpaid');
        }

        $vals['pay_rate'] = $i->pay_rate;
        
        if($i->oied_certified){
            $form->setMatch('oied_certified', true);
            $form->setValue('oied_certified_hidden', 'true');
        }else{
            $form->setValue('oied_certified_hidden', 'false');
        }

        $form->setMatch('term', $i->term);
        $form->setMatch('internship_default_type', $i->internship);
        $form->setMatch('student_teaching_type', $i->student_teaching);
        $form->setMatch('clinical_practica_type', $i->clinical_practica);

        // Plug
        $form->plugIn($vals);
    }

}

?>
