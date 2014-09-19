<?php

namespace Intern;

/*
 * View for interface to Add an Internship
 */
class AddInternshipView implements \View {

    private $terms;
    private $departments;

    public function __construct(Array $terms, Array $departments)
    {
        $this->terms = $terms;
        $this->departments = $departments;
    }

    public function render()
    {
        $tpl = array();


        return \PHPWS_Template::process($tpl, 'intern', 'addInternship.tpl');
    }

    public function getContentType()
    {
        return 'text/html';
    }
}
?>
