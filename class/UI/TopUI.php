<?php

/**
 * TopUI
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class TopUI implements UI
{
    public static function display(){
    }
    
    public static function plug()
    {
        $tpl['HOME_LINK']    = PHPWS_Text::moduleLink('Menu', 'intern');
        $tpl['ADD_LINK']     = PHPWS_Text::moduleLink('Add Student', 'intern', array('action' => 'edit_internship'));
        $tpl['SEARCH_LINK']  = PHPWS_Text::moduleLink('Search', 'intern', array('action' => 'search'));
        $auth = Current_User::getAuthorization();

        $tpl['USER_FULL_NAME'] = Current_User::getDisplayName();
        $tpl['LOGOUT_URI'] = $auth->logout_link;
        
        
        $adminOptions = array();
        
        // Edit list of majors
        if(Current_User::allow('intern', 'edit_major')){
            $adminOptions['EDIT_MAJORS_LINK'] = PHPWS_Text::secureLink('Edit Undergraduate Majors','intern',array('action' => MAJOR_EDIT));
        }
        
        // Edit list grad programs
        if(Current_User::allow('intern', 'edit_grad_prog')){
            $adminOptions['EDIT_GRAD_LINK'] = PHPWS_Text::secureLink('Edit Graduate Programs','intern',array('action' => GRAD_PROG_EDIT));
        }
        
        // Edit departments
        if(Current_User::allow('intern', 'edit_dept')){
            $adminOptions['EDIT_DEPARTMENTS_LINK'] = PHPWS_Text::secureLink('Edit Departments','intern',array('action' => DEPT_EDIT));
        }
        
        // Edit list of states
        if(Current_User::allow('intern', 'edit_states')){
            $adminOptions['EDIT_STATES_LINK'] = PHPWS_Text::secureLink('Edit States','intern',array('action' => STATE_EDIT));
        }
        
        if(Current_User::isDeity()){
            $adminOptions['CONTROL_PANEL']         = PHPWS_Text::secureLink('Control Panel','controlpanel');
            $adminOptions['EDIT_ADMINS_LINK']      = PHPWS_Text::secureLink('Edit Administrators','intern',array('action' => 'edit_admins'));
        }
        
        // If any admin options were added, them show the dropdown and merge those
        // links into the main set of template tags
        if(sizeof($adminOptions) > 0){
            $tpl['ADMIN_OPTIONS'] = ''; // dummy var to show dropdown menu in template
            $tpl = array_merge($tpl, $adminOptions);
        }
       
        Layout::plug(PHPWS_Template::process($tpl, 'intern', 'top.tpl'), 'NAV_LINKS');
    }
}

?>
