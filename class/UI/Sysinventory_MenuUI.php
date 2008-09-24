<?php
/**
 * display the menu page based on what the logged user can do
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

 class Sysinventory_MenuUI {
    var $panel = NULL;

    function display() {

        $this->panel = $this->setupPanel();

        $tags = array();
        $tags['TITLE'] = "System Inventory - Menu";
        $tags['SEARCH_LINK'] = PHPWS_Text::secureLink('Search Systems','sysinventory', array('action' => 'build_query'));
        $tags['ADD_SYSTEM_LINK'] = PHPWS_Text::secureLink('Add a System','sysinventory', array('action' => 'add_system'));
        $tags['EDIT_LOCATIONS_LINK'] = PHPWS_Text::secureLink('Edit Locations','sysinventory',array('action' => 'edit_locations'));
        $tags['EDIT_OFFICES_LINK'] = PHPWS_Text::secureLink('Edit Offices','sysinventory',array('action' => 'edit_offices'));

        // Deity Stuff
        if(Current_User::isDeity()){
            $tags['EDIT_DEPARTMENTS_LINK'] = PHPWS_Text::secureLink('Edit Departments','sysinventory',array('action' => 'edit_departments'));
            $tags['EDIT_ADMINS_LINK'] = PHPWS_Text::secureLink('Edit Administrators','sysinventory',array('action' => 'edit_admins'));
            $tags['GRAND_TOTAL_LABEL'] = _('Total Number of Systems in Database: ');
            $db = &new PHPWS_DB('sysinventory_system');
            $gt = $db->select('count');
            $tags['GRAND_TOTAL'] = $gt;
        }

        return PHPWS_Template::process($tags,'sysinventory','menu.tpl');

    }
    
    function setupPanel() {
        $panel = &new phpws_panel('actions');
        $panel->disablesecure();
        $panel->setmodule('sysinventory');
        $panel->setpanel('panel.tpl');
    }

    function getTotalByLocation($location){
        
    }

 }
 ?>
