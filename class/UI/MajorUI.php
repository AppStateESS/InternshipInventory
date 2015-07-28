<?php
class MajorUI implements UI
{
    public static function display()
    {
        /* Check if user can add/edit/hide/delete majors. */
        if(!Current_User::allow('intern', 'edit_major') &&
           !Current_User::allow('intern', 'delete_major')){
            NQ::simple('intern', INTERN_WARNING, 'You do not have permission to edit undergraduate majors.');
            return false;
        }

        $tpl = array();
        javascript('/jquery/');
        javascriptMod('intern', 'manager');
        javascriptMod('intern', 'editMajor');

        return PHPWS_Template::process($tpl, 'intern', 'edit_major.tpl');
    }

}
