<?php

namespace Intern\UI;
use Intern\Department;
use Intern\AssetResolver;

class DepartmentUI implements UI
{
    public function display()
    {
        /* Permission check */
        if(!\Current_User::allow('intern', 'edit_dept')){
            \NQ::simple('intern', NotifyUI::ERROR, "You do not have permission to edit departments.");
            return ;
        }

        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'editDepartment');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_department.tpl');
    }

}
