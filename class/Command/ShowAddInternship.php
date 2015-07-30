<?php

namespace Intern\Command;
use \Intern\Term;
use \Intern\Department;
use \Intern\State;
use \Intern\CountryFactory;

/*
 * ShowAddInternship
 *
 * Controller for showing the Add Internship view.
 */

class ShowAddInternship {

    public function __construct() {

    }

    public function execute()
    {
        // Check permissions
        if(!\Current_User::allow('intern', 'create_internship')){
            \NQ::simple('intern', NotifyUI::ERROR, 'You do not have permission to create new internships.');
            \NQ::close();
            \PHPWS_Core::home();
        }


        $view = new \Intern\AddInternshipView();

        return new \Response($view);
    }
}
