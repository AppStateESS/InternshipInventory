<?php

namespace Intern\UI;
use \Intern\AssetResolver;

class MajorUI implements UI
{
    public function display()
    {
        /* Check if user can add/edit/hide/delete majors. */
        if(!\Current_User::allow('intern', 'edit_major') &&
           !\Current_User::allow('intern', 'delete_major')){
            \NQ::simple('intern', NotifyUI::WARNING, 'You do not have permission to edit undergraduate majors.');
            return false;
        }

        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editMajor');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_major.tpl');
    }
}
