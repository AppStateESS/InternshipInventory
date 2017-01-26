<?php
namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\AgencyFactory;
use \Intern\InternshipView;
use \Intern\ExternalDataProviderFactory;
use \Intern\TermProviderFactory;

class ShowInternship {

    public function test()
    {

        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

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
            $student = ExternalDataProviderFactory::getProvider()->getStudent($intern->getBannerId(), $intern->getTerm());
        } catch(\Intern\Exception\StudentNotFoundException $e) {
            $studentId = $intern->getBannerId();
            $student = null;
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, "We couldn't find a student with an ID of {$studentId} in Banner. This probably means this person is not an active student.");
        }

        $tpl = array();
  
        $tpl['vendor_bundle'] = \Intern\AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = \Intern\AssetResolver::resolveJsPath('assets.json', 'internshipView');
        $tpl['INTERN_ID'] = $_REQUEST['internship_id'];

        return \PHPWS_Template::process($tpl, 'intern', 'editInternshipView.tpl');


/*


        // Load the term info for this internship
        $termProvider = TermProviderFactory::getProvider();
        $termInfo = $termProvider->getTerm($intern->getTerm());

        $view = new InternshipView($intern, $student, $wfState, $agency, $docs, $termInfo);

        return $view->display();
        */
    }
}
