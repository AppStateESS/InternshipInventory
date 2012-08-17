<?php

/**
 * Internship
 *
 * Forms relationship between a student, department, and agency.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 * @author Jeremy Booker <jbooker at tux dot appstate dot edu>
 * @package Intern
 */
PHPWS_Core::initModClass('intern', 'Model.php');
PHPWS_Core::initModClass('intern', 'Email.php');
PHPWS_Core::initModClass('intern', 'Term.php');
PHPWS_Core::initModClass('intern', 'Major.php');

class Internship {
    
    public $id;

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
    
    // Metaphones for fuzzy search
    public $first_name_meta;
    public $last_name_meta;
    
    // Contact Info
    public $phone;
    public $email;
    
    // Academic info
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

    public $multi_part;
    public $secondary_part;
    
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
     * Constructs a new Internship object.
     */
    public function __construct(){
        
    }
    
    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_internship');
    }
    
    /**
     * Save model to database
     * @return - new ID of model.
     */
    public function save()
    {
        $db = $this->getDb();
        try {
            $result = $db->saveObject($this);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    
        if (PHPWS_Error::logIfError($result)) {
            throw new Exception($result->toString());
        }
    
        return $this->id;
    }
    
    /**
     * Delete model from database.
     */
    public function delete()
    {
        if (is_null($this->id) || !is_numeric($this->id))
            return false;
    
        $db = $this->getDb();
        $db->addWhere('id', $this->id);
        $result = $db->delete();
    
        if (PHPWS_Error::logIfError($result)) {
            throw new Exception($result->getMessage(), $result->getCode());
        }
    
        return true;
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
     * Returns true if this internship is at the undergraduate level, false otherwise.
     *
     * @return boolean
     */
    public function isUndergraduate()
    {
        if($this->getLevel() == 'ugrad'){
            return true;
        }
        
        return false;
    }

    /**
     * Returns true if this internship is at the graduate level, false otherwise.
     * @return boolean
     */
    public function isGraduate()
    {
        if($this->getLevel() == 'grad'){
            return true;
        }
        
        return false;
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
        return $this->domestic;
    }

    /**
     * Is this internship International?
     *
     * @return bool True if this is an international internship, false otherwise.
     */
    public function isInternational()
    {
        return $this->international;
    }
    
    public function isOiedCertified()
    {
        if($this->oied_certified == 1){
            return true;
        }else{
            return false;
        }
    }

    public function isMultipart()
    {
        if($this->multi_part == 1){
            return true;
        }else{
            return false;
        }
    }
    
    public function isSecondaryPart()
    {
        if($this->secondary_part == 1){
            return true;
        }else{
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
        $a = $this->getAgency();
        $f = $this->getFacultySupervisor();
        $d = $this->getDepartment();

        // Student info.
        $tags['STUDENT_NAME'] = PHPWS_Text::moduleLink($this->getFullName(), 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));
        $tags['STUDENT_BANNER'] = PHPWS_Text::moduleLink($this->getBannerId(), 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));

        // Dept. info
        $tags['DEPT_NAME'] = PHPWS_Text::moduleLink($d->name, 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));

        // Faculty info.
        $facultyName = $f->getFullName();
        if(!empty($facultyName)){
            $tags['FACULTY_NAME'] = PHPWS_Text::moduleLink($f->getFullName(), 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));
        }else{
            // Makes this cell in the table a clickable link, even if there's no faculty name
            $tags['FACULTY_NAME'] = PHPWS_Text::moduleLink('&nbsp;', 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));
        }

        $tags['TERM'] = PHPWS_Text::moduleLink(Term::rawToRead($this->term), 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));

        $tags['WORKFLOW_STATE'] = PHPWS_Text::moduleLink($this->getWorkflowState()->getFriendlyName(), 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));
        
        //$tags['EDIT'] = PHPWS_Text::moduleLink('Edit', 'intern', array('action' => 'edit_internship', 'internship_id' => $this->id));
        //$tags['PDF'] = PHPWS_Text::moduleLink('Generate Contract', 'intern', array('action' => 'pdf', 'id' => $this->id));

        return $tags;
    }

    /*****************************
     * Accessor / Mutator Methods
    */

    /**
     * Returns the database id of this internship.
     * 
     * @return int
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Returns the Banner ID of this student.
     *
     * @return string Banner ID
     */
    public function getBannerId(){
        return $this->banner;
    }

    public function getEmailAddress(){
        return $this->email;
    }
    
    /**
     * Returns the WorkflowState name for this internshio's current state/status.
     * Can be null if no state has been set yet.
     * 
     * @return string
     */
    public function getStateName()
    {
        return $this->state;
    }

    /**
     * Sets the WorkflowState of this internship.
     * 
     * @param WorkflowState $state
     */
    public function setState(WorkflowState $state){
        $this->state = $state->getName();
    }
    
    /**
     * Returns the WorkflowState object represeting this internship's current state/status.
     * Returns null if no state has been set yet.
     * 
     * @return WorkflowState
     */
    public function getWorkflowState()
    {
        $stateName = $this->getStateName();
        
        if(is_null($stateName)){
            return null;
        }
        
        PHPWS_Core::initModClass('intern', 'WorkflowStateFactory.php');
        return WorkflowStateFactory::getState($stateName);
    }
    
    /**
     * Returns the campus on which this internship is based
     * 
     * Valid values are: 'main_campus', 'distance_ed'
     * 
     * @return String campus name
     */
    public function getCampus()
    {
        return $this->campus;
    }
    
    /**
     * Returns true if this is a Distance Ed internship, false otherwise.
     * 
     * @return boolean
     */
    public function isDistanceEd()
    {
        if($this->getCampus() == 'distance_ed'){
            return true;
        }
        
        return false;
    }
    
    /**
     * Calculates and sets the metaphone value for this student's first name.
     * 
     * @param string $firstName
     */
    public function setFirstNameMetaphone($firstName){
        $this->first_name_meta = metaphone($firstName);
    }
    
    /**
     * Calculates and sets the metaphone value for this student's last name.
     * 
     * @param string $lastName
     */
    public function setLastNameMetaphone($lastName){
        $this->last_name_meta = metaphone($lastName);
    }

    /**
     * Returns this student's level ('grad', or 'undergrad')
     * 
     * @return string
     */
    public function getLevel(){
        return $this->level;
    }
    
    public function getGpa(){
        return $this->gpa;
    }
    
    public function getPhoneNumber(){
        return $this->phone;
    }
    
    public function getStudentAddress()
    {
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
        
        return $studentAddress;
    }
    
    /**
     * Returns this internship's term
     * 
     * @return int
     */
    public function getTerm(){
        return $this->term;
    }
    
    public function getCourseNumber(){
        return $this->course_no;
    }
    
    public function getCourseSection(){
        return $this->course_sect;
    }
    
    public function getCourseTitle(){
        return $this->course_title;
    }
    
    public function getCreditHours(){
        return $this->credits;
    }
    
    public function getAvgHoursPerWeek(){
        return $this->avg_hours_week;
    }
    
    public function isPaid(){
        if($this->paid == 1){
            return true;
        }
        
        return false;
    }
    
    public function isUnPaid(){
        if($this->unpaid == 1){
            return true;
        }
        
        return false;
    }
    
    public function getEmergencyContactName()
    {
        return $this->emergency_contact_name;
    }
    
    public function getEmergencyContactRelation()
    {
        return $this->emergency_contact_relation;
    }
    
    public function getEmergencyContactPhoneNumber()
    {
        return $this->emergency_contact_phone;
    }
    
    /***********************
     * Static Methods
     ***********************/
    
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
     * 
     * TODO: Move this to it's own controller class.
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
        if (isset($_REQUEST['internship_id'])) {
            // User is editing internship
            try {
                PHPWS_Core::initModClass('intern', 'InternshipFactory.php');
                $i = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
            } catch (Exception $e) {
                // Rollback and re-throw the exception so that admins gets an email
                PHPWS_DB::rollback();
                throw $e;
            }
        }else{
            $i = new Internship();
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

        if(isset($_POST['course_subj']) && $_POST['course_subj'] != '-1'){
            $i->course_subj = strip_tags($_POST['course_subj']);
        }else{
            $i->course_subj = null;
        }

        $i->course_no = strip_tags($_POST['course_no']);
        $i->course_sect = strip_tags($_POST['course_sect']);
        $i->course_title = strip_tags($_POST['course_title']);
        
        if(isset($_POST['multipart'])){
            $i->multi_part = 1;
        }else{
            $i->multi_part = 0;
        }
        
        if(isset($_POST['multipart']) && isset($_POST['secondary_part'])){
            $i->secondary_part = 1;
        }else{
            $i->secondary_part = 0;
        }

        // Student Information
        $i->first_name = $_REQUEST['student_first_name'];
        $i->middle_name = $_REQUEST['student_middle_name'];
        $i->last_name = $_REQUEST['student_last_name'];
        
        $i->setFirstNameMetaphone($_REQUEST['student_first_name']);
        $i->setLastNameMetaphone($_REQUEST['student_last_name']);
        
        $i->banner = $_REQUEST['banner'];
        $i->phone = $_REQUEST['student_phone'];
        $i->email = $_REQUEST['student_email'];
        $i->level = $_REQUEST['student_level'];

        // Check the level and record the major/program for this level.
        // Be sure to set/clear the other leve's major/program to null
        // in case the user is switching levels.
        if($i->getLevel() == 'ugrad'){
            $i->ugrad_major = $_REQUEST['ugrad_major'];
            $i->grad_prog = null;
        }else if($i->getLevel() == 'grad'){
            $i->grad_prog = $_REQUEST['grad_prog'];
            $i->ugrad_major = null;
        }
        
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
        }else{
            $oiedCertified = false;
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

        $workflow->doNotification(isset($_POST['notes'])?$_POST['notes']:null);

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

    public function getLocCountry()
    {
        if (!$this->loc_country) {
            return 'United States';
        }
        return $this->loc_country;
    }

}

?>
