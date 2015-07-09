<?php

class DepartmentUI implements UI
{
    public static function display()
    {
        /* Permission check */
        if(!Current_User::allow('intern', Department::getEditPermission())){
            NQ::simple('intern', INTERN_ERROR, "Uh Uh Uh! You didn't say the magic word!");
            return ;
        }

        $tpl = array();       
        javascript('/jquery/');
        javascriptMod('intern', 'manager');
        javascriptMod('intern', 'editDepartment');


        return PHPWS_Template::process($tpl, 'intern', 'edit_department.tpl');

    }

}

?>
