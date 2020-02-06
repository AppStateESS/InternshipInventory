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

namespace Intern\Command;

use \Intern\WorkflowStateFactory;
use \Intern\ChangeHistory;
use \Intern\DatabaseStorage;
use \Intern\DataProvider\Student\StudentDataProviderFactory;
use \Intern\TermFactory;
use \Intern\SupervisorFactory;
use \Intern\Exception\StudentNotFoundException;

/**
 * Controller class to save changes (on create or update) to an Internship
 *
 * @author jbooker
 * @package intern
 */
class SaveInternship {

    public function __construct(){}

    private function rerouteWithError($url, $errorMessage)
    {
        // Restore the values in the fields the user already entered
        foreach ($_POST as $key => $val) {
            $url .= "&$key=$val";
        }

        \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, $errorMessage);
        \NQ::close();

        return \PHPWS_Core::reroute($url);
    }

    public function execute() {
        // We don't want to do certain things like state change or error checks if this is an ajax request.
        $isAjax = false;
        if(\Canopy\Request::isAjax()){
            $isAjax = true;
        }
        /**************
         * Sanity Checks
         */
        // Required fields check
        $missing = self::checkRequest();
        if (!is_null($missing) && !empty($missing)) {
            // checkRequest returned some missing fields.
            $url = 'index.php?module=intern&action=ShowInternship';
            $url .= '&missing=' . implode('+', $missing);

            $this->rerouteWithError($url, 'Please fill in the highlighted fields.');
        }

        // Course start date must be before end date
        if (!empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])) {
            $start = strtotime($_REQUEST['start_date']);
            $end = strtotime($_REQUEST['end_date']);

            if ($start >= $end) {
                if(!$isAjax){
                    $url = 'index.php?module=intern&action=ShowInternship&missing=start_date+end_date';
                    // Restore the values in the fields the user already entered
                    unset($_POST['start_date']);
                    unset($_POST['end_date']);
                    $this->rerouteWithError($url,
                            'The internship start date must be before the end date.');
                }
            }
        }

        // Sanity check internship supervisor zip, allows a-z or A-Z if international
        if ((isset($_REQUEST['supervisor_zip']) && $_REQUEST['supervisor_zip'] != "") && !is_numeric($_REQUEST['supervisor_zip'])) {
            if (!($_REQUEST['location'] == 'international' && preg_match('/[\w]/',
                            $_REQUEST['supervisor_zip']))) {
                $url = 'index.php?module=intern&action=ShowInternship&missing=supervisor_zip';
                $this->rerouteWithError($url,
                        "The supervisor's zip code is invalid. No changes were saved. Zip codes should be 5 digits only (no letters, spaces, or punctuation).");
            }
        }

        // Sanity check course number
        if ((isset($_REQUEST['course_no']) && $_REQUEST['course_no'] != '') && (strlen($_REQUEST['course_no']) > 20 || !is_numeric($_REQUEST['course_no']))) {
            $url = 'index.php?module=intern&action=ShowInternship&missing=course_no';
            $this->rerouteWithError($url,
                    "The course number provided is invalid. No changes were saved. Course numbers should be less than 20 digits (no letters, spaces, or punctuation).");
        }

        \PHPWS_DB::begin();

        /********************************
         * Load the existing internship *
         */
        try {
            $i = \Intern\InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        // Check that the form token matched before we save anything
        if ($i->form_token == $_REQUEST['form_token']) {
            // Generate a new form token
            $i->form_token = uniqid();
        } else {
            // Form token doesn't match, so show a nice error message
            $this->rerouteWithError('index.php?module=intern&action=ShowInternship',
                    'Someone else has modified this internship while you were working. In order to not overwrite their changes, your changes were not saved.');
        }

        // Load the student object
        try {
            $student = StudentDataProviderFactory::getProvider()->getStudent($i->getBannerId());
        } catch (StudentNotFoundException $e) {
            $student = null;
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't find a matching student in Banner. Your changes were saved, but this student probably needs to contact the Registrar's Office to re-enroll.");
            \NQ::close();
        }

        $i->faculty_id = $_REQUEST['faculty_id'] > 0 ? $_REQUEST['faculty_id'] : null;
        $i->department_id = $_REQUEST['department'];
        $i->start_date = !empty($_REQUEST['start_date']) ? strtotime($_REQUEST['start_date']) : 0;
        $i->end_date = !empty($_REQUEST['end_date']) ? strtotime($_REQUEST['end_date']) : 0;
        $i->credits = isset($_POST['multipart']) && isset($_POST['secondary_part']) ? null : (int) $_REQUEST['credits'];
        $avg_hours_week = (int) $_REQUEST['avg_hours_week'];
        $i->avg_hours_week = $avg_hours_week ? $avg_hours_week : null;
        $i->paid = $_REQUEST['payment'] == 'paid';
        $i->stipend = isset($_REQUEST['stipend']) && $i->paid;
        $i->pay_rate = self::trimField($_REQUEST['pay_rate']);
        $i->loc_phone = self::trimField($_REQUEST['host_phone']);

        if (\Current_User::allow('intern', 'change_term')) {
            $i->term = $_REQUEST['term'];
        }

        $term = TermFactory::getTermByTermCode($i->term);

        // Internship experience type
        if (isset($_REQUEST['experience_type'])) {
            $i->setExperienceType($_REQUEST['experience_type']);
        }

        //Location Data
        $i->host_sub_id = $_REQUEST['SUB_NAME'];

        // Save Country if international
        /*if (\Current_User::isDeity()) {
            $i->sub_host = $_REQUEST['sub_host'];
        }*/

        if (isset($_POST['course_subj']) && $_POST['course_subj'] != '-1') {
            $i->course_subj = strip_tags($_POST['course_subj']);
        } else {
            $i->course_subj = null;
        }

        // Course info
        $i->course_no = !isset($_POST['course_no']) ? null : self::trimField(strip_tags($_POST['course_no']));
        $i->course_sect = !isset($_POST['course_sect']) ? null : self::trimField(strip_tags($_POST['course_sect']));
        $i->course_title = !isset($_POST['course_title']) ? null : self::trimField(strip_tags($_POST['course_title']));

        // Multipart course
        if (isset($_POST['multipart'])) {
            $i->multi_part = 1;
        } else {
            $i->multi_part = 0;
        }

        if (isset($_POST['multipart']) && isset($_POST['secondary_part'])) {
            $i->secondary_part = 1;
        } else {
            $i->secondary_part = 0;
        }

        // Corequisite Course Info
        if (isset($_POST['corequisite_course_num'])) {
            $i->corequisite_number = $_POST['corequisite_course_num'];
        }

        if (isset($_POST['corequisite_course_sect'])) {
            $i->corequisite_section = $_POST['corequisite_course_sect'];
        }

        // Student Information
        $i->preferred_name = self::trimField($_REQUEST['student_preferred_name']);
        $i->setPreferredNameMetaphone(self::trimField($_REQUEST['student_preferred_name']));
        $i->phone = self::trimField($_REQUEST['student_phone']);

        if (\Current_User::isDeity()) {
            $i->campus = $_REQUEST['campus'];
        }

        // Student major handling, if more than one major
        // Make sure we have a student object, since it could be null if the Banner lookup failed
        if (isset($student) && $student != null) {
            $majors = $student->getMajors();
        } else {
            $majors = array();
        }

        if (sizeof($majors) > 1) {

            if (!isset($_POST['major_code'])) {
                // Student has multiple majors, but user didn't choose one, so just take the first one
                $i->major_code = $majors[0]->getCode();
                $i->major_description = $majors[0]->getDescription();
            } else {
                // User choose a major, so loop over the set of majors until we find the matching major code
                $code = $_POST['major_code'];
                foreach ($majors as $m) {
                    if ($m->getCode() == $code) {
                        $major = $m;
                        break;
                    }
                }

                $i->major_code = $major->getCode();
                $i->major_description = $major->getDescription();
            }
        } else if (sizeof($majors) == 1) {
            // Student has exactly one major
            $i->major_code = $majors[0]->getCode();
            $i->major_description = $majors[0]->getDescription();
        }

        /************
         * OIED Certification
         */
        // Check if this has changed from non-certified->certified so we can log it later
        if ($i->oied_certified == 0 && $_POST['oied_certified_hidden'] == 'true') {
            // note the change for later
            $oiedCertified = true;
        } else {
            $oiedCertified = false;
        }

        if ($_POST['oied_certified_hidden'] == 'true') {
            $i->oied_certified = 1;
        } else if ($_POST['oied_certified_hidden'] == 'false') {
            $i->oied_certified = 0;
        } else {
            $i->oied_certified = 0;
        }

        /************
         * Background and Drug checks
         * Now handled in otherGoodies.js
         */

        // If we don't have a state and this is a new internship,
        // then set an initial state
        if ($i->id == 0 && is_null($i->state)) {
            $state = WorkflowStateFactory::getState('CreationState');
            $i->setState($state); // Set this initial value
        }

        try {
            $i->save();
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        try {
            $supervisor = SupervisorFactory::getSupervisorById($_REQUEST['supervisor_id']);
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        //Supervisor Info
        $supervisor->supervisor_first_name = self::trimField($_REQUEST['supervisor_first_name']);
        $supervisor->supervisor_last_name = self::trimField($_REQUEST['supervisor_last_name']);
        $supervisor->supervisor_title = self::trimField($_REQUEST['supervisor_title']);
        $supervisor->supervisor_phone = self::trimField($_REQUEST['supervisor_phone']);
        $supervisor->supervisor_email = self::trimField($_REQUEST['supervisor_email']);
        $supervisor->supervisor_fax = self::trimField($_REQUEST['supervisor_fax']);
        $supervisor->supervisor_address = self::trimField($_REQUEST['supervisor_address']);
        $supervisor->supervisor_city = self::trimField($_REQUEST['supervisor_city']);
        $supervisor->supervisor_zip = $_REQUEST['supervisor_zip'];
        if ($i->isDomestic()) {
            $supervisor->supervisor_state = $_REQUEST['supervisor_state'] == '-1' ? null : $_REQUEST['supervisor_state'];
        } else {
            $supervisor->supervisor_province = self::trimField($_REQUEST['supervisor_province']);
            $supervisor->supervisor_country = $_REQUEST['supervisor_country'] == '-1' ? null : $_REQUEST['supervisor_country'];
        }
        $supervisor->address_same_flag = isset($_REQUEST['copy_address']) ? 't' : 'f';

        try {
            DatabaseStorage::save($supervisor);
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        //Commit to save changes in case of workflow error
        \PHPWS_DB::commit();
        \PHPWS_DB::begin();

        /** *************************
         * State/Workflow Handling *
         * ************************* */
        if(!$isAjax){
            if(!isset($_POST['workflow_action'])){
                $this->rerouteWithError('index.php?module=intern&action=ShowInternship',
                        'The saving of the current workflow status is unknown.');
            }
            $t = \Intern\WorkflowTransitionFactory::getTransitionByName($_POST['workflow_action']);
            $workflow = new \Intern\WorkflowController($i, $t);
            try {
                $workflow->doTransition(isset($_POST['notes']) ? $_POST['notes'] : null);
            } catch (\Intern\Exception\MissingDataException $e) {
                \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, $e->getMessage());
                \NQ::close();
                return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $i->id);
            }

            // Create a ChangeHisotry for the OIED certification.
            if ($oiedCertified) {
                $currState = WorkflowStateFactory::getState($i->getStateName());
                $ch = new ChangeHistory($i, \Current_User::getUserObj(), time(),
                        $currState, $currState, 'Certified by OIED');
                $ch->save();

                // Notify the faculty member that OIED has certified the internship
                if ($i->getFaculty() != null) {
                    $email = new \Intern\Email\OIEDCertifiedEmail(\Intern\InternSettings::getInstance(), $i, $term);
                    $email->send();
                }
            }

            \PHPWS_DB::commit();

            $workflow->doNotification(isset($_POST['notes']) ? $_POST['notes'] : null);
        }

        // If the user clicked the 'Generate Contract' button, then redirect to the PDF view
        if (isset($_POST['generateContract']) && $_POST['generateContract'] == 'true') {
            //return \PHPWS_Core::reroute('index.php?module=intern&action=pdf&internship_id=' . $i->id);
            echo json_encode($i);
            exit;
        } else {
            // Otherwise, redirect to the internship edit view
            // Show message if user edited internship
            \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Saved internship for ' . $i->getFullName());
            \NQ::close();

            return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $i->id);
        }
    }

    /**
     * Check that required fields are in the REQUEST.
     */
    private static function checkRequest() {
        $vals = null;

        foreach (\Intern\InternshipView::$requiredFields as $field) {
            /* If not set or is empty (For text fields) */
            if (!isset($_REQUEST[$field]) || $_REQUEST[$field] == '') {
                $vals[] = $field;
            }
        }

        /* Required select boxes should not equal -1 */
        if (!isset($_REQUEST['department']) || $_REQUEST['department'] == -1) {
            $vals[] = 'department';
        }

        return $vals;
    }

    /**
     *  Trim fields for all user input text fields
     */
    private static function trimField(string $info)
    {
        //trims whitespaces from beginning and end of string
        $info = trim($info);
        //trims extra spaces from middle of two words
        $info = preg_replace('!\s+!', ' ', $info);
        return $info;
    }

}
