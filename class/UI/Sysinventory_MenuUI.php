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
        
        // Deity Stuff
        if(isset($isDeity)){
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
