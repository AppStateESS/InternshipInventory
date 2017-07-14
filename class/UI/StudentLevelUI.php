<?php

namespace Intern\UI;

use \Intern\AssetResolver;

/**
*
* @author Cydney Caldwell
*
*/
class StudentLevelUI implements UI {

    public function display()
    {
        /* Permission check */
        if(!\Current_User::allow('intern', 'edit_level')){
            \NQ::simple('intern', NotifyUI::ERROR, "You do not have permission to edit student levels.");
            return ;
        }

        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editLevel');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_level.tpl');
    }

}
