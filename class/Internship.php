<?php
namespace Intern;

use \Intern\Student;

use \PHPWS_Text;

/**
 * Internship
 *
 * Forms relationship between a student, department, and agency.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 * @author Jeremy Booker <jbooker at tux dot appstate dot edu>
 * @package Intern
 */
class Internship {

    const GPA_MINIMUM = 2.0;

    public $id;

    // Agency
    public $agency_id;

    // Department
    public $department_id;

    //public $faculty_supervisor_id;
    public $faculty_id;

    // Status info
    public $state;
    public $oied_certified;

    // Student data
    public $banner;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $birth_date;

    // Metaphones for fuzzy search
    public $first_name_meta;
    public $middle_name_meta;
    public $last_name_meta;

    // Academic info
    public $level;
    public $gpa;
    public $campus;
    public $major_code;
    public $major_description;

    /**
     * @deprecated
     * @see $major_code
     */
    public $grad_prog;
    /**
     * @deprecated
     * @see $major_code
     */
    public $ugrad_major;

    // Contact Info
    public $phone;
    public $email; // NB: Username, without a domain name

    // Student address
    public $student_address;
    public $student_address2;
    public $student_city;
    public $student_state;
    public $student_zip;

    // Location data
    public $domestic;
    public $international;

    public $loc_address;
    public $loc_city;
    public $loc_state;
    public $loc_zip;
    public $loc_province;
    public $loc_country;

    // Term Info
    public $term;
    public $start_date;
    public $end_date;
    public $credits;
    public $avg_hours_week;
    public $paid;
    public $stipend;
    public $pay_rate;
    public $co_op;


    // Course Info
    public $multi_part;
    public $secondary_part;
    public $course_subj;
    public $course_no;
    public $course_sect;
    public $course_title;

    // Corequisite Course Info
    // Course must be in the same subject, so there's no subject code
    public $corequisite_number;
    public $corequisite_section;

    // Type
    public $experience_type;

    // Checks
    public $background_check;
    public $drug_check;

    // Form token
    public $form_token;


    // Static vars - Used to avoid repeated DB queries when looping over Internship objects
    private static $termDescriptionList;

    /**
     * Constructs a new Internship object.
     */
    public function __construct(Student $student, $term, $location, $state, $country, Department $department, Agency $agency){

        // Initialize student data
        $this->initalizeStudentData($student);

        // Initialize basic data
        $this->term = $term;

        // Set basic location data
        if($location == 'domestic') {
            $this->setDomestic(true);
            $this->setInternational(false);

            $this->setLocationState($state);
        } else if($location == 'international') {
            $this->setDomestic(false);
            $this->setInternational(true);

            $this->setLocationCountry($country);
        } else {
            throw new \InvalidArgumentException('Invalid location.');
        }

        // Get department id
        $this->department_id = $department->getId();

        // Get agency id
        $this->agency_id = $agency->getId();

        // Set initial state
        $this->setState(WorkflowStateFactory::getState('CreationState'));

        // Set initial OIED certification
        $this->setOiedCertified(false);

        // Set Form Token
        $this->form_token = uniqid();
    }

    /**
     * Copies student data from Student object to this Internship.
     * @param Student $student
     */
    private function initalizeStudentData(Student $student)
    {
        // Basic student demographics
        $this->banner       = $student->getStudentId();
        $this->email        = $student->getUsername();
        $this->first_name   = $student->getFirstName();
        $this->middle_name  = $student->getMiddleName();
        $this->last_name    = $student->getLastName();
        $this->birth_date   = $student->getBirthDate();

        $this->setFirstNameMetaphone($student->getFirstName());
        $this->setMiddleNameMetaphone($student->getMiddleName());
        $this->setLastNameMetaphone($student->getLastName());

        // Academic info
        $this->level = $student->getLevel();
        $this->campus = $student->getCampus();
        $this->gpa = $student->getGpa();

        // Majors - If double major, just take index 0
        $majors = $student->getMajors();
        if(is_array($majors) && sizeof($majors) > 0) {
            $this->major_code = $majors[0]->getCode();
            $this->major_description = $majors[0]->getDescription();
        } else if (is_object($majors)) {
            $this->major_code = $majors->getCode();
            $this->major_description = $majors->getDescription();
        } // Else, there were no majors set in the Student object

        // Contact Info
        $this->phone = $student->getPhone();

        // Student address
        $this->student_address  = $student->getAddress();
        $this->student_address2 = $student->getAddress2();
        $this->student_city = $student->getCity();
        $this->student_state = $student->getState();
        $this->student_zip = $student->getZip();
    }

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new \PHPWS_DB('intern_internship');
    }

    /**
     * Save model to database
     * @return new ID of model.
     */
    public function save()
    {
        $db = $this->getDb();
        try {
            $result = $db->saveObject($this);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        if (\PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->toString());
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

        if (\PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->getMessage(), $result->getCode());
        }

        return true;
    }

    /**
     * @Override Model::getCSV
     * Get a CSV formatted for for this internship.
     */
    public function getCSV()
    {
        // Initalize term description list, if needed
        // Store term list in a static var, so hopefully we only do this once per export
        if(!isset(self::$termDescriptionList)){
            self::$termDescriptionList = TermFactory::getTermsAssoc();
        }

        $csv = array();

        // Student data
        $csv['Banner ID']   = $this->banner;
        $csv['First Name']  = $this->first_name;
        $csv['Middle Name'] = $this->middle_name;
        $csv['Last Name']   = $this->last_name;

        // Academic Info
        $csv['Level']           = $this->getLevel();
        if($this->getLevel() == 'ugrad'){
            //$csv['Undergrad Major'] = $this->getUgradMajor()->getName();
            $csv['Grduate Program'] = '';
        }else if($this->getLevel() == 'grad'){
            $csv['Undergrad Major'] = '';
            //$csv['Graduate Program'] = $this->getGradProgram()->getName();
        }else{
            $csv['Undergrad Major'] = '';
            $csv['Grduate Program'] = '';
        }
        $csv['GPA']             = $this->getGpa();
        $csv['Campus']          = $this->getCampus();

        // Status Info
        $csv['Status']                 = $this->getWorkflowState()->getFriendlyName();
        $csv['OIED Certified']         = $this->isOiedCertified() == 1 ? 'Yes' : 'No';

        // Student Academic Info
        $csv['Phone #']     = $this->phone;
        $csv['Email']       = $this->email;

        // Student Address
        $csv['Student Address']        = $this->student_address;
        $csv['Student Address 2']      = $this->student_address2;
        $csv['Student City']           = $this->student_city;
        $csv['Student State']          = $this->student_state;
        $csv['Student Zip']            = $this->student_zip;

        // Emergency Contact
        $csv['Emergency Contact Name']     = $this->getEmergencyContactName();
        $csv['Emergency Contact Relation'] = $this->getEmergencyContactRelation();
        $csv['Emergency Contact Phone']    = $this->getEmergencyContactPhoneNumber();

        // Internship Data
        $csv['Term']                   = self::$termDescriptionList[$this->term];
        $csv['Start Date']             = $this->getStartDate(true);
        $csv['End Date']               = $this->getEndDate(true);
        $csv['Credits']                = $this->credits;
        $csv['Average Hours Per Week'] = $this->avg_hours_week;
        $csv['Paid']                   = $this->paid == 1 ? 'Yes' : 'No';
        $csv['Stipend']                = $this->stipend == 1 ? 'Yes' : 'No';

        // Internship Type
        $csv['Experience Type']          = $this->getExperienceType();

        // Internship location data
        $csv['Domestic']               = $this->isDomestic() ? 'Yes' : 'No';
        $csv['International']          = $this->isInternational() ? 'Yes' : 'No';
        $csv['Location Address']       = $this->loc_address;
        $csv['Location City']          = $this->loc_city;
        $csv['Location State']         = $this->loc_state;
        $csv['Location Zip']           = $this->loc_zip;
        $csv['Province']               = $this->loc_province;
        $csv['Country']                = $this->loc_country;

        // Course Info
        $csv['Multi-part']             = $this->isMultipart() ? 'Yes' : 'No';
        $csv['Secondary Part']         = $this->isSecondaryPart() ? 'Yes' : 'No';

        if($this->getSubject() !== null){
            $csv['Course Subject']     = $this->getSubject()->getName();
        }else {
            $csv['Course Subject']     = '';
        }

        $csv['Course Number']          = $this->course_no;
        $csv['Course Section']         = $this->course_sect;
        $csv['Course Title']           = $this->course_title;

        // Get external objects
        $a = $this->getAgency();
        $f = $this->getFaculty();
        $d = $this->getDepartment();
        $c = $this->getDocuments();

        // Merge data from other objects.
        $csv = array_merge($csv, $a->getCSV());

		if(count($c) > 0) {
			$csv['Document Uploaded']  = 'Yes';
		} else {
			$csv['Document Uploaded']  = 'No';
		}

        if ($f instanceof Faculty) {
            $csv = array_merge($csv, $f->getCSV());
        } else {
            $csv['Faculty Super. First Name'] = '';
            $csv['Faculty Super. Last Name']  = '';
            $csv['Faculty Super. Phone']      = '';
            $csv['Faculty Super. Email']      = '';
        }

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
        $level = $this->getLevel();
        if($level == Student::GRADUATE || $level == Student::GRADUATE2 || $level == Student::DOCTORAL || $level == Student::POSTDOC) {
            return true;
        }

        return false;
    }

    /**
     * Get a Major object for the major of this student.
     */
    public function getUgradMajor()
    {
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
        if(!is_null($this->grad_prog) && $this->grad_prog != 0){
            return new GradProgram($this->grad_prog);
        }else{
            return null;
        }
    }

    public function getAgencyId() {
        return $this->agency_id;
    }

    /**
     * Get the Agency object associated with this internship.
     */
    public function getAgency()
    {
        return AgencyFactory::getAgencyById($this->getAgencyId());
    }

    /**
     * Get the Faculty Supervisor object associated with this internship.
     *
     */
    public function getFaculty()
    {
        if(!isset($this->faculty_id)){
            return null;
        }

        return FacultyFactory::getFacultyObjectById($this->faculty_id);
    }

	/**
	 * Get the Emergency Contact's First Name
	 */
	public function getEmergencyContactName()
	{
		$name = EmergencyContactFactory::getContactsForInternship($this);
		if(!empty($name))
		{
			 return $name[0]->getName();
		}
	}

		/**
	 * Get the Emergency Contact's Relationship
	 */
	public function getEmergencyContactRelation()
	{
		$relationship = EmergencyContactFactory::getContactsForInternship($this);
		if(!empty($relationship))
		{
			 return $relationship[0]->getRelation();
		}
	}

		/**
	 * Get the Emergency Contact's Phone Number
	 */
	public function getEmergencyContactPhoneNumber()
	{
		$phone = EmergencyContactFactory::getContactsForInternship($this);
		if(!empty($phone))
		{
			 return $phone[0]->getPhone();
		}
	}

    /**
     * Get the Department object associated with this internship.
     */
    public function getDepartment()
    {
        return new Department($this->department_id);
    }

    public function getSubject()
    {
        if($this->course_subj === null || $this->course_subj === 0){
            return null;
        }

        return new Subject($this->course_subj);
    }

    /**
     * Get Document objects associated with this internship.
     */
    public function getDocuments()
    {
        $db = InternDocument::getDB();
        $db->addWhere('internship_id', $this->id);
        return $db->getObjects('\Intern\InternDocument');
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

    /*
     * Get the student's first name.
     */
    public function getFirstName()
    {
      return $this->first_name;
    }

    /*
     * Get the student's last name.
     */
    public function getLastName()
    {
      return $this->last_name;
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

    public function setStartDate($startDate) {
        $this->start_date = $startDate;
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

    public function setEndDate($endDate) {
        $this->end_date = $endDate;
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
     * Sets the domestic location flag.
     *
     * @param bool $domestic
     */
    public function setDomestic($domestic)
    {
        $this->domestic = $domestic;
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

    /**
     * Sets the international flag.
     *
     * @param bool $international
     */
    public function setInternational($international)
    {
        $this->international = $international;
    }

    public function getLocationState()
    {
        return $this->loc_state;
    }

    public function getLocationCountry()
    {
        return $this->loc_country;
    }

    /**
     * Sets the country code for this internship. Should be a two letter abbreviation.
     */
    public function setLocationCountry($country)
    {
        if(!isset($country) || $country == '') {
            throw new \InvalidArgumentException('Empty country code');
        }

        if(strlen($country) > 2) {
            throw new \InvalidArgumentException('Country code is too long');
        }

        $this->loc_country = $country;
    }

    public function getLocationProvince()
    {
        return $this->loc_province;
    }

    public function isOiedCertified()
    {
        if($this->oied_certified == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Sets whether or not this internship is OIED certified
     *
     * @param boolean $certified
     */
    public function setOiedCertified($certified) {
        if($certified){
            $this->oied_certified = 1;
        }else{
            $this->oied_certified = 0;
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
        $tags = array();

        // Get objects associated with this internship.
        $d = $this->getDepartment();

        //TODO: Use a single $params array instead of making a new array for every call to PHPWS_Test::moduleLink

        // Student info.
        $tags['STUDENT_NAME'] = PHPWS_Text::moduleLink($this->getFullName(), 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));
        $tags['STUDENT_BANNER'] = PHPWS_Text::moduleLink($this->getBannerId(), 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));

        // Dept. info
        $tags['DEPT_NAME'] = PHPWS_Text::moduleLink($d->name, 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));

        // Faculty info.
        if(isset($this->faculty_id)){
            $f = $this->getFaculty();
            $tags['FACULTY_NAME'] = PHPWS_Text::moduleLink($f->getFullName(), 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));
        }else{
            // Makes this cell in the table a clickable link, even if there's no faculty name
            $tags['FACULTY_NAME'] = PHPWS_Text::moduleLink('&nbsp;', 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));
        }

        $term = TermFactory::getTermByTermCode($this->term);
        $tags['TERM'] = PHPWS_Text::moduleLink($term->getDescription(), 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));

        $tags['WORKFLOW_STATE'] = PHPWS_Text::moduleLink($this->getWorkflowState()->getFriendlyName(), 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));

        //$tags['EDIT'] = PHPWS_Text::moduleLink('Edit', 'intern', array('action' => 'ShowInternship', 'internship_id' => $this->id));
        //$tags['PDF'] = PHPWS_Text::moduleLink('Generate Contract', 'intern', array('action' => 'pdf', 'id' => $this->id));

        return $tags;
    }

    public function getLocCountry()
    {
        if (!$this->loc_country) {
            return 'United States';
        }
        return $this->loc_country;
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

    public function setId($id){
      $this->id = $id;
    }

    /**
     * Returns the Banner ID of this student.
     *
     * @return string Banner ID
     */
    public function getBannerId(){
        return $this->banner;
    }

    /**
     * NB: Returns the username part of the email, without a domain name
     */
    public function getEmailAddress(){
        return $this->email;
    }

    public function getBirthDateFormatted() {
        if(!isset($this->birth_date) || $this->birth_date === 0){
            return null;
        }

        return date('n/j/Y', $this->birth_date);
    }

    public function getFacultyId()
    {
        return $this->faculty_id;
    }

	public function getStreetAddress(){
		return $this->loc_address;
	}

    /**
     * Get the domestic looking address of agency.
     */
    public function getLocationAddress()
    {
        $add = array();

        if (!empty($this->loc_address)) {
            $add[] = $this->loc_address . ',';
        }
        if (!empty($this->loc_city)) {
            $add[] = $this->loc_city . ',';
        }
        if(!empty($this->loc_state)){
            $add[] = $this->loc_state;
        }
        if (!empty($this->loc_zip)) {
            $add[] = $this->loc_zip;
        }

        if(!empty($this->loc_province)){
            $add[] = $this->loc_province . ', ';
        }

        if(!empty($this->loc_country)){
            $add[] = $this->loc_country;
        }

        return implode(' ', $add);
    }

    /**
     * Returns the Department's database id
     * @return integer department id
     */
    public function getDepartmentId()
    {
        return $this->department_id;
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
        $this->state = $state->getClassName();
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

        return WorkflowStateFactory::getState($stateName);
    }

    /**
     * Returns array of campus names
     *
     * @return Array campus names
     */
    public static function getCampusAssoc()
    {
        $campusNames = array("main_campus" => "Main campus", "distance_ed" => "Distance Ed");
        return $campusNames;
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

    // TODO - Get rid of the magic values, use constants
    public function getCampusFormatted()
    {
        if($this->getCampus() == 'main_campus') {
            return 'Main campus';
        } else if ($this->getCampus() == 'distance_ed') {
            return 'Distance Ed';
        } else {
            return 'Unknown campus';
        }
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
     * Calculates and sets the metaphone value for this student's middle name.
     *
     * @param string $middleName
     */
    public function setMiddleNameMetaphone($middleName){
        $this->middle_name_meta = metaphone($middleName);
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

    public function getLevelFormatted()
    {
        if($this->getLevel() == Student::UNDERGRAD) {
            return 'Undergraduate';
        } else if ($this->getLevel() == Student::GRADUATE) {
            return 'Graduate';
        } else if ($this->getLevel() == Student::GRADUATE2) {
            return 'Graduate 2';
        }else if ($this->getLevel() == Student::DOCTORAL) {
            return 'Doctoral';
        } else if ($this->getLevel() == Student::POSTDOC) {
            return 'Postdoctoral';
        } else {
            return 'Unknown level';
        }
    }

    public function getMajorCode() {
        return $this->major_code;
    }

    public function getMajorDescription() {
        return $this->major_description;
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

    public function setTerm($term){
        $this->term = $term;
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

    public function getCorequisiteNum(){
        return $this->corequisite_number;
    }

    public function getCorequisiteSection(){
        return $this->corequisite_section;
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

    public function hasStipend() {
        if($this->stipend == 1){
            return true;
        }

        return false;
    }

    public function isCoOp(){
        if($this->co_op == 1){
            return true;
        }

        return false;
    }

    public function getExperienceType(){
        return $this->experience_type;
    }

    public function setExperienceType($type){
        $this->experience_type = $type;
    }

    public function getBackgroundCheck(){
        return $this->background_check;
    }

    public function setBackgroundCheck($check){
        $this->background_check = $check;
    }

    public function getDrugCheck(){
        return $this->drug_check;
    }

    public function setDrugCheck($check){
        $this->drug_check = $check;
    }

    /**
     * Sets the location state (i.e. One of the 50 states of the USA, not the approval status)
     */
    public function setLocationState($state)
    {
        $this->loc_state = $state;
    }

    public function getFormToken()
    {
        return $this->form_token;
    }

    /***********************
     * Static Methods
     ***********************/
    public static function getTypesAssoc()
    {
        return array('internship'       => 'Internship',
                     'student_teaching' => 'Student Teaching',
                     'practicum'        => 'Practicum',
                     'clinical'         => 'Clinical');
    }
}
