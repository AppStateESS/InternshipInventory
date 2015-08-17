<?php
namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\AgencyFactory;
use \Intern\InternshipView;

class ShowInternship {

    public function execute()
    {
        // Make sure an 'internship_id' key is set on the request
        if(!isset($_REQUEST['internship_id'])) {
            \NQ::simple('intern', NotifyUI::ERROR, 'No internship ID was given.');
            \NQ::close();
            \PHPWS_Core::reroute('index.php');
        }

        // Load the Internship
        try{
            $intern = InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        }catch(InternshipNotFoundException $e){
            \NQ::simple('intern', NotifyUI::ERROR, 'Could not locate an internship with the given ID.');
            return;
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

        $view = new InternshipView($intern, $wfState, $agency, $docs);

        return $view->display();
    }
}
