<?php

/**
 * Class for reporting stuff back from the db
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

 class Sysinventory_Report {
    var $location = NULL;
    var $department = NULL;
    var $office = NULL;
    var $employee = NULL;
    
    function generateReport() {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        
        // Stuff for the template
        $tpl = array();
        $tpl['PAGE_TITLE'] = 'System Report';
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        $tpl['QUERY_LINK'] = PHPWS_Text::moduleLink('New Query','sysinventory',array('action'=>'build_query'));
        // set up the pager
        $pager = &new DBPager('sysinventory_system','Sysinventory_System');
        $pager->setModule('sysinventory');
        $pager->setTemplate('sysinventory_list_results.tpl');
        
        // Make an array of possible request variables
        $fields = array('model',
                        'hdd',
                        'proc',
                        'ram',
                        'dual_mon',
                        'mac',
                        'printer',
                        'staff_member',
                        'username',
                        'telephone',
                        'room_number',
                        'docking_stand',
                        'deep_freeze',
                        'purchase_date',
                        'vlan',
                        'reformat',
                        'notes');

        // These fields must match exactly
        $intfields = array('department_id','location_id');
        foreach ($intfields as $intfield) {
            if(isset($_REQUEST[$intfield]) && $_REQUEST[$intfield] != 0){
                $pager->addWhere($intfield,$_REQUEST[$intfield],'=');
            }
        }

        // determine what other stuff we got from the request and add restrictions for it
        foreach ($fields as $field) {
            if(isset($_REQUEST[$field])){
                $pager->addWhere($field,"%$_REQUEST[$field]%",'LIKE');
            }
        }

        javascript('/jquery/');
        
        $pager->addRowTags('get_row_tags'); 
        $pager->addPageTags($tpl);
        return $pager->get();
    }
 }

?>
