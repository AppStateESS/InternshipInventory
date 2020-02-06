<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern;

use \Intern\Student;
use \Intern\SubHostFactory;
use \Intern\SupervisorFactory;
use \Intern\Command\DocumentRest;

use \PHPWS_Text;

/**
* Internship
*
* Forms relationship between a student, department, and host.
*
* @author Robert Bost <bostrt at tux dot appstate dot edu>
* @author Jeremy Booker <jbooker at tux dot appstate dot edu>
* @package Intern
*/
class Internship {

    const GPA_MINIMUM = 2.00;

    public $id;

    // Host & sup
    public $host_id;
    public $host_sub_id;
    public $supervisor_id;

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
    public $preferred_name;

    // Metaphones for fuzzy search
    public $first_name_meta;
    public $middle_name_meta;
    public $last_name_meta;
    public $preferred_name_meta;

    // Academic info
    public $level;
    public $gpa;
    public $campus;
    public $major_code;
    public $major_description;

    // Contact Info
    public $phone;
    public $email; // NB: Username, without a domain name

    // Location data
    public $domestic;
    public $international;
    public $loc_state;
    public $loc_country;
    public $loc_phone;

    // Term Info
    public $term;
    public $start_date;
    public $end_date;
    public $credits;
    public $avg_hours_week;
    public $paid;
    public $stipend;
    public $pay_rate;


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
    public function __construct(Student $student, $term, $location, $state, $country, Department $department, SubHost $sub_host, Supervisor $supervisor){
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

        // Get host
        $this->host_id = $sub_host->getMainId();
        $this->host_sub_id = $sub_host->getId();
        $this->supervisor_id = $supervisor->getId();

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
        $this->banner         = $student->getStudentId();
        $this->email          = $student->getUsername();
        $this->first_name     = $student->getFirstName();
        $this->middle_name    = $student->getMiddleName();
        $this->last_name      = $student->getLastName();
        $this->preferred_name = $student->getPreferredName();

        $this->setFirstNameMetaphone($student->getFirstName());
        $this->setMiddleNameMetaphone($student->getMiddleName());
        $this->setLastNameMetaphone($student->getLastName());
        $this->setPreferredNameMetaphone($student->getPreferredName());

        // Academic info
        $this->level = $student->getLevel();
        $this->campus = $student->getCampus();
        $this->gpa = $student->getGpa();

        // Majors - If double major, just take index 0
        $majors = $student->getMajors();
        if(is_array($majors) && sizeof($majors) > 0) {
            $this->major_code = $majors[0]->getCode();
            $this->major_description = $majors[0]->getDescription();
        } else if(is_object($majors)) {
            $this->major_code = $majors->getCode();
            $this->major_description = $majors->getDescription();
        }// Else, there were no majors set in the Student object

        // Contact Info
        $this->phone = $student->getPhone();
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
     * Get a CSV formatted for this internship.
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
        $csv['Banner ID']      = $this->banner;
        $csv['First Name']     = $this->first_name;
        $csv['Middle Name']    = $this->middle_name;
        $csv['Last Name']      = $this->last_name;
        $csv['Preferred Name'] = $this->preferred_name;

        // Academic Info
        $csv['Level'] = $this->getLevel();
        $level = LevelFactory::getLevelObjectById($this->getLevel());
        if($level->getLevel() == Level::UNDERGRAD){
            $csv['Undergrad Major'] = $this->major_description;
            $csv['Graduate Program'] = '';
        }else if($level->getLevel() == Level::GRADUATE){
            $csv['Undergrad Major'] = '';
            $csv['Graduate Program'] = $this->major_description;
        }else{
            $csv['Undergrad Major'] = '';
            $csv['Graduate Program'] = '';
        }
        $csv['GPA']             = $this->getGpa();
        $csv['Campus']          = $this->getCampus();

        // Status Info
        $csv['Status']                 = $this->getWorkflowState()->getFriendlyName();
        $csv['OIED Certified']         = $this->isOiedCertified() == 1 ? 'Yes' : 'No';

        // Student Academic Info
        $csv['Phone #']     = $this->phone;
        $csv['Email']       = $this->email;

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
        $csv['Host Phone']             = $this->loc_phone;

        // Gets host information
        $s = $this->getHost();
        if ($s instanceof SubHost) {
            $csv = array_merge($csv, $s->getCSV());
        } else{
            $csv['Host Name'] = '';
            $csv['Host Sub Name'] = '';
            $csv['Host Address'] = '';
            $csv['Host City'] = '';
            $csv['Host State'] = '';
            $csv['Host Province'] = '';
            $csv['Host Zip Code'] = '';
            $csv['Host Country'] = '';
        }

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
        $f = $this->getFaculty();
        $d = $this->getDepartment();
        $c = DocumentRest::contractAffilationSelected($this->id);

        // Sets the type and if there are contracts, else sets the name of affiliation if one
        $csv['Agreement Type'] = $c['type'];
        if($c['type'] == "contract") {
            $csv['Contract Uploaded']  = $c['value'];
            $csv['Affiliation Uploaded']  = null;
        } else {
            $csv['Contract Uploaded'] = null;
            $csv['Affiliation Uploaded']  = $c['value'];
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
        $level = LevelFactory::getLevelObjectById($this->getLevel());
        if($level->getLevel() == Level::UNDERGRAD) {
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
        $level = LevelFactory::getLevelObjectById($this->getLevel());
        if($level->getLevel() == Level::GRADUATE) {
            return true;
        }
        return false;
    }

    public function getHostId() {
        return $this->host_id;
    }

    public function getSubId() {
        return $this->host_sub_id;
    }

    /**
    * Get the Host object associated with this internship.
    */
    public function getHost() {
        return SubHostFactory::getSubById($this->getSubId());
    }

    public function getSupervisorId() {
        return $this->supervisor_id;
    }

    public function setSupervisorId($sup_id) {
        $this->supervisor_id = $sup_id;
    }

    /**
    * Get the Supervisor object associated with this internship.
    */
    public function getSupervisor()
    {
        return SupervisorFactory::getSupervisorById($this->getSupervisorId());
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
    public function setInternational($international) {
        $this->international = $international;
    }

    public function getLocationState() {
        return $this->loc_state;
    }

    public function getLocationCountry() {
        return $this->loc_country;
    }

    /**
    * Sets the country code for this internship. Should be a two letter abbreviation.
    */
    public function setLocationCountry($country) {
        if(!isset($country) || $country == '') {
            throw new \InvalidArgumentException('Empty country code');
        }

        if(strlen($country) > 2) {
            throw new \InvalidArgumentException('Country code is too long');
        }

        $this->loc_country = $country;
    }

    public function isOiedCertified() {
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

    public function isMultipart() {
        if($this->multi_part == 1){
            return true;
        }else{
            return false;
        }
    }

    public function isSecondaryPart() {
        if($this->secondary_part == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
    * Row tags for DBPager
    */
    public function getRowTags() {
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

    public function getLocCountry() {
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

    public function getFacultyId() {
        return $this->faculty_id;
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
    public function getStateName() {
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
    public function getWorkflowState() {
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
    public static function getCampusAssoc() {
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
    public function getCampus() {
        return $this->campus;
    }

    // TODO - Get rid of the magic values, use constants
    public function getCampusFormatted() {
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
    public function isDistanceEd() {
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
     * Calculates and sets the metaphone value for this student's preferred name.
     *
     * @param string $preferredName
     */
    public function setPreferredNameMetaphone($preferredName){
        $this->preferred_name_meta = metaphone($preferredName);
    }

    /**
     * Returns this student's level code ('U' or 'G' ...)
     *
     * @return string
     */
    public function getLevel(){
        return $this->level;
    }

    public function getLevelFormatted() {
        $levelE = LevelFactory::checkLevelExist($this->level);
        if($levelE){
            $levelD = LevelFactory::getLevelObjectById($this->level);
            return $levelD->getDesc();
        } else{
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

    public function getPreferredName(){
        return $this->preferred_name;
    }

    public function setPreferredName($pname){
        $this->preferred_name = $pname;
    }

    /**
    * Sets the location state (i.e. One of the 50 states of the USA, not the approval status)
    */
    public function setLocationState($state) {
        $this->loc_state = $state;
    }

    public function getFormToken() {
        return $this->form_token;
    }

    /***********************
    * Static Methods
    ***********************/
    public static function getTypesAssoc() {
        return array('internship'       => 'Internship',
        'student_teaching' => 'Student Teaching',
        'practicum'        => 'Practicum',
        'clinical'         => 'Clinical');
    }
}
