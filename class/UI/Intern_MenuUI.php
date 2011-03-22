<?php
/**
 * display the menu page based on what the logged user can do
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

 class Intern_MenuUI {

    function display() {

        //housekeeping
        if(isset($_SESSION['query'])) unset($_SESSION['query']);

        $tags = array();
        $tags['TITLE']                  = "Options";
        $tags['SEARCH_LINK']            = PHPWS_Text::secureLink('Search Systems','sysinventory', array('action' => 'build_query'));
        $tags['ADD_SYSTEM_LINK']        = PHPWS_Text::secureLink('Add a System','sysinventory', array('action' => 'edit_system'));
        $tags['EDIT_LOCATIONS_LINK']    = PHPWS_Text::secureLink('Edit Locations','sysinventory',array('action' => 'edit_locations'));

        // Deity Stuff
        if(Current_User::isDeity()){
            $tags['DEITY']                     = 'Admin Options';
            $tags['EDIT_DEPARTMENTS_LINK']     = PHPWS_Text::secureLink('Edit Departments','intern',array('action' => 'edit_departments'));
            $tags['EDIT_ADMINS_LINK']          = PHPWS_Text::secureLink('Edit Administrators','sysinventory',array('action' => 'edit_admins'));
            $tags['EDIT_DEFAULT_LINK']         = PHPWS_Text::secureLink('Edit Default Systems','sysinventory',array('action'=>'edit_default'));
            $tags['GRAND_TOTAL_LABEL']         = _('Total Number of Systems in Database: ');
            $db = new PHPWS_DB('sysinventory_system');
            $gt = $db->select('count');
            $tags['GRAND_TOTAL']               = $gt;
        }

        return PHPWS_Template::process($tags,'intern','menu.tpl');

    }
 }
 ?>
