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

class Internship extends Model {

    public $term;
    public $student_id;
    public $agency_id;

    /**
     * If true, internship was approved by the Dean
     * @var boolean
     */
    public $approved = false;

    /**
     * username of person who approved it
     * @var string
     */
    public $approved_by = null;

    /**
     * Timestamp of approval
     * @var integer
     */
    public $approved_on = 0;
    public $faculty_supervisor_id;
    public $department_id;
    public $start_date = 0;
    public $end_date = 0;
    public $credits;
    public $avg_hours_week;
    public $domestic;
    public $international;
    public $paid;
    public $unpaid;
    public $stipend;
    public $pay_rate;
    public $notes;
    public $internship = 0;
    public $service_learn = 0;
    public $independent_study = 0;
    public $research_assist = 0;
    public $student_teaching = 0;
    public $clinical_practica = 0;
    public $special_topics = 0;
    public $other_type;
    public $loc_address;
    public $loc_city;
    public $loc_state;
    public $loc_zip;
    public $loc_province;
    public $loc_country;
    public $course_subj;
    public $course_no;
    public $course_sect;
    public $course_title;

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
        PHPWS_Core::initModClass('intern', 'Term.php');

        $i = array();
        $s = $this->getStudent();
        $a = $this->getAgency();
        $f = $this->getFacultySupervisor();
        $d = $this->getDepartment();
        $i['Start Date'] = $this->getStartDate(true);
        $i['End Date'] = $this->getEndDate(true);
        $i['Term'] = Term::rawToRead($this->term, false);
        $i['Credits'] = $this->credits;
        $i['Average Hours Per Week'] = $this->avg_hours_week;
        $i['Domestic'] = $this->domestic == 1 ? 'Yes' : 'No';
        $i['International'] = $this->international == 1 ? 'Yes' : 'No';
        $i['Paid'] = $this->paid == 1 ? 'Yes' : 'No';
        $i['Stipend'] = $this->stipend == 1 ? 'Yes' : 'No';
        $i['Unpaid'] = $this->unpaid == 1 ? 'Yes' : 'No';
        $i['Internship'] = $this->internship == 1 ? 'Yes' : 'No';
        $i['Service Learning'] = $this->service_learn == 1 ? 'Yes' : 'No';
        $i['Independent Study'] = $this->independent_study == 1 ? 'Yes' : 'No';
        $i['Research Assistant'] = $this->research_assist == 1 ? 'Yes' : 'No';
        $i['Student Teaching'] = $this->student_teaching == 1 ? 'Yes' : 'No';
        $i['Clinical Practica'] = $this->clinical_practica == 1 ? 'Yes' : 'No';
        $i['Special Topics'] = $this->special_topics == 1 ? 'Yes' : 'No';
        $i['Approved by Dean'] = $this->approved == 1 ? 'Yes' : 'No';
        $i['Approver'] = $this->approved_by;
        $i['Approval Date'] = $this->approved_on;
        $i['Other Type'] = $this->other_type;
        $i['Notes'] = $this->notes;
        $i['Location Address'] = $this->loc_address;
        $i['Location State'] = $this->loc_state;
        $i['Location City'] = $this->loc_city;
        $i['Location Zip'] = $this->loc_zip;
        $i['Course Subject'] = $this->course_subj;
        $i['Course Number'] = $this->course_no;
        $i['Course Section'] = $this->course_sect;
        $i['Course Title'] = $this->course_title;
        // Merge data from other objects.
        $i = array_merge($s->getCSV(), $i);
        $i = array_merge($i, $a->getCSV());
        $i = array_merge($i, $f->getCSV());
        $i = array_merge($i, $d->getCSV());

        return $i;
    }

    /*
     private function toggle($pdf, $header=true)
    {
    if ($header) {
    // Set header font.
    $pdf->setFont('Arial', 'B', '10');
    } else {
    // Set data font.
    $pdf->setFont('Courier', '', '10');
    }
    }
    */

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
        if ($this->service_learn == 1) {
            $types[] = 'Service Learning';
        }
        if ($this->independent_study == 1) {
            $types[] = 'Independent Study';
        }
        if ($this->research_assist == 1) {
            $types[] = 'Research Assistant';
        }
        if ($this->student_teaching == 1) {
            $types[] = 'Student Teaching';
        }
        if ($this->clinical_practica == 1) {
            $types[] = 'Clinical Practica';
        }
        if ($this->special_topics == 1) {
            $types[] = 'Special Topics';
        }
        if ($this->other_type != '' && $this->other_type != null) {
            $types[] = $this->other_type;
        }

        return implode(', ', $types);
    }

    /**
     * Get the Student object associated with this Internship.
     */
    public function getStudent()
    {
        PHPWS_Core::initModClass('intern', 'Student.php');
        return new Student($this->student_id);
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
     * Get formatted dates.
     */
    public function getStartDate($formatted=false)
    {
        if (!$this->start_date) {
            return null;
        }
        if ($formatted) {
            return date('j M, Y', $this->start_date);
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
            return date('j M, Y', $this->end_date);
        } else {
            return $this->end_date;
        }
    }

    /**
     * Is this internship domestic?
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
     * Row tags for DBPager
     */
    public function getRowTags()
    {
        PHPWS_Core::initModClass('intern', 'Term.php');

        $tags = array();

        // Get objects associated with this internship.
        $s = $this->getStudent();
        $a = $this->getAgency();
        $f = $this->getFacultySupervisor();
        $d = $this->getDepartment();

        // Student info.
        $tags['STUDENT_NAME'] = $s->getFullName();
        $tags['STUDENT_BANNER'] = $s->banner;

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

        $tags['EDIT'] = PHPWS_Text::moduleLink('Edit', 'intern', array('action' => 'edit_internship', 'id' => $this->id));
        $tags['PDF'] = PHPWS_Text::moduleLink('Generate Contract', 'intern', array('action' => 'pdf', 'id' => $this->id));

        // TODO: Finish off fields.
        return $tags;
    }

    /**
     * Get internship types in an associative array.
     */
    public static function getTypesAssoc()
    {
        /**
         return array('internship' => 'Internship',
         'service_learn' => 'Service Learning',
         'independent_study' => 'Independent Study',
         'research_assistant' => 'Research Assistant',
         'student_teaching' => 'Student Teaching',
         'clinical_practica' => 'Clinical Practica In Health Fields',
         'special_topics' => 'Special Topics');
         *
         */
        return array('internship' => 'Internship',
            'student_teaching' => 'Student Teaching',
            'clinical_practica' => 'Clinical Practica In Health Fields');
    }

    /**
     * Create a CSV file with rows for each internship with
     * ID in $internships.
     * @return filename
     */
    public static function getCSVFile($internships)
    {
        if (sizeof($internships) < 1) {
            return null;
        }

        // Create a temporary file for CSV.
        $tmpName = tempnam('/tmp', 'php');
        $handle = fopen($tmpName, 'w');

        // Get the first internship manually so we can fetch the header names.
        $i = new Internship($internships[0]);
        $line = $i->getCSV();
        $headers = array_keys($line);
        fputcsv($handle, $headers); // Write header first.
        fputcsv($handle, $line); // Write first data line.
        // Continue writing the rest of lines.
        for ($index = 1; $index < sizeof($internships); $index++) {
            $i = new Internship($internships[$index]);
            $line = $i->getCSV();
            fputcsv($handle, $line);
        }

        fclose($handle);

        return $tmpName;
    }

    /**
     * Create a new internship. Save to DB.
     */
    public static function addInternship()
    {
        PHPWS_Core::initModClass('intern', 'Student.php');
        PHPWS_Core::initModClass('intern', 'Agency.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'FacultySupervisor.php');

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

        PHPWS_DB::begin();
        /* See if this student exists already */
        $student = Student::getStudentByBanner($_REQUEST['banner']);
        if (isset($_REQUEST['student_id'])) {
            /* User is attempting to edit internship. Student ID should be passed in. */
            try {
                $student = new Student($_REQUEST['student_id']);
            } catch (Exception $e) {
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Student ID.');
                NQ::close();
                return PHPWS_Core::goBack();
            }
        } else if (is_null($student)) {
            /* Not student exists by banner ID and this is a new internship. */
            $student = new Student();
        }

        $student->first_name = $_REQUEST['student_first_name'];
        $student->middle_name = $_REQUEST['student_middle_name'];
        $student->last_name = $_REQUEST['student_last_name'];
        $student->banner = $_REQUEST['banner'];
        $student->phone = $_REQUEST['student_phone'];
        $student->email = $_REQUEST['student_email'];
        $student->level = $_REQUEST['student_level'];
        $student->grad_prog = $_REQUEST['grad_prog'] == -1 ? null : $_REQUEST['grad_prog'];
        $student->ugrad_major = $_REQUEST['ugrad_major'] == -1 ? null : $_REQUEST['ugrad_major'];
        $student->gpa = $_REQUEST['student_gpa'];
        
        $student->address = $_REQUEST['student_address'];
        $student->city = $_REQUEST['student_city'];
        $student->state = $_REQUEST['student_state'];
        $student->zip = $_REQUEST['student_zip'];
        
        $student->emergency_contact_name = $_REQUEST['emergency_contact_name'];
        $student->emergency_contact_relation = $_REQUEST['emergency_contact_relation'];
        $student->emergency_contact_phone = $_REQUEST['emergency_contact_phone'];

        try {
            $studentId = $student->save();
        } catch (Exception $e) {
            PHPWS_DB::rollback();
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }

        // Create/Save agency
        $agency = new Agency();
        if (isset($_REQUEST['agency_id'])) {
            // User is editing internship
            try {
                $agency = new Agency($_REQUEST['agency_id']);
            } catch (Exception $e) {
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Agency ID.');
                NQ::close();
                return PHPWS_Core::goBack();
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
            PHPWS_DB::rollback();
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }

        // Create/Save Faculty supervisor
        $faculty = new FacultySupervisor();
        if (isset($_REQUEST['supervisor_id'])) {
            // User is editing internship
            try {
                $faculty = new FacultySupervisor($_REQUEST['supervisor_id']);
            } catch (Exception $e) {
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Faculty Supervisor ID.');
                NQ::close();
                return PHPWS_Core::goBack();
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
            PHPWS_DB::rollback();
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }

        // Create/Save internship.
        $i = new Internship();
        if (isset($_REQUEST['internship_id'])) {
            // User is editing internship
            try {
                $i = new Internship($_REQUEST['internship_id']);
            } catch (Exception $e) {
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Internship ID.');
                NQ::close();
                return PHPWS_Core::goBack();
            }
        }

        $i->term = $_REQUEST['term'];
        $i->student_id = $studentId;
        $i->agency_id = $agencyId;
        $i->faculty_supervisor_id = $facultyId;
        $i->department_id = $_REQUEST['department'];
        $i->start_date = !empty($_REQUEST['start_date']) ? strtotime($_REQUEST['start_date']) : 0;
        $i->end_date = !empty($_REQUEST['end_date']) ? strtotime($_REQUEST['end_date']) : 0;
        // Check dates
        if ($i->start_date > $i->end_date) {
            PHPWS_DB::rollback();
            NQ::simple('intern', INTERN_WARNING, 'Start date needs to be before end date.');
            NQ::close();
            return PHPWS_Core::goBack();
        }
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
        //$i->service_learn = isset($_REQUEST['service_learning_type']);
        //$i->independent_study = isset($_REQUEST['independent_study_type']);
        //$i->research_assist = isset($_REQUEST['research_assist_type']);
        $i->student_teaching = isset($_REQUEST['student_teaching_type']);
        $i->clinical_practica = isset($_REQUEST['clinical_practica_type']);
        //$i->special_topics = isset($_REQUEST['special_topics_type']);
        //$i->other_type = isset($_REQUEST['check_other_type']) ? $_REQUEST['other_type'] : null;
        $i->notes = $_REQUEST['notes'];
        
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
        
        $i->course_subj = strip_tags($_POST['course_subj']);
        $i->course_no = strip_tags($_POST['course_no']);
        $i->course_sect = strip_tags($_POST['course_sect']);
        $i->course_title = strip_tags($_POST['course_title']);
        if (isset($_POST['approved'])) {
            $i->approved = 1;
            $i->approved_by = Current_User::getUsername();
            $i->approved_on = time();
            Email::sendRegistrarEmail($student, $i, $agency);
        }

        try {
            $i->save();
        } catch (Exception $e) {
            PHPWS_DB::rollback();
            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            NQ::close();
            return PHPWS_Core::goBack();
        }

        PHPWS_DB::commit();
        if (isset($_REQUEST['student_id'])) {
            // Show message if user edited internship
            NQ::simple('intern', INTERN_SUCCESS, 'Saved internship for ' . $student->getFullName());
            NQ::close();
            return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&id=' . $i->id);
        } else {
            NQ::simple('intern', INTERN_SUCCESS, 'Added internship for ' . $student->getFullName());
            NQ::close();
            return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&id=' . $i->id);
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
        $s = $this->getStudent();
        $a = $this->getAgency();
        $d = $this->getDepartment();
        $f = $this->getFacultySupervisor();
        $m = $s->getUgradMajor();
        $g = $s->getGradProgram();


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
        $pdf->MultiCell(31, 3, $d->name);

        // Subject and Course #
        //$pdf->setFont('Times', null, 8);
        $pdf->setXY(132, 44);
        $course_info = $this->course_subj . ' ' . $this->course_no;
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
        $pdf->cell(55, 5, $s->getFullName());

        $pdf->setXY(173,77);
        $pdf->cell(54,5, $s->gpa);

        $pdf->setXY(32, 83);
        $pdf->cell(42, 5, $s->banner);

        $pdf->setXY(41, 88);
        $pdf->cell(54, 5, $s->email . '@appstate.edu');

        $pdf->setXY(113, 88);
        $pdf->cell(54, 5, $s->phone);
        
        /* Student Address */
        $pdf->setXY(105, 83);
        $pdf->cell(54, 5, $s->address . ', ' . $s->city . ', ' . $s->state . ' ' . $s->zip);


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
        $pdf->cell(77, 5, $f->email);

        /**
         * Agency information.
         */
        $pdf->setXY(133, 108);
        $pdf->cell(71, 5, $a->name);

        if ($this->domestic == 1) {
            $agency_address = $a->getDomesticAddress();
        } else {
            $agency_address = $a->getInternationalAddress();
        }
        
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
        $pdf->cell(75, 5, $a->getSupervisorFullName() .  ', ' . $a->supervisor_title);

        if ($this->domestic == 1) {
            $s_agency_address = $a->getSuperDomesticAddress();
        } else {
            $s_agency_address = $a->getSuperInternationalAddress();
        }

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
        $pdf->cell(52, 5, $s->emergency_contact_name);
        
        $pdf->setXY(134, 252);
        $pdf->cell(52, 5, $s->emergency_contact_relation);
        
        $pdf->setXY(175, 252);
        $pdf->cell(52, 5, $s->emergency_contact_phone);
        
        
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
