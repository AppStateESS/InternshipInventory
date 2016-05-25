<?php

namespace Intern\UI;
use Intern\Department;

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
        \javascript('/jquery/');
        \javascriptMod('intern', 'manager');
        \javascriptMod('intern', 'editDepartment');

        return \PHPWS_Template::process($tpl, 'intern', 'edit_department.tpl');
    }

}
