<?php

namespace Intern\Command;

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
        $terms = Intern\Term::getFutureTerms();
        $departments = array();
        
        
        $view = new \Intern\AddInternshipView();
        
    }
}