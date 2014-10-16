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

        // Get list of available (future) terms
        $terms = Term::getFutureTermsAssoc();

        // Get list of departments for the current user
        // If user is a Deity, then get all departments
        if (\Current_User::isDeity()) {
            $departments = \Intern\Department::getDepartmentsAssoc();
        } else {
            $departments = \Intern\Department::getDepartmentsAssocForUsername(\Current_User::getUsername());
        }

        // Get the list of allowed US states
        $states = State::getAllowedStates();

        // Get a list of the countries
        $countries = CountryFactory::getCountries();

        $requestVars = $_GET;
        unset($requestVars['module']);
        unset($requestVars['action']);

        $view = new \Intern\AddInternshipView($terms, $departments, $states, $countries, $requestVars);

        return new \Response($view);
    }
}