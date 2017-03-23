<?php

namespace Intern;

/*
 * View for interface to Add an Internship
 */
class AddInternshipView {

    public function __construct()
    {
    }

    public function render()
    {
        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'createInterface');

        return \PHPWS_Template::process($tpl, 'intern', 'addInternship.tpl');
    }

    public function getContentType()
    {
        return 'text/html';
    }
}
