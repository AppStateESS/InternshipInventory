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

use Intern\ChangeHistoryView;
use Intern\DepartmentFactory;
use Intern\TermFactory;
use Intern\Command\MajorRest;

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
    private $student;
    private $tpl;

    private $host;
    private $supervisor;
    private $department;
    private $term;
    private $studentExistingCreditHours;

    private $formVals;

    /**
     * Constructor for the big Internship form.
     *
     * @param string $pagetitle
     * @param Internship $i
     */
    public function __construct(Internship $i, Student $student = null, SubHost $host, Supervisor $supervisor, Term $term, $studentExistingCreditHours)
    {
        \Layout::addPageTitle('Edit Internship');

        $this->intern = $i;
        $this->student = $student;

        $this->host = $host;
        $this->supervisor = $supervisor;
        $this->department = $this->intern->getDepartment();
        $this->term = $term;
        $this->studentExistingCreditHours = $studentExistingCreditHours;

        $this->tpl = array();

        $this->tpl['INTERN_ID'] = $this->intern->getId();
        $this->tpl['DEITY_STAT'] = \Current_User::isDeity();

        $this->form = new \PHPWS_Form('internship');
        $this->formVals = array();

        // Build all the form fields
        $this->buildInternshipForm();

        // Plug in the existing values from Internship object (sets default/selected values)
        $this->plugInternship();

        $this->setupChangeHistory();
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
    public function buildInternshipForm() {
        javascript('jquery');
        javascript('jquery_ui');

        // Form Submission setup, only allowed to save if you have permission
        $permAllowed = false;
        $currentState = $this->intern->getWorkflowState();
        $permAllowSave = $currentState->getAllowedPermissionList();
        foreach($permAllowSave as $p){
            if(\Current_User::allow('intern', $p)){
                $permAllowed = true;
            }
        }

        javascriptMod('intern', 'formGoodies', array('perm' => (string)$permAllowed, 'id' => $this->intern->getId()));
        if($permAllowed){
            $this->form->setAction('index.php?module=intern&action=SaveInternship');
            $this->form->addSubmit('submit', 'Save');
        } else{
            $this->form->setAction('index.php?module=intern&action=ShowInternship&internship_id=' . $this->intern->getId());
            $this->form->addSubmit('submit', 'Refresh');
        }

        // Delete button setup
        if (\Current_User::isDeity()) {
            $this->tpl['DELETE_URL'] = 'index.php?module=intern&action=DeleteInternship&internship_id=' . $this->intern->getId();
        }

        /*********************
         * Copy to Next Term *
        *********************/
        if($this->intern->getStateName() != 'DeniedState' && \Current_User::allow('intern', 'create_internship')){
            // Get next three terms
            $term = TermFactory::getTermByTermCode($this->intern->getTerm());

            $nextTerm = TermFactory::getNextTerm($term);

            if($nextTerm !== null){
                $nextTwoTerm = TermFactory::getNextTerm($nextTerm);
            } else {
                $nextTwoTerm = null;
            }

            if($nextTwoTerm !== null){
                $nextThreeTerm = TermFactory::getNextTerm($nextTwoTerm);
            } else {
                $nextThreeTerm = null;
            }

            $this->tpl['CONTINUE_TERM_LIST'] = array();

            // Determine if we can copy to the next term (i.e. the next term exists)
            if($nextTerm !== null){
                $this->tpl['CONTINUE_TERM_LIST'][] = array('DEST_TERM'=>$nextTerm->getTermCode(), 'DEST_TERM_TEXT'=>$nextTerm->getDescription());
            }

            // Copy if it's Spring and exist, else if it's Summer 1 and exist.
            if($nextThreeTerm !== null && $term->getSemesterType() == Term::SPRING){
                $this->tpl['CONTINUE_TERM_LIST'][] = array('DEST_TERM'=>$nextThreeTerm->getTermCode(), 'DEST_TERM_TEXT'=>$nextThreeTerm->getDescription());
            } else if($nextTwoTerm !== null && $term->getSemesterType() == Term::SUMMER1){
                $this->tpl['CONTINUE_TERM_LIST'][] = array('DEST_TERM'=>$nextTwoTerm->getTermCode(), 'DEST_TERM_TEXT'=>$nextTwoTerm->getDescription());
            }

            // If no terms are available to copy to, show a helpful message
            if(sizeof($this->tpl['CONTINUE_TERM_LIST']) == 0) {
                $this->tpl['CONTINUE_TERM_NO_TERMS'] = 'No future terms available.';
            }
        } else if(!\Current_User::allow('intern', 'create_internship')){
            $this->tpl['CONTINUE_TERM_NO_TERMS'] = 'You do not have permission to create new internships.';
        } else{
            $this->tpl['CONTINUE_TERM_NO_TERMS'] = 'No future terms available.';
        }


        /*********************
         * Workflow / Status *
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
        $this->form->addText('student_preferred_name');
        $this->form->setLabel('student_preferred_name', 'Preferred Name');
        $this->form->addCssClass('student_preferred_name', 'form-control');

        $this->form->addText('student_phone');
        $this->form->setLabel('student_phone', 'Phone');
        $this->form->addCssClass('student_phone', 'form-control');

        // checks are need is needed
        $this->form->addCheck('bgcheck');
        $this->form->setMatch('bgcheck', $this->intern->bgcheck);
        $this->form->addCheck('dcheck');
        $this->form->setMatch('dcheck', $this->intern->dcheck);

        if(\Current_User::allow('intern', 'sig_auth_approve')){
            if($this->intern->getBackgroundCheck() == 1){
                $this->tpl['BACK_CHECK_REQUESTED_BTN'] = 'Background Check Requested';
            }else{
                $this->tpl['BACK_CHECK_REQUEST_BTN'] = 'Send Background Check Request';
            }

            if($this->intern->getDrugCheck() == 1){
                $this->tpl['DRUG_CHECK_REQUESTED_BTN'] = 'Drug Screening Requested';
            }else{
                $this->tpl['DRUG_CHECK_REQUEST_BTN'] = 'Send Drug Screening Request';
            }
        }

        /************************
         * Department Drop Down *
         */
        if (\Current_User::isDeity()) {
            if (!is_null($this->intern)){
                $depts = DepartmentFactory::getDepartmentsAssoc($this->intern->department_id);
            } else {
                $depts = DepartmentFactory::getDepartmentsAssoc();
            }
        }else {
            if (!is_null($this->intern)){
                $depts = DepartmentFactory::getDepartmentsAssocForUsername(\Current_User::getUsername(), $this->intern->department_id);
            }else{
                $depts = DepartmentFactory::getDepartmentsAssocForUsername(\Current_User::getUsername());
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
        $this->form->addSelect('faculty', array(-1=>'Select Faculty Supervisor'));
        $this->form->setExtra('faculty', 'disabled');
        $this->form->setLabel('faculty', 'Faculty Supervisor / Instructor of Record');
        $this->form->addCssClass('faculty', 'form-control');

        // Hidden field for selected faculty member
        $this->form->addHidden('faculty_id');

        /***
         * Supervisor info
        */
        $this->form->addText('supervisor_first_name');
        $this->form->setLabel('supervisor_first_name', 'First Name');
        $this->form->addCssClass('supervisor_first_name', 'form-control');

        $this->form->addText('supervisor_last_name');
        $this->form->setLabel('supervisor_last_name', 'Last Name');
        $this->form->addCssClass('supervisor_last_name', 'form-control');

        $this->form->addText('supervisor_title');
        $this->form->setLabel('supervisor_title', 'Title');
        $this->form->addCssClass('supervisor_title', 'form-control');

        $this->form->addText('supervisor_phone');
        $this->form->setLabel('supervisor_phone', 'Phone');
        $this->form->addCssClass('supervisor_phone', 'form-control');

        $this->form->addText('supervisor_email');
        $this->form->setLabel('supervisor_email', 'Email');
        $this->form->addCssClass('supervisor_email', 'form-control');

        $this->form->addCheck('copy_address');
        $this->form->setLabel('copy_address', "Supervisor's information is same as host's");

        $this->form->addText('supervisor_address');
        $this->form->setLabel('supervisor_address', 'Address');
        $this->form->addCssClass('supervisor_address', 'form-control');

        $this->form->addText('supervisor_city');
        $this->form->setLabel('supervisor_city', 'City');
        $this->form->addCssClass('supervisor_city', 'form-control');

        $this->form->addText('supervisor_zip');
        $this->form->addCssClass('supervisor_zip', 'form-control');

        if($this->intern->domestic) {
            $this->form->addSelect('supervisor_state', State::$UNITED_STATES);
            $this->form->setLabel('supervisor_state', 'State');
            $this->form->addCssClass('supervisor_state', 'form-control');

            $this->form->setLabel('supervisor_zip', 'Zip Code');
        } else {
            $countries = CountryFactory::getCountries();
            asort($countries, SORT_STRING);
            $countries = array('-1' => 'Select Country') + $countries;

            $this->form->addText('supervisor_province');
            $this->form->setLabel('supervisor_province', 'Province');
            $this->form->addCssClass('supervisor_province', 'form-control');
            $this->form->addSelect('supervisor_country', $countries);
            $this->form->setLabel('supervisor_country', 'Country');
            $this->form->addCssClass('supervisor_country', 'form-control');
            $this->form->setLabel('supervisor_zip', 'Postal Code');
        }

        $this->form->addText('supervisor_fax');
        $this->form->setLabel('supervisor_fax', 'Fax');
        $this->form->addCssClass('supervisor_fax', 'form-control');

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
        // Remote
        $this->form->addCheck('remote');
        $this->form->setLabel('remote', 'This internship is remote.');

        $this->form->addSelect('remote_state', State::$UNITED_STATES);
        $this->form->setLabel('remote_state', 'Remote State');
        $this->form->addCssClass('remote_state', 'form-control');

        // Phone
        $this->form->addText('host_phone');
        $this->form->addCssClass('host_phone', 'form-control');

        /*************
         * Term Info *
         */
        if (\Current_User::allow('intern', 'change_term')) {
            $terms = TermFactory::getTermsAssoc();
            $this->form->addSelect('term', $terms);
            $this->form->setMatch('term', $this->intern->term);
            $this->form->addCssClass('term', 'form-control');
        }else{
            $this->tpl['TERM'] = $this->term->getDescription();
        }

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
        $subjects = array("-1" => "Select subject...") + Subject::getSubjects();
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

        /*******************
         * Form Token *
         */
        $this->form->addHidden('form_token', $this->intern->getFormToken());
    }

    /**
     * Loads the form's fields with the internship's information.
     */
    public function plugInternship() {
        $this->plugStudent();
        $this->plugDept();
        $this->plugFaculty();
        $this->plugHost();
        $this->plugSupervisor();
        $this->plugInternInfo();
        $this->plugCourseInfo();

        $this->form->setMatch('experience_type', $this->intern->getExperienceType());

        $this->tpl['INTERNSHIP_JSON'] = json_encode($this->intern);

        // Plug
        $this->form->plugIn($this->formVals);

        //Emergency Contacts
        // Display of emergency contacts just requires the 'INTERN_ID' template variable be included. This is located in the constructor.
    }

    private function plugStudent() {
        // Student
        $this->tpl['BANNER'] = $this->intern->getBannerId();
        $this->tpl['STUDENT_FIRST_NAME'] = $this->intern->getFirstName();
        $this->tpl['STUDENT_MIDDLE_NAME'] = $this->intern->middle_name;
        $this->tpl['STUDENT_LAST_NAME'] = $this->intern->getLastName();
        $this->tpl['STUDENT_EMAIL'] = $this->intern->email;
        $this->tpl['STUDENT_GPA'] = $this->intern->getGpa();

        if (\Current_User::isDeity()) {
            $campus = Internship::getCampusAssoc();
            $this->form->addSelect('campus', $campus);
            $this->form->setMatch('campus', $this->intern->campus);
            $this->form->addCssClass('campus', 'form-control');
        }else{
            $this->tpl['CAMPUS'] = $this->intern->getCampusFormatted();
        }

        if (\Current_User::isDeity()) {
            $level = LevelFactory::getLevelList();
            $this->form->addSelect('level', $level);
            $this->form->setMatch('level', $this->intern->level);
            $this->form->addCssClass('level', 'form-control');
        }else{
            $this->tpl['LEVEL'] = $this->intern->getLevelFormatted();
        }


        // Student object can be null, so be sure we actually have a student first
        if(isset($this->student)){
            // Credit Hours
            $creditHours = $this->studentExistingCreditHours;
            if(isset($creditHours)) {
                $this->tpl['ENROLLED_CREDIT_HORUS'] = $creditHours;
            } else {
                $this->tpl['ENROLLED_CREDIT_HORUS'] = '<span class="text-muted"><em>Not Available</em></span>';
            }

            // Grad date
            $gradDate = $this->student->getGradDate();
            if(isset($gradDate)) {
                $this->tpl['GRAD_DATE'] = date('n/j/Y', $this->student->getGradDate());
            } else {
                $this->tpl['GRAD_DATE'] = '<span class="text-muted"><em>Not Available</em></span>';
            }

        } else {
            $this->tpl['ENROLLED_CREDIT_HORUS'] = '<span class="text-muted"><em>Not Available</em></span>';
            $this->tpl['GRAD_DATE'] = '<span class="text-muted"><em>Not Available</em></span>';
        }

        // Major handling -- Shows a selector if there's more than one major
        if(isset($this->student)){
            $majors = $this->student->getMajors();
            $majorsCount = sizeof($majors);
            if($majorsCount == 1) {
                // Only one major, so display it
                $this->tpl['MAJOR'] = $this->intern->getMajorDescription();
            } else if($majorsCount > 1) {
                // Add a repeat for each major
                foreach($majors as $m) {
                    if($this->intern->getMajorCode() == $m->getCode()){
                        $this->tpl['majors_repeat'][] = array('CODE' => $m->getCode(), 'DESC' => $m->getDescription(), 'ACTIVE' => 'active', 'CHECKED' => 'checked');
                    } else {
                        $this->tpl['majors_repeat'][] = array('CODE' => $m->getCode(), 'DESC' => $m->getDescription(), 'ACTIVE' => '', 'CHECKED' => '');
                    }
                }
            } else {
                if($this->intern->getMajorDescription() != null){
                    $this->tpl['MAJOR'] = $this->intern->getMajorDescription();
                } else{
                    $this->tpl['MAJOR'] = '<span class="text-muted"><em>Not Available</em></span>';
                }
            }
        } else {
            if($this->intern->getMajorDescription() != null){
                $this->tpl['MAJOR'] = $this->intern->getMajorDescription();
            } else{
                $this->tpl['MAJOR'] = '<span class="text-muted"><em>Not Available</em></span>';
            }
        }


        $this->formVals['student_preferred_name'] = $this->intern->preferred_name;
        $this->formVals['student_phone'] = $this->intern->phone;
        $this->formVals['campus'] = $this->intern->campus;
    }

    private function plugFaculty() {
        // Faculty Supervisor
        $facultyId = $this->intern->getFacultyId();
        if (isset($facultyId) && $facultyId != 0) {
            $this->formVals['faculty_id'] = $facultyId;
        }
    }

    private function plugHost() {

        if (!\Current_User::isDeity()) {
            $this->tpl['HOST_NAME'] = $this->host->getMainName();
            $host_id = SubHostFactory::getSubHostCond($this->host->main_host_id, $this->host->state, $this->host->country);
            if (!in_array($this->host->sub_name, $host_id)) {
                $host_id[$this->host->id] = $this->host->sub_name;
            }
            $this->form->addSelect('SUB_NAME', $host_id);
            $this->form->setMatch('SUB_NAME', $this->host->id);
            $this->form->addCssClass('SUB_NAME', 'form-control');
        }

        $this->form->addHidden('host_id', $this->host->id);
        $this->tpl['HOST_ADDRESS'] = $this->host->address;
        $this->tpl['HOST_CITY'] = $this->host->city;
        $this->tpl['HOST_ZIP'] = $this->host->zip;
        if($this->intern->domestic) {
            $this->tpl['HOST_STATE'] = $this->host->state;
            $this->tpl['HOST_ZIP_LABEL_TEXT'] = 'Zip Code';
        } else {
            $this->tpl['HOST_PROVINCE'] = $this->host->province;
            $this->tpl['HOST_COUNTRY'] = $this->host->country;
            $this->tpl['HOST_ZIP_LABEL_TEXT'] = 'Postal Code';
        }
    }

    private function plugSupervisor() {
        $this->form->addHidden('supervisor_id', $this->supervisor->id);

        $this->formVals['supervisor_first_name']  = $this->supervisor->supervisor_first_name;
        $this->formVals['supervisor_last_name']   = $this->supervisor->supervisor_last_name;
        $this->formVals['supervisor_title']       = $this->supervisor->supervisor_title;
        $this->formVals['supervisor_phone']       = $this->supervisor->supervisor_phone;
        $this->formVals['supervisor_email']       = $this->supervisor->supervisor_email;
        $this->formVals['supervisor_fax']         = $this->supervisor->supervisor_fax;
        $this->formVals['supervisor_address']     = $this->supervisor->supervisor_address;
        $this->formVals['supervisor_city']        = $this->supervisor->supervisor_city;
        $this->formVals['supervisor_zip']         = $this->supervisor->supervisor_zip;
        if($this->intern->domestic) {
            $this->formVals['supervisor_state']       = $this->supervisor->supervisor_state;
        } else {
            $this->formVals['supervisor_province']    = $this->supervisor->supervisor_province;
            $this->form->setMatch('supervisor_country', $this->supervisor->supervisor_country);
        }
        $this->formVals['copy_address']           = $this->supervisor->address_same_flag == 't';
    }

    private function plugInternInfo() {
        // Internship
        $this->form->addHidden('internship_id', $this->intern->id);
        $this->formVals['start_date'] = $this->intern->start_date ? date('m/d/Y', $this->intern->start_date) : null;
        $this->formVals['end_date'] = $this->intern->end_date ? date('m/d/Y', $this->intern->end_date) : null;

        $this->tpl['TERM_DATES'] =  'Broadest dates of term: <br />' . $this->term->getStartDateFormatted() . ' through ' . $this->term->getEndDateFormatted();

        $this->formVals['credits'] = $this->intern->credits;
        $this->formVals['avg_hours_week'] = $this->intern->avg_hours_week;

        if ($this->intern->isRemote()) {
            $this->form->setMatch('remote', '1');
            $this->formVals['remote_state'] = $this->intern->remote_state;
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
        }
    }

    private function plugCourseInfo()
    {
        // Course Info

        // Remove the subject field and re-add it
        $this->form->dropElement('course_subj');
        $this->form->addSelect('course_subj', array('-1' => 'Select Subject...') + Subject::getSubjects($this->intern->course_subj));
        $this->form->addCssClass('course_subj', 'form-control');
        $this->form->setMatch('course_subj', $this->intern->course_subj);
        $this->formVals['course_no'] = $this->intern->course_no;
        $this->formVals['course_sect'] = $this->intern->course_sect;
        $this->formVals['course_title'] = $this->intern->course_title;
        $this->formVals['host_phone'] = $this->intern->loc_phone;

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

    private function setupChangeHistory()
    {
        $historyView = new ChangeHistoryView($this->intern);
        $this->tpl['CHANGE_LOG'] = $historyView->show();
    }
}
