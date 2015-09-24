<?php

namespace Intern;

/*
 * View for interface to Add an Internship
 */
class AddInternshipView implements \View {

    public function __construct()
    {
    }

    public function render()
    {
        $tpl = array();

        \javascript('jquery');
        \javascriptMod('intern', 'missing');

        return \PHPWS_Template::process($tpl, 'intern', 'addInternship.tpl');
    }

    public function getContentType()
    {
        return 'text/html';
    }
}
?>
