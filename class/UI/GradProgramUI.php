<?php

namespace Intern\UI;

use Intern\GradProgram;
use Intern\AssetResolver;

class GradProgramUI implements UI
{
    public function display()
    {
        /* Check if user can add/edit/hide/delete grad programs. */
        if(!\Current_User::allow('intern', 'edit_grad_prog') &&
           !\Current_User::allow('intern', 'delete_grad_prog')){
            \NQ::simple('intern', NotifyUI::WARNING, 'You do not have permission to edit graduate programs.');
            return false;
        }

        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editGrad');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_grad.tpl');
    }

}
