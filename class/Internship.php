<?php

/**
 * Internship
 *
 * Forms relationship between a student, department, and agency.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */
PHPWS_Core::initModClass('intern', 'Model.php');
PHPWS_Core::initModClass('intern', 'Email.php');
PHPWS_Core::initModClass('intern', 'Term.php');
PHPWS_Core::initModClass('intern', 'Major.php');

class Internship extends Model {

    public $agency_id;
    public $faculty_supervisor_id;
    public $department_id;

    public $state;
    public $oied_certified;


    // Student data
    public $banner;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $phone;
    public $email;
    public $level;
    public $grad_prog;
    public $ugrad_major;
    public $gpa;
    public $campus;

    public $student_address;
    public $student_city;
    public $student_state;
    public $student_zip;

    public $emergency_contact_name;
    public $emergency_contact_relation;
    public $emergency_contact_phone;

    // Location data
    public $domestic;
    public $international;

    public $loc_address;
    public $loc_city;
    public $loc_state;
    public $loc_zip;
    public $loc_province;
    public $loc_country;

    public $term;
    public $start_date = 0;
    public $end_date = 0;
    public $credits;
    public $avg_hours_week;

    public $course_subj;
    public $course_no;
    public $course_sect;
    public $course_title;

    public $paid;
    public $unpaid;
    public $stipend;
    public $pay_rate;

    public $internship = 0;
    public $student_teaching = 0;
    public $clinical_practica = 0;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_internship');
    }

    /**
     * @Override Model::getCSV
     * Get a CSV formatted for for this internship.
     */
    public function getCSV()
    {
        $csv = array();

        // Student data
        $csv['Banner'] = $this->banner;
        $csv['Student First Name']  = $this->first_name;
        $csv['Student Middle Name'] = $this->middle_name;
        $csv['Student Last Name']   = $this->last_name;
        $csv['Student Phone']       = $this->phone;
        $csv['Student Email']       = $this->email;
        
        // Internship Data
        $csv['Status']                 = $this->state;
        $csv['Start Date']             = $this->getStartDate(true);
        $csv['End Date']               = $this->getEndDate(true);
        $csv['Term']                   = Term::rawToRead($this->term, false);
        $csv['Credits']                = $this->credits;
        $csv['Average Hours Per Week'] = $this->avg_hours_week;
        $csv['Domestic']               = $this->domestic == 1 ? 'Yes' : 'No';
        $csv['International']          = $this->international == 1 ? 'Yes' : 'No';
        $csv['Paid']                   = $this->paid == 1 ? 'Yes' : 'No';
        $csv['Stipend']                = $this->stipend == 1 ? 'Yes' : 'No';
        $csv['Unpaid']                 = $this->unpaid == 1 ? 'Yes' : 'No';
        $csv['Internship']             = $this->internship == 1 ? 'Yes' : 'No';
        $csv['Student Teaching']       = $this->student_teaching == 1 ? 'Yes' : 'No';
        $csv['Clinical Practica']      = $this->clinical_practica == 1 ? 'Yes' : 'No';

        // Location data
        $csv['Location Address']       = $this->loc_address;
        $csv['Location State']         = $this->loc_state;
        $csv['Location City']          = $this->loc_city;
        $csv['Location Zip']           = $this->loc_zip;
        $csv['Course Subject']         = $this->course_subj;
        $csv['Course Number']          = $this->course_no;
        $csv['Course Section']         = $this->course_sect;
        $csv['Course Title']           = $this->course_title;

        /*
         * TODO: Fix this so that the columns are always present in the exported csv, even if they're empty
        if($major != null)
            $csv = array_merge($csv, $major->getCSV());
        else
            $csv = array_merge($csv, Major::getEmptyCSV());
        if($prog != null)
            $csv = array_merge($csv, $prog->getCSV());
        else
            $csv = array_merge($csv, GradProgram::getEmptyCSV());
        */

        // Get external objects
        $a = $this->getAgency();
        $f = $this->getFacultySupervisor();
        $d = $this->getDepartment();

        // Merge data from other objects.
        $csv = array_merge($csv, $a->getCSV());
        $csv = array_merge($csv, $f->getCSV());
        $csv = array_merge($csv, $d->getCSV());

        return $csv;
    }


    /**
     * Get a Major object for the major of this student.
     */
    public function getUgradMajor()
    {
        PHPWS_Core::initModClass('intern', 'Major.php');
        if(!is_null($this->ugrad_major) && $this->ugrad_major != 0){
            return new Major($this->ugrad_major);
        }else{
            return null;
        }
    }

    /**
     * Get a GradProgram object for the graduate program of this student.
     */
    public function getGradProgram()
    {
        PHPWS_Core::initModClass('intern', 'GradProgram.php');
        if(!is_null($this->grad_prog) && $this->grad_prog != 0){
            return new GradProgram($this->grad_prog);
        }else{
            return null;
        }
    }

    /**
     * Get the Agency object associated with this internship.
     */
    public function getAgency()
    {
        PHPWS_Core::initModClass('intern', 'Agency.php');
        return new Agency($this->agency_id);
    }

    /**
     * Get the Faculty Supervisor object associated with this internship.
     */
    public function getFacultySupervisor()
    {
        PHPWS_Core::initModClass('intern', 'FacultySupervisor.php');
        return new FacultySupervisor($this->faculty_supervisor_id);
    }

    /**
     * Get the Department object associated with this internship.
     */
    public function getDepartment()
    {
        PHPWS_Core::initModClass('intern', 'Department.php');
        return new Department($this->department_id);
    }

    public function getSubject()
    {
        PHPWS_Core::initModClass('intern', 'Subject.php');
        return new Subject($this->course_subj);
    }

    /**
     * Get Document objects associated with this internship.
     */
    public function getDocuments()
    {
        PHPWS_Core::initModClass('intern', 'Intern_Document.php');
        $db = Intern_Document::getDB();
        $db->addWhere('internship_id', $this->id);
        return $db->getObjects('Intern_Document');
    }

    /**
     * Get the concatenated first name, middle name/initial, and last name.
     */
    public function getFullName()
    {
        $name = $this->first_name . ' ';
        // Middle name is not required. If no middle name as input then
        // this will not show the extra space for padding between middle and last name.
        $name .= (isset($this->middle_name) && $this->middle_name != '') ? $this->middle_name . ' ': null;
        $name .= $this->last_name;
        return $name;
    }

    /**
     * Get a comma separated list of the types for
     * this internship.
     */
    public function getReadableTypes()
    {
        $types = array();

        if ($this->internship == 1) {
            $types[] = 'Internship';
        }
        if ($this->student_teaching == 1) {
            $types[] = 'Student Teaching';
        }
        if ($this->clinical_practica == 1) {
            $types[] = 'Clinical Practica';
        }

        return implode(', ', $types);
    }

    /**
     * Get formatted dates.
     */
    public function getStartDate($formatted=false)
    {
        if (!$this->start_date) {
            return null;
        }
        if ($formatted) {
            return date('F j, Y', $this->start_date);
        } else {
            return $this->start_date;
        }
    }

    public function getEndDate($formatted=false)
    {
        if (!$this->end_date) {
            return null;
        }
        if ($formatted) {
            return date('F j, Y', $this->end_date);
        } else {
            return $this->end_date;
        }
    }

    /**
     * Is this internship domestic?
     *
     * @return bool True if this is a domestic internship, false otherwise.
     */
    public function isDomestic()
    {
        if ($this->domestic == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is this internship International?
     *
     * @return bool True if this is an international internship, false otherwise.
     */
    public function isInternational()
    {
        if($this->international == 1) {
            return false;
        } else {
            return false;
        }
    }

    /**
     * Returns the Banner ID of this student.
     *
     * @return string Banner ID
     */
    public function getBannerId(){
        return $this->banner;
    }

    public function getStateName()
    {
        return $this->state;
    }

    public function setState(WorkflowState $state){
        $this->state = $state->getName();
    }

    /**
     * Row tags for DBPager
     */
    public function getRowTags()
    {
        PHPWS_Core::initModClass('intern', 'Term.php');

        $tags = array();

        // Get objects associated with this internship.
        $a = $this->getAgency();
        $f = $this->getFacultySupervisor();
        $d = $this->getDepartment();

        // Student info.
        $tags['STUDENT_NAME'] = $this->getFullName();
        $tags['STUDENT_BANNER'] = $this->getBannerId();

        // Agency info.
        $tags['AGENCY_NAME'] = $a->name;

        // Dept. info
        $tags['DEPT_NAME'] = $d->name;

        // Faculty info.
        $tags['FACULTY_NAME'] = $f->getFullName();
        

        // Internship info.
        $tags['START_DATE'] = $this->getStartDate();
        $tags['END_DATE'] = $this->getEndDate();
        $tags['TERM'] = Term::rawToRead($this->term);
        $tags['ID'] = $this->id;

        $tags['EDIT'] = PHPWS_Text::moduleLink('Edit', 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));
        $tags['PDF'] = PHPWS_Text::moduleLink('Generate Contract', 'intern', array('action' => 'pdf', 'id' => $this->id));

        return $tags;
    }

    /**
     * Get internship types in an associative array.
     */
    public static function getTypesAssoc()
    {
        return array('internship' => 'Internship',
                'student_teaching' => 'Student Teaching',
                'clinical_practica' => 'Clinical Practica In Health Fields');
    }

    /**
     * Create a new internship. Save to DB.
     */
    public static function addInternship()
    {
        PHPWS_Core::initModClass('intern', 'Agency.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'FacultySupervisor.php');

        /**************
         * Sanity Checks
        */

        // Required fields check
        $missing = self::checkRequest();
        if (!is_null($missing) && !empty($missing)) {
            // checkRequest returned some missing fields.
            $url = 'index.php?module=intern&action=edit_internship';
            $url .= '&missing=' . implode('+', $missing);
            // Throw in values fields the user typed in
            foreach ($_POST as $key => $val) {
                $url .= "&$key=$val";
            }
            NQ::simple('intern', INTERN_ERROR, 'Please fill in the highlighted fields.');
            NQ::close();
            return PHPWS_Core::reroute($url);
        }

        // Sanity check the Banner ID
        if(!preg_match('/^\d{9}$/', $_REQUEST['banner'])){
            $url = 'index.php?module=intern&action=edit_internship&missing=banner';
            // Throw in values fields the user typed in
            foreach ($_POST as $key => $val) {
                $url .= "&$key=$val";
            }
            NQ::simple('intern', INTERN_ERROR, "The Banner ID you entered is not valid. No changes were saved. The student's Banner ID should be nine digits only (no letters, spaces, or punctuation).");
            NQ::close();
            return PHPWS_Core::reroute($url);
        }

        // Course start date must be before end date
        if(!empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])){
            $start = strtotime($_REQUEST['start_date']);
            $end   = strtotime($_REQUEST['end_date']);

            if ($start > $end) {
                $url = 'index.php?module=intern&action=edit_internship&missing=start_date+end_date';
                // Throw in values fields the user typed in
                unset($_POST['start_date']);
                unset($_POST['end_date']);
                foreach ($_POST as $key => $val) {
                    $url .= "&$key=$val";
                }
                NQ::simple('intern', INTERN_WARNING, 'The internship start date must be before the end date.');
                NQ::close();
                return PHPWS_Core::reroute($url);
            }
        }

        PHPWS_DB::begin();

        // Create/Save agency
        $agency = new Agency();
        if (isset($_REQUEST['agency_id'])) {
            // User is editing internship
            try {
                $agency = new Agency($_REQUEST['agency_id']);
            } catch (Exception $e) {
                // Rollback and re-throw the exception so that admins gets an email
                PHPWS_DB::rollback();
                throw $e;
            }
        }
        $agency->name = $_REQUEST['agency_name'];
        $agency->address = $_REQUEST['agency_address'];
        $agency->city = $_REQUEST['agency_city'];
        $agency->zip = $_REQUEST['agency_zip'];
        $agency->phone = $_REQUEST['agency_phone'];

        if ($_REQUEST['location'] == 'internat') {
            /* Location is INTERNATIONAL. Country is required. Province was typed in. */
            $agency->state = $_REQUEST['agency_state'];
            $agency->country = $_REQUEST['agency_country'];
            $agency->supervisor_state = $_REQUEST['agency_state'];
            $agency->supervisor_country = $_REQUEST['agency_sup_country'];
        } else {
            /* Location is DOMESTIC. Country is U.S. State was chosen from drop down */
            $agency->state = $_REQUEST['agency_state'] == -1 ? null : $_REQUEST['agency_state'];
            $agency->country = 'United States';
            $agency->supervisor_state = $_REQUEST['agency_sup_state'] == -1 ? null : $_REQUEST['agency_sup_state'];
            $agency->supervisor_country = 'United States';
        }

        $agency->supervisor_first_name = $_REQUEST['agency_sup_first_name'];
        $agency->supervisor_last_name = $_REQUEST['agency_sup_last_name'];
        $agency->supervisor_title = $_REQUEST['agency_sup_title'];
        $agency->supervisor_phone = $_REQUEST['agency_sup_phone'];
        $agency->supervisor_email = $_REQUEST['agency_sup_email'];
        $agency->supervisor_fax = $_REQUEST['agency_sup_fax'];
        $agency->supervisor_address = $_REQUEST['agency_sup_address'];
        $agency->supervisor_city = $_REQUEST['agency_sup_city'];
        $agency->supervisor_zip = $_REQUEST['agency_sup_zip'];
        $agency->address_same_flag = isset($_REQUEST['copy_address']) ? 't' : 'f';

        try {
            $agencyId = $agency->save();
        } catch (Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            PHPWS_DB::rollback();
            throw $e;
        }

        // Create/Save Faculty supervisor
        $faculty = new FacultySupervisor();
        if (isset($_REQUEST['supervisor_id'])) {
            // User is editing internship
            try {
                $faculty = new FacultySupervisor($_REQUEST['supervisor_id']);
            } catch (Exception $e) {
                // Rollback and re-throw the exception so that admins gets an email
                PHPWS_DB::rollback();
                throw $e;
            }
        }

        $faculty->first_name = $_REQUEST['supervisor_first_name'];
        $faculty->last_name = $_REQUEST['supervisor_last_name'];
        $faculty->email = $_REQUEST['supervisor_email'];
        $faculty->phone = $_REQUEST['supervisor_phone'];
        $faculty->department_id = $_REQUEST['department'];

        try {
            $facultyId = $faculty->save();
        } catch (Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            PHPWS_DB::rollback();
            throw $e;
        }

        // Create/Save internship.
        $i = new Internship();
        if (isset($_REQUEST['internship_id'])) {
            // User is editing internship
            try {
                $i = new Internship($_REQUEST['internship_id']);
            } catch (Exception $e) {
                // Rollback and re-throw the exception so that admins gets an email
                PHPWS_DB::rollback();
                throw $e;
            }
        }

        $i->term = $_REQUEST['term'];
        $i->agency_id = $agencyId;
        $i->faculty_supervisor_id = $facultyId;
        $i->department_id = $_REQUEST['department'];
        $i->start_date = !empty($_REQUEST['start_date']) ? strtotime($_REQUEST['start_date']) : 0;
        $i->end_date = !empty($_REQUEST['end_date']) ? strtotime($_REQUEST['end_date']) : 0;
        $credits = (int) $_REQUEST['credits'];
        $i->credits = $credits ? $credits : NULL;
        $avg_hours_week = (int) $_REQUEST['avg_hours_week'];
        $i->avg_hours_week = $avg_hours_week ? $avg_hours_week : NULL;
        $i->domestic = $_REQUEST['location'] == 'domestic';
        $i->international = $_REQUEST['location'] == 'internat';
        $i->paid = $_REQUEST['payment'] == 'paid';
        $i->stipend = isset($_REQUEST['stipend']) && $i->paid;
        $i->unpaid = $_REQUEST['payment'] == 'unpaid';
        $i->pay_rate = $_REQUEST['pay_rate'];
        $i->internship = isset($_REQUEST['internship_default_type']);
        $i->student_teaching = isset($_REQUEST['student_teaching_type']);
        $i->clinical_practica = isset($_REQUEST['clinical_practica_type']);

        $i->loc_address = strip_tags($_POST['loc_address']);
        $i->loc_city = strip_tags($_POST['loc_city']);
        if ($_POST['loc_state'] != '-1') {
            $i->loc_state = strip_tags($_POST['loc_state']);
        } else {
            $i->loc_state = null;
        }
        $i->loc_zip = strip_tags($_POST['loc_zip']);
        $i->loc_province = $_POST['loc_province'];
        $i->loc_country = strip_tags($_POST['loc_country']);

        if($_POST['course_subj'] != '-1'){
            $i->course_subj = strip_tags($_POST['course_subj']);
        }else{
            $i->course_subj = null;
        }

        $i->course_no = strip_tags($_POST['course_no']);
        $i->course_sect = strip_tags($_POST['course_sect']);
        $i->course_title = strip_tags($_POST['course_title']);

        // Student Information
        $i->first_name = $_REQUEST['student_first_name'];
        $i->middle_name = $_REQUEST['student_middle_name'];
        $i->last_name = $_REQUEST['student_last_name'];
        $i->banner = $_REQUEST['banner'];
        $i->phone = $_REQUEST['student_phone'];
        $i->email = $_REQUEST['student_email'];
        $i->level = $_REQUEST['student_level'];
        $i->grad_prog = $_REQUEST['grad_prog'] == -1 ? null : $_REQUEST['grad_prog'];
        $i->ugrad_major = $_REQUEST['ugrad_major'] == -1 ? null : $_REQUEST['ugrad_major'];
        $i->gpa = $_REQUEST['student_gpa'];
        $i->campus = $_REQUEST['campus'];

        $i->student_address = $_REQUEST['student_address'];
        $i->student_city = $_REQUEST['student_city'];
        $i->student_state = $_REQUEST['student_state'];
        $i->student_zip = $_REQUEST['student_zip'];

        $i->emergency_contact_name = $_REQUEST['emergency_contact_name'];
        $i->emergency_contact_relation = $_REQUEST['emergency_contact_relation'];
        $i->emergency_contact_phone = $_REQUEST['emergency_contact_phone'];

        /************
         * OIED Certification
        */
        // Check if this has changed from non-certified->certified so we can log it later
        if($i->oied_certified == 0 && $_POST['oied_certified_hidden'] == 'true'){
            // note the change for later
            $oiedCertified = true;
        }

        if($_POST['oied_certified_hidden'] == 'true'){
            $i->oied_certified = 1;
        }else if($_POST['oied_certified_hidden'] == 'false'){
            $i->oied_certified = 0;
        }else{
            $i->oied_certified = 0;
        }

        // If we don't have a state and this is a new internship,
        // the set an initial state
        if($i->id == 0 && is_null($i->state)){
            PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');
            $state = WorkflowStateFactory::getState('CreationState');
            $i->setState($state); // Set this initial value
        }

        try {
            $i->save();
        } catch (Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            PHPWS_DB::rollback();
            throw $e;
        }

        /***************************
         * State/Workflow Handling *
        ***************************/
        PHPWS_Core::initModClass('intern', 'WorkflowController.php');
        PHPWS_Core::initModClass('intern', 'WorkflowTransitionFactory.php');
        $t = WorkflowTransitionFactory::getTransitionByName($_POST['workflow_action']);
        $workflow = new WorkflowController($i, $t);
        $workflow->doTransition(isset($_POST['notes'])?$_POST['notes']:null);

        // Create a ChangeHisotry for the OIED certification.
        if($oiedCertified){
            $currState = WorkflowStateFactory::getState($i->getStateName());
            $ch = new ChangeHistory($i, Current_User::getUserObj(), time(), $currState, $currState, 'Certified by OIED');
            $ch->save();
        }

        PHPWS_DB::commit();

        $workflow->doNotification();

        if (isset($_REQUEST['internship_id'])) {
            // Show message if user edited internship
            NQ::simple('intern', INTERN_SUCCESS, 'Saved internship for ' . $i->getFullName());
            NQ::close();
            return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&internship_id=' . $i->id);
        } else {
            NQ::simple('intern', INTERN_SUCCESS, 'Added internship for ' . $i->getFullName());
            NQ::close();
            return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&internship_id=' . $i->id);
        }
    }

    /**
     * Check that required fields are in the REQUEST.
     */
    private static function checkRequest()
    {
        PHPWS_Core::initModClass('intern', 'UI/InternshipUI.php');
        $vals = null;

        foreach (InternshipUI::$requiredFields as $field) {
            /* If not set or is empty (For text fields) */
            if (!isset($_REQUEST[$field]) || $_REQUEST[$field] == '') {
                $vals[] = $field;
            }
        }

        /* Required select boxes should not equal -1 */

        if (!isset($_REQUEST['department']) ||
                $_REQUEST['department'] == -1) {
            $vals[] = 'department';
        }

        if(isset($_REQUEST['student_level']) && $_REQUEST['student_level'] == 'ugrad' &&
                (!isset($_REQUEST['ugrad_major']) || $_REQUEST['ugrad_major'] == -1)){
            $vals[] = 'ugrad_major';
        }

        if(isset($_REQUEST['student_level']) && $_REQUEST['student_level'] == 'grad' &&
                (!isset($_REQUEST['grad_prog']) || $_REQUEST['grad_prog'] == -1)){
            $vals[] = 'grad_prog';
        }

        if (!isset($_REQUEST['term']) ||
                $_REQUEST['term'] == -1) {
            $vals[] = 'term';
        }

        // Make sure a location (domestic vs. intl) is set
        if(!isset($_REQUEST['location'])){
            // If not, make the user select it
            $vals[] = 'location';
        }else{
            // If so, check the state/country appropriately
            if($_REQUEST['location'] == 'domestic'){
                // Check internshp state
                if ($_REQUEST['loc_state'] == -1) {
                    $vals[] = 'loc_state';
                }
            }else{
                if(!isset($_REQUEST['loc_country'])){
                    $vals[] = 'loc_country';
                }
            }
        }


        /**
         * Funky stuff here for location.
         * If location is DOMESTIC then State and Zip are required.
         * If location is INTERNATIONAL then state and zip are not required
         * and are set to null though Country is required.
         */
        /**
         * Updated 7/26/2011 - several requirements loosened
         */
        if (!isset($_REQUEST['location'])) {
            $vals[] = 'location';
        } elseif ($_REQUEST['location'] == 'domestic') {
        }

        return $vals;
    }

    /**
     * Generate a PDF Report for this internship.
     * Filename is returned.
     */
    public function getPDF()
    {
        require_once(PHPWS_SOURCE_DIR . 'mod/intern/pdf/fpdf.php');
        require_once(PHPWS_SOURCE_DIR . 'mod/intern/pdf/fpdi.php');
        PHPWS_Core::initModClass('intern', 'Term.php');

        $pdf = new FPDI('P', 'mm', 'Letter');
        $a = $this->getAgency();
        $d = $this->getDepartment();
        $f = $this->getFacultySupervisor();
        $m = $this->getUgradMajor();
        $g = $this->getGradProgram();
        $subject = $this->getSubject();

        $pagecount = $pdf->setSourceFile(PHPWS_SOURCE_DIR . 'mod/intern/pdf/contract-flat.pdf');
        $tplidx = $pdf->importPage(1);
        $pdf->addPage();
        $pdf->useTemplate($tplidx);

        $pdf->setFont('Times', null, 10);

        /*
         * Internship information
        */

        //        $types = $this->getReadableTypes();
        //        // If the width of the string of types is greater than 139 (found by trial/error)
        //        // then we need to correct the header's alignment and left border.
        //        if ($pdf->getStringWidth($types) > 139) {
        //            $pdf->cell(15, 10, 'Type:', 1);
        //        } else {
        //            $pdf->cell(15, 5, 'Type:', 1);
        //        }
        //        $pdf->multiCell(175, 5, $types, 1);

        // Term
        $pdf->setXY(128, 39);
        $pdf->cell(60, 5, Term::rawToRead($this->term, false));

        /* Department */
        //$pdf->setFont('Times', null, 10);
        $pdf->setXY(171, 40);
        $pdf->MultiCell(31, 3, $subject->abbreviation);

        // Subject and Course #
        //$pdf->setFont('Times', null, 8);
        $pdf->setXY(132, 44);
        $course_info = $this->course_no;
        $pdf->cell(59, 5, $course_info);

        // Section #
        $pdf->setXY(178, 44);
        $pdf->cell(25, 5, $this->course_sect);

        /*
         $pdf->setXY(132, 39);
        if (!is_null($m)) {
        $major = $m->getName();
        } else {
        $major = 'N/A';
        }
        $pdf->cell(73, 5, $major);
        */

        //$pdf->setFont('Times', null, 10);
        $pdf->setXY(140, 48);
        $pdf->cell(73, 6, $this->course_title);

        /* Location */
        if($this->domestic == 1){
            $pdf->setXY(85, 62);
            $pdf->cell(12, 5, 'X');
        }
        if($this->international == 1){
            $pdf->setXY(156, 62);
            $pdf->cell(12, 5, 'X');
        }

        /**
         * Student information.
         */
        $pdf->setXY(40, 77);
        $pdf->cell(55, 5, $this->getFullName());

        $pdf->setXY(173,77);
        $pdf->cell(54,5, $this->gpa);

        $pdf->setXY(32, 83);
        $pdf->cell(42, 5, $this->banner);

        $pdf->setXY(41, 88);
        $pdf->cell(54, 5, $this->email . '@appstate.edu');

        $pdf->setXY(113, 88);
        $pdf->cell(54, 5, $this->phone);

        /* Student Address */
        $pdf->setXY(105, 83);
        $studentAddress = "";
        if(!empty($this->student_address)){
            $studentAddress .= ($this->student_address . ", ");
        }
        if(!empty($this->student_city)){
            $studentAddress .= ($this->student_city . ", ");
        }
        if(!empty($this->student_state) && $this->student_state != '-1'){
            $studentAddress .= ($this->student_state . " ");
        }
        if(!empty($this->student_zip)){
            $studentAddress .= $this->student_zip;
        }
        $pdf->cell(54, 5, $studentAddress);


        /* Payment */
        if($this->paid == 1){
            $pdf->setXY(160, 88);
            $pdf->cell(10,5, 'X');
        }

        if($this->unpaid == 1){
            $pdf->setXY(190, 88);
            $pdf->cell(10,5,'X');
        }

        /* Start/end dates */
        //$pdf->setFont('Times', null, 10);
        $pdf->setXY(50, 93);
        $pdf->cell(25, 5, $this->getStartDate(true));
        $pdf->setXY(93, 93);
        $pdf->cell(25, 5, $this->getEndDate(true));

        /* Hours */
        $pdf->setXY(193, 93);
        $pdf->cell(12, 5, $this->credits); // Credit hours
        $pdf->setXY(157, 93);
        $pdf->cell(12, 5, $this->avg_hours_week); // hours per week

        //        $pdf->cell(35, 5, 'Graduate Program:', 'LTB');
        //        if (!is_null($g)) {
        //            $pdf->cell(155, 5, $g->getName(), 'RTB');
        //        } else {
        //            $pdf->cell(155, 5, 'N/A', 'RTB');
        //        }
        //
        /**
        * Faculty supervisor information.
        */
        $pdf->setXY(26, 109);
        $pdf->cell(81, 5, $f->getFullName());

        $pdf->setXY(26, 131);
        $pdf->cell(77, 5, $f->phone);

        $pdf->setXY(26, 145);
        $pdf->cell(77, 5, $f->email . '@appstate.edu');

        /**
         * Agency information.
         */
        $pdf->setXY(133, 108);
        $pdf->cell(71, 5, $a->name);

        $agency_address = $a->getAddress();

        if(strlen($agency_address) < 50){
            // If it's short enough, just write it
            $pdf->setXY(125, 114);
            $pdf->cell(77, 5, $agency_address);
        }else{
            // Too long, need to use two lines
            $agencyLine1 = substr($agency_address, 0, 49); // get first 50 chars
            $agencyLine2 = substr($agency_address, 50); // get the rest, hope it fits

            $pdf->setXY(125, 114);
            $pdf->cell(77, 5, $agencyLine1);
            $pdf->setXY(110, 118);
            $pdf->cell(77, 5, $agencyLine2);
        }

        /**
         * Agency supervisor info.
         */
        $pdf->setXY(110, 129);
        $super = "";
        $superName = $a->getSupervisorFullName();
        if(isset($superName) && !empty($superName) && $superName != ''){
            //test('ohh hai',1);
            $super .= $a->getSupervisorFullName() . ',';
        }

        if(isset($a->supervisor_title) && !empty($a->supervisor_title) && $a->supervisor_title != ''){
            $super .= $a->supervisor_title;
        }
        $pdf->cell(75, 5, $super);

        $s_agency_address = $a->getSuperAddress();

        $pdf->setXY(124, 134);
        $pdf->cell(78, 5, $s_agency_address);

        $pdf->setXY(122, 144);
        $pdf->cell(72, 5, $a->supervisor_email);

        $pdf->setXY(122, 139);
        $pdf->cell(33, 5, $a->supervisor_phone);

        $pdf->setXY(163, 139);
        $pdf->cell(40, 5, $a->supervisor_fax);

        /* Internship Location */
        if(!empty($this->loc_address)){
            $loc[] = $this->loc_address;
        }

        if (!empty($this->loc_city)) {
            $loc[] = $this->loc_city;
        }

        if (!empty($this->loc_state) && $this->loc_state != '-1') {
            $loc[] = $this->loc_state;
        }

        if(!empty($this->loc_zip)){
            $loc[] = $this->loc_zip;
        }

        if($this->international == 1){
            $loc[] = $this->getLocCountry();
        }

        if (isset($loc)) {
            $pdf->setXY(110, 154);
            $pdf->cell(52, 5, implode(', ', $loc));
        }

        /**********
         * Page 2 *
        **********/
        $tplidx = $pdf->importPage(2);
        $pdf->addPage();
        $pdf->useTemplate($tplidx);

        /* Emergency Contact Info */
        $pdf->setXY(60, 252);
        $pdf->cell(52, 5, $this->emergency_contact_name);

        $pdf->setXY(134, 252);
        $pdf->cell(52, 5, $this->emergency_contact_relation);

        $pdf->setXY(175, 252);
        $pdf->cell(52, 5, $this->emergency_contact_phone);

        $pdf->output();
    }

    public function getLocCountry()
    {
        if (!$this->loc_country) {
            return 'United States';
        }
        return $this->loc_country;
    }

}

?>
