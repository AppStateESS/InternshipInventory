<?php

/**
 * Class for reporting stuff back from the db
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

 class Sysinventory_Report {
    var $location   = NULL;
    var $department = NULL;
    var $office     = NULL;
    var $employee   = NULL;
    
    function generateReport($data) {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        
        // Stuff for the template
        $tpl = array();
        $tpl['PAGE_TITLE']      = 'System Report';
        $tpl['HOME_LINK']       = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        $tpl['QUERY_LINK']      = PHPWS_Text::moduleLink('New Query','sysinventory',array('action'=>'build_query'));
        $tpl['ADD_SYSTEM_LINK'] = PHPWS_Text::moduleLink('Add New System','sysinventory',array('action'=>'edit_system'));

        // set up the pager
        $pager = &new DBPager('sysinventory_system','Sysinventory_System');
        $pager->setModule('sysinventory');
        $pager->setTemplate('sysinventory_list_results.tpl');
        $pager->setReportRow('report_row');
        $pager->allowPartialReport(false);
        
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

        // Set up the array for the session...
        $query = array();

        // These fields must match exactly
        $intfields = array('department_id','location_id');
        foreach ($intfields as $intfield) {
            if(isset($data[$intfield]) && $data[$intfield] != 0){
                $pager->addWhere($intfield,$data[$intfield],'=');
                $query[$intfield] = $data[$intfield];
            }
        }
        
        // Need to make "department_id" match only departmens one is an admin of if they're not a deity...
        if(isset($data['department_id']) && $data['department_id'] == 0 && !Current_User::isDeity()) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
            $deps = Sysinventory_Department::getDepartmentsByUsername();
            foreach($deps as $dept) {
                $pager->addWhere('department_id',$dept['id'],'=');
            }
        }

        // determine what other stuff we got from the request and add restrictions for it
        foreach ($fields as $field) {
            if(isset($data[$field])){
                $pager->addWhere($field,"%$data[$field]%",'LIKE');
                $query[$field] = $data[$field];
            }
        }
        
        // now session that request
        $_SESSION['query'] = $query;

        javascript('/jquery/');
        
        $pager->addRowTags('get_row_tags'); 
        $pager->addPageTags($tpl);

        return $pager->get();
    }
 }

?>
