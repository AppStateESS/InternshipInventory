<?php

namespace Intern\Command;
use \Intern;

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
        $terms = Intern\Term::getFutureTermsAssoc();
        $departments = array();
        
        
        $view = new \Intern\AddInternshipView();
        
    }
}