<?php

PHPWS_Core::initModClass('intern', 'UI/UI.php');

/**
 * Display the menu page based on what the current (logged in) user can do
 * @author	Micah Carter <mcarter at tux dot appstate dot edu>
 * @author	Jeremy Booker <jbooker at tux dot appstate dot edu>
 * @package	intern
 */
class InternMenu implements UI{

	/**
	 * Main display method
	 */
    public function display() {
        javascript('jquery');
        
        PHPWS_Core::initModClass('intern', 'Major.php');

        //housekeeping
        if(isset($_SESSION['query'])) unset($_SESSION['query']);

        $tags = array();
        $auth = Current_User::getAuthorization();

        $adminOptions = array();
        
        $tags['EXAMPLE_LINK']      = PHPWS_Text::secureLink('Example form','intern',array('action' => 'example_form'));
        
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
        
        // Edit faculty members for allowed departments
        //if(Current_User::allow('intern', 'edit_faculty') || Current_User::isDeity())
        //{
        	$adminOptions['EDIT_FACULTY'] = PHPWS_Text::secureLink('Edit Faculty Members', 'intern', array('action'=>'edit_faculty'));
        //}
        
        if(Current_User::isDeity()){
        	$tags['CONTROL_PANEL']         = PHPWS_Text::secureLink('Control Panel','controlpanel');
        	$tags['EDIT_ADMINS_LINK']      = PHPWS_Text::secureLink('Edit Administrators','intern',array('action' => 'edit_admins'));
        	$tags['GRAND_TOTAL_LABEL']     = _('Total Internships in Database: ');
        
        	$db = new PHPWS_DB('intern_internship');
        	$gt = $db->select('count');
        	$tags['GRAND_TOTAL'] = $gt;
        }
        
        if(!empty($adminOptions)){
        	$tags['ADMIN_BOX'] = 'Admin Options';
        	$tags = array_merge($tags, $adminOptions);
        }
        
        return PHPWS_Template::process($tags,'intern','menu.tpl');
    }
}
?>
