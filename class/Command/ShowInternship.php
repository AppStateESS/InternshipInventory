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

        

        $tpl = array();
        //javascriptMod('intern', 'editInternshipView');

        javascript('jquery');
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
