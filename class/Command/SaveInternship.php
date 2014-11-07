<?php
namespace Intern\Command;

use \Intern\WorkflowStateFactory;
use \Intern\ChangeHistory;
use \Intern\AgencyFactory;
use \Intern\DatabaseStorage;

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
		if((isset($_REQUEST['student_zip']) && $_REQUEST['student_zip'] != "") && (strlen($_REQUEST['student_zip']) != 5 || !is_numeric($_REQUEST['student_zip']))) {
			$url = 'index.php?module=intern&action=ShowInternship&missing=student_zip';
			// Restore the values in the fields the user already entered
			foreach ($_POST as $key => $val){
				$url .= "&$key=$val";
			}
			\NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "The student's zip code is invalid. No changes were saved. Zip codes should be 5 digits only (no letters, spaces, or punctuation).");
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
		if((isset($_REQUEST['loc_zip']) && $_REQUEST['loc_zip'] != "") && (strlen($_REQUEST['loc_zip']) != 5 || !is_numeric($_REQUEST['loc_zip']))) {
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
		if((isset($_REQUEST['agency_zip']) && $_REQUEST['agency_zip'] != "") && (strlen($_REQUEST['agency_zip']) != 5 || !is_numeric($_REQUEST['agency_zip']))) {
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
		if((isset($_REQUEST['agency_sup_zip']) && $_REQUEST['agency_sup_zip'] != "") && (strlen($_REQUEST['agency_sup_zip']) != 5 || !is_numeric($_REQUEST['agency_sup_zip']))) {
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

        // Update agency
        try {
            $agency = AgencyFactory::getAgencyById($_REQUEST['agency_id']);
        } catch (Exception $e) {
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

        $agency->state = $_REQUEST['agency_state'] == '-1' ? null : $_REQUEST['agency_state'];
        $agency->province = $_REQUEST['agency_province'];
        $agency->country = $_REQUEST['agency_country']== '-1' ? null : $_REQUEST['agency_country'];

        // Agency Supervisor Info
        $agency->supervisor_first_name = $_REQUEST['agency_sup_first_name'];
        $agency->supervisor_last_name = $_REQUEST['agency_sup_last_name'];
        $agency->supervisor_title = $_REQUEST['agency_sup_title'];
        $agency->supervisor_phone = $_REQUEST['agency_sup_phone'];
        $agency->supervisor_email = $_REQUEST['agency_sup_email'];
        $agency->supervisor_fax = $_REQUEST['agency_sup_fax'];
        $agency->supervisor_address = $_REQUEST['agency_sup_address'];
        $agency->supervisor_city = $_REQUEST['agency_sup_city'];
        $agency->supervisor_state = $_REQUEST['agency_sup_state'];
        $agency->supervisor_zip = $_REQUEST['agency_sup_zip'];
        $agency->supervisor_province = $_REQUEST['agency_sup_province'];
        $agency->supervisor_country = $_REQUEST['agency_sup_country'] == '-1' ? null : $_REQUEST['agency_sup_country'];
        $agency->address_same_flag = isset($_REQUEST['copy_address']) ? 't' : 'f';

        try {
            DatabaseStorage::save($agency);
        } catch (Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        /**********************************
         * Save the Internship
         */
        // User is editing internship
        try {
            $i = \Intern\InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        } catch (Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        $i->faculty_id = $_REQUEST['faculty_id'] > 0 ? $_REQUEST['faculty_id'] : null;
        $i->department_id = $_REQUEST['department'];
        $i->start_date = !empty($_REQUEST['start_date']) ? strtotime($_REQUEST['start_date']) : 0;
        $i->end_date = !empty($_REQUEST['end_date']) ? strtotime($_REQUEST['end_date']) : 0;
        $credits = (int) $_REQUEST['credits'];
        $i->credits = $credits ? $credits : null;
        $avg_hours_week = (int) $_REQUEST['avg_hours_week'];
        $i->avg_hours_week = $avg_hours_week ? $avg_hours_week : null;
        $i->paid = $_REQUEST['payment'] == 'paid';
        $i->stipend = isset($_REQUEST['stipend']) && $i->paid;
        $i->unpaid = $_REQUEST['payment'] == 'unpaid';
        $i->pay_rate = $_REQUEST['pay_rate'];

        // Internship experience type
        if(isset($_REQUEST['experience_type'])){
            $i->setExperienceType($_REQUEST['experience_type']);
        }

        // Set ields depending on domestic/international
        /*
        if($_REQUEST['location'] == 'domestic'){
            // Set Flags
            //$i->domestic      = 1;
            $i->international = 0;

            // Set state
            if ($_POST['loc_state'] != '-1') {
                $i->loc_state = strip_tags($_POST['loc_state']);
            } else {
                $i->loc_state = null;
            }

            // Clear province, country
            $i->loc_province  = '';
            $i->loc_country   = '';
        }else
        */
        if($_REQUEST['location'] == 'internat'){
            // Set flags
            //$i->domestic      = 0;
            //$i->international = 1;

            // Set province, country
            $i->loc_province = $_POST['loc_province'];
            $i->loc_country = strip_tags($_POST['loc_country']);

            // Clear state
            //$i->loc_state = null;
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
        $i->setLastNameMetaphone($_REQUEST['student_last_name']);

        $i->phone = $_REQUEST['student_phone'];
        $i->email = $_REQUEST['student_email'];

        $i->gpa = $_REQUEST['student_gpa'];
        //$i->campus = $_REQUEST['campus'];

        $i->student_address = $_REQUEST['student_address'];
        $i->student_address2 = $_REQUEST['student_address2'];
        $i->student_city = $_REQUEST['student_city'];
        if($_REQUEST['student_state'] != '-1'){
            $i->student_state = $_REQUEST['student_state'];
        }else{
            $i->student_state = "";
        }
        $i->student_zip = $_REQUEST['student_zip'];

        /*
        $i->emergency_contact_name = $_REQUEST['emergency_contact_name'];
        $i->emergency_contact_relation = $_REQUEST['emergency_contact_relation'];
        $i->emergency_contact_phone = $_REQUEST['emergency_contact_phone'];
        */

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
        } catch (Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

        \PHPWS_DB::commit();

        /***************************
         * State/Workflow Handling *
        ***************************/
        $t = \Intern\WorkflowTransitionFactory::getTransitionByName($_POST['workflow_action']);
        $workflow = new \Intern\WorkflowController($i, $t);
        try {
            $workflow->doTransition(isset($_POST['notes'])?$_POST['notes']:null);
        } catch (MissingDataException $e) {
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

        $workflow->doNotification(isset($_POST['notes'])?$_POST['notes']:null);

        // Show message if user edited internship
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Saved internship for ' . $i->getFullName());
        \NQ::close();
        return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $i->id);
    }

    /**
     * Check that required fields are in the REQUEST.
     */
    private static function checkRequest()
    {
        $vals = null;

        foreach (\Intern\UI\InternshipUI::$requiredFields as $field) {
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
