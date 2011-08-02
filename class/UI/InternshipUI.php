<?php

/**
 * This class holds the form for adding/editing an internship.
 */
PHPWS_Core::initModClass('intern', 'UI/UI.php');

class InternshipUI implements UI {

    /**
     * @editor Matt Mcnaney
     * The following fields were removed on 7/21/2011
     * 'supervisor_first_name', 'supervisor_last_name', 'supervisor_email',
     * 'supervisor_phone', 'start_date', 'end_date',
     * 'agency_sup_phone','agency_address','agency_zip', 'agency_sup_zip',
     * 'agency_phone',  'agency_city', 'agency_sup_state', 'agency_sup_first_name',
     * 'agency_sup_last_name', 'agency_sup_email', 'agency_sup_address',
     * 'agency_sup_city', 'department'
     */
    public static $requiredFields = array('student_first_name', 'student_last_name',
        'banner', 'student_phone', 'student_email', 'agency_name', 'agency_state',
        'ugrad_major', 'term', 'loc_state');

    public static function display()
    {
        PHPWS_Core::initModClass('intern', 'Internship.php');
        PHPWS_Core::initModClass('intern', 'Intern_Document.php');
        PHPWS_Core::initModClass('intern', 'Intern_Folder.php');
        PHPWS_Core::initModClass('intern', 'Agency.php');

        $tpl = array();

        if (isset($_REQUEST['id'])) {
            /* Attempting to edit internship */
            try {
                $internship = new Internship($_REQUEST['id']);
                if ($internship->id == 0) {
                    /* Intership failed to load */
                    NQ::simple('intern', INTERN_ERROR, 'Failed to get internship.');
                    return false;
                }
                $form = self::getInternshipForm($internship);
                $tpl['PDF'] = PHPWS_Text::moduleLink('Generate Contract', 'intern', array('action' => 'pdf', 'id' => $internship->id));
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
            } catch (Exception $e) {
                NQ::simple('intern', INTERN_ERROR, $e->getMessage());
                return false;
            }
        } else {
            /* Show form with empty fields. */
            $form = self::getInternshipForm();
            // Show a disabled button in document list if we are adding an internship.
            $tpl['UPLOAD_DOC'] = "<input type='button' disabled='disabled' class='disabled-button' title='Must save internship first.' value='Add Document'/>";
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
                $form->setClass($m, 'missing');
            }

            /* Plug old values back into form fields. */
            $form->plugIn($_GET);

            // If internship is being edited... 
            if (isset($_REQUEST['internship_id'])) {
                /* Re-add hidden fields with object ID's */
                $i = new Internship($_GET['internship_id']);
                $s = $i->getStudent();
                $a = $i->getAgency();
                $f = $i->getFacultySupervisor();
                $form->addHidden('student_id', $s->id);
                $form->addHidden('agency_id', $a->id);
                $form->addHidden('supervisor_id', $f->id);
                $form->addHidden('internship_id', $i->id);
            }
        }

        $form->mergeTemplate($tpl);
        Layout::addPageTitle('Add Internship');
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'add_internship.tpl');
    }

    /**
     * Build the form for adding/editing an internship.
     *
     * If there is an Internship obj passed as parameter
     * then fill in the form with that Internship's fields.
     */
    public static function getInternshipForm(Internship $i=NULL)
    {
        PHPWS_Core::initModClass('intern', 'Term.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'Major.php');
        PHPWS_Core::initModClass('intern', 'GradProgram.php');

        $form = new PHPWS_Form('internship');
        if (!is_null($i)) {
            $s = $i->getStudent();
            $a = $i->getAgency();
        } else {
            $i = new Internship;
            $a = new Agency;
        }
        $form->setAction('index.php?module=intern&action=add_internship');
        $form->addSubmit('submit', 'Save');

        /**
         * Student fields
         */
        if (!$i->approved) {
            $form->addCheckbox('approved');
            $form->setLabel('approved', 'Internship approved by Dean');
        } else {
            $approved_on = "Approved by {$i->approved_by} on " . date('g:ia, M j, Y', $i->approved_on);
            if (Current_User::isDeity()) {
                $approved_on .= ' <a href="index.php?module=intern&action=unapprove&id='
                . $i->id  . '&authkey=' . Current_User::getAuthKey() . '">Unapprove</a>';
            }
            $form->addTplTag('APPROVED_BY_ON', $approved_on);
            
        }
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
        $form->setLabel('student_email', 'Email');
        if (isset($s))
            $majors = Major::getMajorsAssoc($s->ugrad_major);
        else
            $majors = Major::getMajorsAssoc();
        $form->addSelect('ugrad_major', $majors);
        $form->setLabel('ugrad_major', 'Undergraduate Major');
        if (isset($s))
            $progs = GradProgram::getGradProgsAssoc($s->grad_prog);
        else
            $progs = GradProgram::getGradProgsAssoc();
        $form->addSelect('grad_prog', $progs);
        $form->setLabel('grad_prog', 'Graduate Program');

        /**
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

        /**
         * Agency supervisor info.
         */
        $db = new PHPWS_DB('intern_state');
        $db->addWhere('active', 1);
        $db->addColumn('abbr');
        $db->addColumn('full_name');
        $db->setIndexBy('abbr');
        // get backwards because we flip it
        $db->addOrder('full_name desc');
        $states = $db->select('col');
        if (empty($states)) {
            exit(sprintf('Please go under admin options and <a href="index.php?module=intern&action=edit_states&authkey=%s">add allowed states.</a>', Current_User::getAuthKey()));
        }
        $states[-1] = 'Select a state';
        $states = array_reverse($states, true);
        $form->addText('agency_name');
        $form->setLabel('agency_name', 'Name');
        $form->addText('agency_address');
        $form->setLabel('agency_address', 'Address');
        $form->addText('agency_city');
        $form->setLabel('agency_city', 'City');
        $form->addSelect('agency_state', $states);
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
        $form->addText('agency_sup_first_name');
        $form->setLabel('agency_sup_first_name', 'First Name');
        $form->addText('agency_sup_last_name');
        $form->setLabel('agency_sup_last_name', 'Last Name');
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
        $form->addSelect('agency_sup_state', $states);
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

        /**
         * Internship details.
         */
        $form->addSelect('term', Term::getTermsAssoc());
        $form->setLabel('term', 'Select Term');
        $form->addText('start_date');
        $form->setLabel('start_date', 'Start Date');
        $form->addText('end_date');
        $form->setLabel('end_date', 'End Date');
        $form->addText('credits');
        $form->setLabel('credits', 'Credits');
        $form->addText('avg_hours_week');
        $form->setLabel('avg_hours_week', 'Average Hours per Week');
        $loc = array('domestic' => 'Domestic', 'internat' => 'International');
        $form->addRadioAssoc('location', $loc);
        $form->setMatch('location', 'domestic'); // Default to domestic
        $pay = array('unpaid' => 'Unpaid', 'paid' => 'Paid');
        $form->addRadioAssoc('payment', $pay);
        $form->setMatch('payment', 'unpaid'); // Default to unpaid
        $form->addCheck('stipend');
        $form->setLabel('stipend', 'Stipend');
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
        $form->setLabel('clinical_practica_type', 'Clinical Practica');
//        $form->addCheck('special_topics_type');
//        $form->setLabel('special_topics_type', 'Special Topics');
//        $form->addCheck('check_other_type');
//        $form->addText('other_type');
//        $form->setLabel('other_type', 'Other Type');

        /**
         * Internship location
         */
        $form->addText('loc_address');
        $form->setLabel('loc_address', 'Address');
        $form->addText('loc_city');
        $form->setLabel('loc_city', 'City');
        $form->addText('loc_country');
        $form->setLabel('loc_country', 'Country');
        $form->addSelect('loc_state', $states);
        $form->setLabel('loc_state', 'State');
        $form->addText('loc_zip');
        $form->setLabel('loc_zip', 'Zip');


        $form->addText('course_subj');
        $form->setLabel('course_subj', 'Subject');

        $form->addText('course_no');
        $form->setLabel('course_no', 'Number');

        $form->addText('course_sect');
        $form->setLabel('course_sect', 'Section');

        $form->addText('course_title');
        $form->setLabel('course_title', 'Title');


        $form->addTextArea('notes');
        $form->setLabel('notes', 'Notes');

        // Label required fields
        foreach (self::$requiredFields as $field) {
            $form->setRequired($field);
        }

        javascript('jquery');
        javascript('jquery_ui');
        javascriptMod('intern', 'formGoodies');

        return $form;
    }

    /**
     * Load up a form's fields with the internship's information.
     */
    private static function plugInternship(PHPWS_Form $form, Internship $i)
    {

        $vals = array();

        $s = $i->getStudent();
        $a = $i->getAgency();
        $f = $i->getFacultySupervisor();
        $d = $i->getDepartment();


        // Student
        $form->addHidden('student_id', $s->id);
        $vals['student_first_name'] = $s->first_name;
        $vals['student_middle_name'] = $s->middle_name;
        $vals['student_last_name'] = $s->last_name;
        $vals['banner'] = $s->banner;
        $vals['student_phone'] = $s->phone;
        $vals['student_email'] = $s->email;
        $vals['grad_prog'] = $s->grad_prog;
        $vals['ugrad_major'] = $s->ugrad_major;

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
        $vals['other_type'] = $i->other_type;
        $vals['notes'] = $i->notes;
        $vals['loc_address'] = $i->loc_address;
        $vals['loc_city'] = $i->loc_city;
        $vals['loc_country'] = $i->loc_country;
        $vals['loc_state'] = $i->loc_state;
        $vals['loc_zip'] = $i->loc_zip;

        $vals['course_subj'] = $i->course_subj;
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
        $form->setMatch('term', $i->term);
        $form->setMatch('internship_default_type', $i->internship);
        $form->setMatch('service_learning_type', $i->service_learn);
        $form->setMatch('independent_study_type', $i->independent_study);
        $form->setMatch('research_assist_type', $i->research_assist);
        $form->setMatch('student_teaching_type', $i->student_teaching);
        $form->setMatch('clinical_practica_type', $i->clinical_practica);
        $form->setMatch('special_topics_type', $i->special_topics);
        if ($i->other_type != '' && !is_null($i->other_type)) {
            $form->setMatch('check_other_type', true);
        }

        // Plug 
        $form->plugIn($vals);
    }

}

?>