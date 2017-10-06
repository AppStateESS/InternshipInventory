<?php
namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\AgencyFactory;
use \Intern\InternshipView;
use \Intern\DataProvider\Student\StudentDataProviderFactory;
use \Intern\TermFactory;
use \Intern\InternSettings;

class ShowInternship {

    public function execute()
    {
        // Make sure an 'internship_id' key is set on the request
        if(!isset($_REQUEST['internship_id'])) {
            \NQ::simple('intern', NotifyUI::ERROR, 'No internship ID was given.');
            \NQ::close();
            \PHPWS_Core::reroute('index.php');
        }

        // Load system settings
        $settings = InternSettings::getInstance();

        // Load the Internship
        try{
            $intern = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
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
            $student = StudentDataProviderFactory::getProvider()->getStudent($intern->getBannerId());
        } catch(\Intern\Exception\StudentNotFoundException $e) {
            $studentId = $intern->getBannerId();
            $student = null;
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't find a student with an ID of {$studentId} in Banner. This probably means this person is not an active student.");
        }

        try {
            $existingCreditHours = StudentDataProviderFactory::getProvider()->getCreditHours($intern->getBannerId(), $intern->getTerm());
        } catch(\Exception $e){
            $studentId = $intern->getBannerId();
            $student = null;
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't get the credit hours for {$studentId}. This probably means this person is not an active student.");
        }

        // Load the WorkflowState
        $wfState = $intern->getWorkflowState();

        // Load the agency
        $agency = AgencyFactory::getAgencyById($intern->getAgencyId());

        // Load the documents
        $docs = $intern->getDocuments();
        if($docs === null) {
            $docs = array(); // if no docs, setup an empty array
        }

        // Load the term info for this internship
        $term = TermFactory::getTermByTermCode($intern->getTerm());

        $view = new InternshipView($intern, $student, $wfState, $agency, $docs, $term, $existingCreditHours, $settings);

        return $view->display();
    }
}
