<?php

namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\AgencyFactory;
use \Intern\InternshipAgencyFactory;
use \Intern\InternshipView;
use \Intern\StudentFactory;
use \Intern\Internship;
use \Intern\Subject;

class EditInternshipRest {

	public function execute()
	{

		switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->post();
                exit;
            case 'DELETE':
                $this->delete();
                exit;
            case 'GET':
            	$data = $this->get();
				echo (json_encode($data));
				exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
	}

	public function post()
	{
        $req = \Server::getCurrentRequest();
        $postarray = json_decode($req->getRawData(), true);


        // id, internId, agencyId, loc_start, loc_end, loc_address, loc_city, loc_zip, loc_province, loc_country
// echo("<pre>");
// var_dump($postarray);
// echo("</pre>");
// exit;
        $studentPost    = $postarray["internship"]["student"];
        $faculty        = $postarray['internship']['faculty'];
        $status         = $postarray['internship']['status'];
        $term           = $postarray['internship']['term'];
        $type           = $postarray['internship']['type'];

         \PHPWS_DB::begin();

        /********************************
         * Load the existing internship *
         */
        try {
            $i = \Intern\InternshipFactory::getInternshipById($studentPost['id']);
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }

/*
        // Check that the form token matched before we save anything
        if($i->form_token == $_REQUEST['form_token']) {
            // Generate a new form token
            $i->form_token = uniqid();
        } else {
            // Form token doesn't match, so show a nice error message
            $this->rerouteWithError('index.php?module=intern&action=ShowInternship', 'Some else has modified this internship while you were working. In order to not overwrite their changes, your changes were not saved.');
        }
*/


        // Load the student object
        try {
            $student = ExternalDataProviderFactory::getProvider()->getStudent($i->getBannerId(), $i->getTerm());
        } catch (StudentNotFoundException $e){
            $student = null;

            $this->rerouteWithError('index.php?module=intern&action=ShowInternship', "We couldn't find a matching student in Banner. Your changes were saved, but this student probably needs to contact the Registrar's Office to re-enroll.");
            \NQ::close();
        }

        // Student Information Field
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


        /************************
        * Faculty Advisor Field *
        *************************/
        $i->faculty_id = $faculty['faculty_id'] > 0 ? $faculty['faculty_id'] : null;
        $i->department_id = $studentPost['department'];

        // Term & Course Information Field
        // TERM START AND END DATE
        $i->start_date = !empty($term['termStart']) ? strtotime($term['termStart']) : 0;
        $i->end_date = !empty($term['termEnd']) ? strtotime($term['termEnd']) : 0;

        // Course info
        $i->course_no = !isset($_POST['course_no']) ? null : strip_tags($_POST['course_no']);
        $i->course_sect = !isset($_POST['course_sect']) ? null : strip_tags($_POST['course_sect']);
        $i->course_title = !isset($_POST['course_title']) ? null : strip_tags($_POST['course_title']);

//Ensure this is the correct creditHours field..
        $i->credits = (int) $term['creditHours'];

        // Compensation Field
        $avg_hours_week = (int) $_REQUEST['avg_hours_week'];
        $i->avg_hours_week = $avg_hours_week ? $avg_hours_week : null;
        $i->paid = $_REQUEST['payment'] == 'paid';
        $i->stipend = isset($_REQUEST['stipend']) && $i->paid;
        $i->pay_rate = $_REQUEST['pay_rate'];

        if (\Current_User::isDeity()) {
            $i->term = $_REQUEST['term'];
        }

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


        // Corequisite Course Info
/*      UNSURE ABOUT THIS BLOCK
        if (isset($_POST['corequisite_course_num'])) {
            $i->corequisite_number = $_POST['corequisite_course_num'];
        }

        if (isset($_POST['corequisite_course_sect'])) {
            $i->corequisite_section = $_POST['corequisite_course_sect'];
        }
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

        /************
         * Background and Drug checks
        */
        // Check if this has changed from no to yes for sending email
        if($i->background_check == 0 && $_REQUEST['background_code'] == '1'){
            // note the change for later
            $backgroundCheck = true;
        }else{
            $backgroundCheck = false;
        }

        if($_REQUEST['background_code'] == '1'){
            $i->background_check = 1;
        }else if($_REQUEST['background_code'] == '0'){
            $i->background_check = 0;
        }

        if($i->drug_check == 0 && $_REQUEST['drug_code'] == '1'){
            // note the change for later
            $drugCheck = true;
        }else{
            $drugCheck = false;
        }

        if($_REQUEST['drug_code'] == '1'){
            $i->drug_check = 1;
        }else if($_REQUEST['drug_code'] == '0'){
            $i->drug_check = 0;
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

            // Notify the faculty member that OIED has certified the internship
            if ($i->getFaculty() != null) {
                \Intern\Email::sendOIEDCertifiedNotice($i, $agency);
            }
        }

        // If the background check or drug check status changed to true (computed earlier), then send a notification
        if($backgroundCheck || $drugCheck) {
            \Intern\Email::sendBackgroundCheckEmail($i, $agency, $backgroundCheck, $drugCheck);
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


        echo("<pre>");
        var_dump($postarray);
        echo("</pre>");
        exit;
	}

	public function delete()
	{

	}

	public function get()
	{
		// Load the Internship
        try{
            $intern = InternshipFactory::getInternshipById($_REQUEST['internshipId']);
        }catch(\Intern\Exception\InternshipNotFoundException $e){
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'Could not locate an internship with the given ID.');
            return;
        }

        if($intern === false) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'Could not locate an internship with the given ID.');
            //TODO redirect to the search interface
            return;
        }

        // Load a fresh copy of the student data from the web service
        try {

            $student = StudentFactory::getStudent($intern->getBannerId(), $intern->getTerm());


        } catch(\Intern\Exception\StudentNotFoundException $e) {
            $studentId = $intern->getBannerId();
            $student = null;
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't find a student with an ID of {$studentId} in Banner. This probably means this person is not an active student.");
        }

        // Format intern data
        $intern = $this->formatIntern($intern);

        $state = $intern->getWorkflowState();
        // Load the WorkflowState
        $transitions = $state->getTransitions($intern);

        $workflow = array('status'=>$state->getFriendlyName());

        // Generate the array of radio buttons to add (one for each possible transition)
        $radioButtons = array();

        foreach($transitions as $t){
            $radioButtons[$t->getName()] = $t->getActionName();
        }

        $workflow['workflowAction'] = $radioButtons;
        $workflow['allow'] = true;

        if(!\Current_User::allow('intern', 'oied_certify') || $intern->isDomestic()){
            $workflow['allow'] = false;
        }

        $wfState = $workflow;

        $agencies = InternshipAgencyFactory::getHostInfoById($intern->getId());

        // foreach($agencies as $a){
        //     // Load the agency
        //     var_dump(AgencyFactory::getAgencyById($a['agency_id']));
        //     //$agencies[] += AgencyFactory::getAgencyById($a['agency_id']);
        // }
        // //var_dump($agencies);
        // exit;

        // Grab Student Data
        $studentData = $this->getStudentData($student, $intern);

        // Load the documents
        $docs = $intern->getDocuments();
        // if($docs === null) {
        //     $docs = array(); // if no docs, setup an empty array
        // } else {
        $doc = $this->setupDocumentList($docs, $intern->getId());
        //}

        $notes = $this->setupChangeHistory($intern);

        $expType = Internship::getTypesAssoc();
        $subjects = array("-1" => "Select subject...") + Subject::getSubjects();

        $content = array("intern" => $intern, "student" => $studentData, "wfState" => $wfState, "agency" => $agencies, "docs" => $doc, "notes" => $notes, "experience_type" => $expType, "subjects" => $subjects);
        return $content;
	}

	public function formatIntern($intern)
	{
		$birthday = $intern->getBirthDateFormatted();
        if(is_null($birthday)) {
            $intern->birth_date = null;
        } else {
            $intern->birth_date = $birthday;
        }

        $intern->campus = $intern->getCampusFormatted();
        $intern->level = $intern->getLevelFormatted();
        $intern->level = $intern->getLevelFormatted();

		return $intern;
	}

	public function getStudentData($student, $intern)
	{
		$data = array();

		// Student object can be null, so be sure we actually have a student first
        // TODO: newer PHP versions provide syntax to clean up this logic
        if(isset($student)){
            // Credit Hours
            $creditHours = $student->getCreditHours();
            if(isset($creditHours))
            {
            	$data['enrolled_credit_hours'] = $creditHours;
            }else{
            	$data['enrolled_credit_hours'] = null;
               // $this->tpl['ENROLLED_CREDIT_HORUS'] = '<span class="text-muted"><em>Not Available</em></span>';
            }


            // Grad date
            $gradDate = $student->getGradDate();
            if(isset($gradDate))
            {
            	$data['grad_date'] = date('n/j/Y', $gradDate);
            }else{
            	$data['grad_date'] = null;
                //$this->tpl['GRAD_DATE'] = '<span class="text-muted"><em>Not Available</em></span>';
            }

            // Major selector
            $majors = $student->getMajors();
            $majorsCount = sizeof($majors);
            if($majorsCount == 1) {
                // Only one major, so display it
                $data['major'] = $intern->getMajorDescription();
            } else if($majorsCount > 1) {
                // Add a repeat for each major
                foreach($majors as $m) {
                    if($intern->getMajorCode() == $m->getCode()){
                        $data['majors_repeat'][] = array('code' => $m->getCode(), 'desc' => $m->getDescription(), 'active' => 'active', 'checked' => 'checked');
                    } else {
                        $data['majors_repeat'][] = array('code' => $m->getCode(), 'desc' => $m->getDescription(), 'active' => '', 'checked' => '');
                    }
                }
            }
        } else {
        	$data['enrolled_credit_hours'] = null;
        	$data['grad_date'] = null;
        	$data['major'] = null;
            //$this->tpl['ENROLLED_CREDIT_HORUS'] = '<span class="text-muted"><em>Not Available</em></span>';
            //$this->tpl['GRAD_DATE'] = '<span class="text-muted"><em>Not Available</em></span>';
        }
        return $data;
	}

    private function setupDocumentList($docs, $id)
    {
        $data = array();

        // Document list
        if (!is_null($docs)) {
            foreach ($docs as $doc) {
                $data['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink('blah'),
                                             'DELETE' => $doc->getDeleteLink());
            }
        }

        // Document upload button
        $folder = new \Intern\InternFolder(\Intern\InternDocument::getFolderId());
        $data = $folder->documentUpload($id);

        return $data;
    }

    private function setupChangeHistory($intern)
    {
        $historyView = new \Intern\ChangeHistoryView($intern);
        return $historyView->show();
    }
}
