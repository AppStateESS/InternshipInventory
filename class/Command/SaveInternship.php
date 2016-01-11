<?php
namespace Intern\Command;

use \Intern\WorkflowStateFactory;
use \Intern\ChangeHistory;
use \Intern\AgencyFactory;
use \Intern\DatabaseStorage;
use \Intern\StudentProviderFactory;
use \Intern\Exception\StudentNotFoundException;

/**
 * Controller class to save changes (on create or update) to an Internship
 *
 * @author jbooker
 * @package intern
 */
class SaveInternship {

    public function __construct()
    {

    }

    public function execute()
    {
        /**************
         * Sanity Checks
         */

        // Required fields check
        $missing = self::checkRequest();
        if (!is_null($missing) && !empty($missing)) {
            // checkRequest returned some missing fields.
            $url = 'index.php?module=intern&action=ShowInternship';
            $url .= '&missing=' . implode('+', $missing);
            // Restore the values in the fields the user already entered
            foreach ($_POST as $key => $val) {
                $url .= "&$key=$val";
            }
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'Please fill in the highlighted fields.');
            \NQ::close();
            return \PHPWS_Core::reroute($url);
        }

        // Sanity check student email
        if(isset($_REQUEST['student_email']) && preg_match("/@/", $_REQUEST['student_email'])){
            $url = 'index.php?module=intern&action=ShowInternship&missing=student_email';
            // Restore the values in the fields the user already entered
            foreach ($_POST as $key => $val) {
                $url .= "&$key=$val";
            }
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "The student's email address is invalid. No changes were saved. Enter only the username portion of the student's email address. The '@appstate.edu' portion is not necessary.");
            \NQ::close();
            return \PHPWS_Core::reroute($url);
        }

		// Sanity check student zip
		if(isset($_REQUEST['student_zip']) && $_REQUEST['student_zip'] != "" && !preg_match('/^[\d]{5}$|^[\d]{5}-[\d]{4}$/', $_REQUEST['student_zip'])) {
			$url = 'index.php?module=intern&action=ShowInternship&missing=student_zip';
			// Restore the values in the fields the user already entered
			foreach ($_POST as $key => $val){
				$url .= "&$key=$val";
			}
			\NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "The student's zip code is invalid. No changes were saved. The zip code should be 5 digits (no letters, spaces, or punctuation), OR use the extended nine digit form (e.g. 28608-1234).");
			\NQ::close();
			return \PHPWS_Core::reroute($url);
		}

        // Course start date must be before end date
        if(!empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])){
            $start = strtotime($_REQUEST['start_date']);
            $end   = strtotime($_REQUEST['end_date']);

            if ($start > $end) {
                $url = 'index.php?module=intern&action=ShowInternship&missing=start_date+end_date';
                // Restore the values in the fields the user already entered
                unset($_POST['start_date']);
                unset($_POST['end_date']);
                foreach ($_POST as $key => $val) {
                    $url .= "&$key=$val";
                }
                \NQ::simple('intern', Intern\NotifyUI::WARNING, 'The internship start date must be before the end date.');
                \NQ::close();
                return \PHPWS_Core::reroute($url);
            }
        }

		// Sanity check internship location zip
		if((isset($_REQUEST['loc_zip']) && $_REQUEST['loc_zip'] != "") && !is_numeric($_REQUEST['loc_zip'])) {
			$url = 'index.php?module=intern&action=ShowInternship&missing=loc_zip';
			// Restore the values in the fields the user already entered
			foreach ($_POST as $key => $val){
				$url .= "&$key=$val";
			}
			\NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "The internship location's zip code is invalid. No changes were saved. Zip codes should be 5 digits only (no letters, spaces, or punctuation).");
			\NQ::close();
			return \PHPWS_Core::reroute($url);
		}

		// Sanity check agency zip
		if((isset($_REQUEST['agency_zip']) && $_REQUEST['agency_zip'] != "") && !is_numeric($_REQUEST['agency_zip'])) {
			$url = 'index.php?module=intern&action=ShowInternship&missing=agency_zip';
			// Restore the values in the fields the user already entered
			foreach ($_POST as $key => $val){
				$url .= "&$key=$val";
			}
			\NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "The agency's zip code is invalid. No changes were saved. Zip codes should be 5 digits only (no letters, spaces, or punctuation).");
			\NQ::close();
			return \PHPWS_Core::reroute($url);
		}

		// Sanity check supervisor's zip
		if((isset($_REQUEST['agency_sup_zip']) && $_REQUEST['agency_sup_zip'] != "") && !is_numeric($_REQUEST['agency_sup_zip'])) {
			$url = 'index.php?module=intern&action=ShowInternship&missing=agency_sup_zip';
			// Restore the values in the fields the user already entered
			foreach ($_POST as $key => $val){
				$url .= "&$key=$val";
			}
			\NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "The agency supervisor's zip code is invalid. No changes were saved. Zip codes should be 5 digits only (no letters, spaces, or punctuation).");
			\NQ::close();
			return \PHPWS_Core::reroute($url);
		}

		// Sanity check course number
		if((isset($_REQUEST['course_no']) && $_REQUEST['course_no'] != '') && (strlen($_REQUEST['course_no']) > 20 || !is_numeric($_REQUEST['course_no']))) {
			$url = 'index.php?module=intern&action=ShowInternship&missing=course_no';
			// Restore the values in the fields the user already entered
			foreach ($_POST as $key => $val){
				$url .= "&$key=$val";
			}
			\NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "The course number provided is invalid. No changes were saved. Course numbers should be less than 20 digits (no letters, spaces, or punctuation).");
			\NQ::close();
			return \PHPWS_Core::reroute($url);
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

        // Load the student object
        try {
            $student = StudentProviderFactory::getProvider()->getStudent($i->getBannerId(), $i->getTerm());
        } catch (StudentNotFoundException $e){
            $student = null;

            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't find a matching student in Banner. Your changes were saved, but this student probably needs to contact the Registrar's Office to re-enroll.");
			\NQ::close();
        }

        $i->faculty_id = $_REQUEST['faculty_id'] > 0 ? $_REQUEST['faculty_id'] : null;
        $i->department_id = $_REQUEST['department'];
        $i->start_date = !empty($_REQUEST['start_date']) ? strtotime($_REQUEST['start_date']) : 0;
        $i->end_date = !empty($_REQUEST['end_date']) ? strtotime($_REQUEST['end_date']) : 0;
        $i->credits = $_REQUEST['credits'] != '' ? (int) $_REQUEST['credits'] : null;
        $avg_hours_week = (int) $_REQUEST['avg_hours_week'];
        $i->avg_hours_week = $avg_hours_week ? $avg_hours_week : null;
        $i->paid = $_REQUEST['payment'] == 'paid';
        $i->stipend = isset($_REQUEST['stipend']) && $i->paid;
        $i->pay_rate = $_REQUEST['pay_rate'];

        // Internship experience type
        if(isset($_REQUEST['experience_type'])){
            $i->setExperienceType($_REQUEST['experience_type']);
        }

        if($i->isInternational()){
            // Set province
            $i->loc_province = $_POST['loc_province'];
        }

        // Address, city, zip are always set (no matter domestic or international)
        $i->loc_address = strip_tags($_POST['loc_address']);
        $i->loc_city = strip_tags($_POST['loc_city']);
        $i->loc_zip = strip_tags($_POST['loc_zip']);

        if(isset($_POST['course_subj']) && $_POST['course_subj'] != '-1'){
            $i->course_subj = strip_tags($_POST['course_subj']);
        }else{
            $i->course_subj = null;
        }

        // Course info
        $i->course_no = strip_tags($_POST['course_no']);
        $i->course_sect = strip_tags($_POST['course_sect']);
        $i->course_title = strip_tags($_POST['course_title']);

        // Multipart course
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

        // Corequisite Course Info
        if (isset($_POST['corequisite_course_num'])) {
        	$i->corequisite_number = $_POST['corequisite_course_num'];
        }

        if (isset($_POST['corequisite_course_sect'])) {
        	$i->corequisite_section = $_POST['corequisite_course_sect'];
        }

        // Student Information
        $i->first_name = $_REQUEST['student_first_name'];
        $i->middle_name = $_REQUEST['student_middle_name'];
        $i->last_name = $_REQUEST['student_last_name'];

        $i->setFirstNameMetaphone($_REQUEST['student_first_name']);
        $i->setMiddleNameMetaphone($_REQUEST['student_middle_name']);
        $i->setLastNameMetaphone($_REQUEST['student_last_name']);

        $i->phone = $_REQUEST['student_phone'];
        $i->email = $_REQUEST['student_email'];

        $i->student_address = $_REQUEST['student_address'];
        $i->student_city = $_REQUEST['student_city'];
        if($_REQUEST['student_state'] != '-1'){
            $i->student_state = $_REQUEST['student_state'];
        }else{
            $i->student_state = "";
        }
        $i->student_zip = $_REQUEST['student_zip'];

        // Student major handling, if more than one major
        // Make sure we have a student object, since it could be null if the Banner lookup failed
        if(isset($student) && $student != null) {
            $majors = $student->getMajors();
        } else {
            $majors = array();
        }

        if(sizeof($majors) > 1) {

            if(!isset($_POST['major_code'])){
                // Student has multiple majors, but user didn't choose one, so just take the first one
                $i->major_code = $majors[0]->getCode();
                $i->major_description = $majors[0]->getDescription();
            }else{
                // User choose a major, so loop over the set of majors until we find the matching major code
                $code = $_POST['major_code'];
                foreach($majors as $m){
                    if($m->getCode() == $code){
                        $major = $m;
                        break;
                    }
                }

                $i->major_code = $major->getCode();
                $i->major_description = $major->getDescription();
            }
        } else if(sizeof($majors) == 1){
            // Student has exactly one major
            $i->major_code = $majors[0]->getCode();
            $i->major_description = $majors[0]->getDescription();

        }

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

        // Update agency
        try {
            $agency = AgencyFactory::getAgencyById($_REQUEST['agency_id']);
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        // Agency Info
        $agency->name = $_REQUEST['agency_name'];
        $agency->address = $_REQUEST['agency_address'];
        $agency->city = $_REQUEST['agency_city'];
        $agency->zip = $_REQUEST['agency_zip'];
        $agency->phone = $_REQUEST['agency_phone'];

        if($i->isDomestic()){
            $agency->state = $_REQUEST['agency_state'] == '-1' ? null : $_REQUEST['agency_state'];
        } else {
            $agency->province = $_REQUEST['agency_province'];
            $agency->country = $_REQUEST['agency_country']== '-1' ? null : $_REQUEST['agency_country'];
        }

        // Agency Supervisor Info
        $agency->supervisor_first_name = $_REQUEST['agency_sup_first_name'];
        $agency->supervisor_last_name = $_REQUEST['agency_sup_last_name'];
        $agency->supervisor_title = $_REQUEST['agency_sup_title'];
        $agency->supervisor_phone = $_REQUEST['agency_sup_phone'];
        $agency->supervisor_email = $_REQUEST['agency_sup_email'];
        $agency->supervisor_fax = $_REQUEST['agency_sup_fax'];
        $agency->supervisor_address = $_REQUEST['agency_sup_address'];
        $agency->supervisor_city = $_REQUEST['agency_sup_city'];
        $agency->supervisor_zip = $_REQUEST['agency_sup_zip'];
        if($i->isDomestic()){
            $agency->supervisor_state = $_REQUEST['agency_sup_state'];
        } else {
            $agency->supervisor_province = $_REQUEST['agency_sup_province'];
            $agency->supervisor_country = $_REQUEST['agency_sup_country'] == '-1' ? null : $_REQUEST['agency_sup_country'];
        }
        $agency->address_same_flag = isset($_REQUEST['copy_address']) ? 't' : 'f';

        try {
            DatabaseStorage::save($agency);
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        /***************************
         * State/Workflow Handling *
        ***************************/
        $t = \Intern\WorkflowTransitionFactory::getTransitionByName($_POST['workflow_action']);
        $workflow = new \Intern\WorkflowController($i, $t);
        try {
            $workflow->doTransition(isset($_POST['notes'])?$_POST['notes']:null);
        } catch (\Intern\Exception\MissingDataException $e) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, $e->getMessage());
            \NQ::close();
            return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $i->id);
        }

        // Create a ChangeHisotry for the OIED certification.
        if($oiedCertified){
            $currState = WorkflowStateFactory::getState($i->getStateName());
            $ch = new ChangeHistory($i, \Current_User::getUserObj(), time(), $currState, $currState, 'Certified by OIED');
            $ch->save();
        }

        \PHPWS_DB::commit();

        $workflow->doNotification(isset($_POST['notes'])?$_POST['notes']:null);

        //var_dump($_POST['generateContract']);exit;

        // If the user clicked the 'Generate Contract' button, then redirect to the PDF view
        if(isset($_POST['generateContract']) && $_POST['generateContract'] == 'true'){
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
    private static function checkRequest()
    {
        $vals = null;

        foreach (\Intern\InternshipView::$requiredFields as $field) {
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

        return $vals;
    }
}
