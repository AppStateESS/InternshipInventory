<?php
/**
 * display the menu page based on what the logged user can do
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/
PHPWS_Core::initModClass('intern', 'UI/UI.php');
class Intern_MenuUI implements UI{

    public static function display() {

        //housekeeping
        if(isset($_SESSION['query'])) unset($_SESSION['query']);

        $tags = array();
        $tags['TITLE']               = "Options";
        $auth = Current_User::getAuthorization();
        $tags['LOGOUT'] = "<a href='$auth->logout_link'>Logout</a>";

        javascript('/modules/intern/menu');

        if(Current_User::allow('intern', 'edit_major')){
            $tags['DEITY']                 = 'Admin Options';
            $tags['EDIT_MAJORS_LINK']      = PHPWS_Text::secureLink('Edit Majors','intern',array('action' => 'edit_majors'));
        }
        if(Current_User::allow('intern', 'edit_grad_prog')){
            $tags['DEITY']                 = 'Admin Options';
            $tags['EDIT_GRAD_LINK']      = PHPWS_Text::secureLink('Edit Graduate Programs','intern',array('action' => 'edit_grad_progs'));
        }

        if(Current_User::isDeity()){
            $tags['DEITY']                 = 'Admin Options';
            $tags['EDIT_MAJORS_LINK']      = PHPWS_Text::secureLink('Edit Majors','intern',array('action' => 'edit_majors'));
            $tags['EDIT_DEPARTMENTS_LINK'] = PHPWS_Text::secureLink('Edit Departments','intern',array('action' => 'edit_departments'));
            $tags['EDIT_ADMINS_LINK']      = PHPWS_Text::secureLink('Edit Administrators','intern',array('action' => 'edit_admins'));
            $tags['GRAND_TOTAL_LABEL']     = _('Total Internships in Database: ');
            $db                            = new PHPWS_DB('intern_internship');
            $gt                            = $db->select('count');
            $tags['GRAND_TOTAL']           = $gt;
        }

        return PHPWS_Template::process($tags,'intern','menu.tpl');
    }
}
?>
