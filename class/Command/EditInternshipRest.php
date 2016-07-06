<?php

namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\AgencyFactory;
use \Intern\InternshipAgencyFactory;
use \Intern\InternshipView;
use \Intern\StudentProviderFactory;
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
            $student = StudentProviderFactory::getProvider()->getStudent($intern->getBannerId(), $intern->getTerm());
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
        $docs = $this->setupDocumentList($docs, $intern->getId());
        //}

        $notes = $this->setupChangeHistory($intern);
        
        $expType = Internship::getTypesAssoc();
        $subjects = array("-1" => "Select subject...") + Subject::getSubjects();

        $content = array("intern" => $intern, "student" => $studentData, "wfState" => $wfState, "agency" => $agencies, "docs" => $docs, "notes" => $notes, "experience_type" => $expType, "subjects" => $subjects);
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
        $data['UPLOAD_DOC'] = $folder->documentUpload($id);

        return $data;
    }

    private function setupChangeHistory($intern)
    {
        $historyView = new \Intern\ChangeHistoryView($intern);
        return $historyView->show();
    }
}

