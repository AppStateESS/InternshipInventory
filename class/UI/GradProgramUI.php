<?php

class GradProgramUI implements UI
{
    public static function display()
    {
        /* Check if user can add/edit/hide/delete grad programs. */
        if(!Current_User::allow('intern', 'edit_grad_prog') && 
           !Current_User::allow('intern', 'delete_grad_prog')){
            NQ::simple('intern', INTERN_WARNING, 'You do not have permission to edit graduate programs.');
            return false;
        }

        
        $tpl = array();       
        javascript('/jquery/');
        javascriptMod('intern', 'manager');
        javascriptMod('intern', 'editGrad');

        return PHPWS_Template::process($tpl, 'intern', 'edit_grad.tpl');
    }

}

?>
