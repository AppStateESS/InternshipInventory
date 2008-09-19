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
    
    function doReport() {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        
        // set up the pager
        $pager = &new DBPager('sysinventory_system','Sysinventory_System');
        $pager->setModule('sysinventory');
        $pager->setTemplate('sysinventory_list_results.tpl');
        
        // Make an array of possible request variables
        $fields = array('model',
                        'hdd',
                        'proc',
                        'ram',
                        'dual_monitor',
                        'mac',
                        'printer',
                        'staff_member',
                        'username',
                        'telephone',
                        'room',
                        'docking_stand',
                        'deep_freeze',
                        'purchase_date',
                        'rotation');

        // determine what stuff we got from the request and add restrictions for it
        foreach ($fields as $field) {
            if(isset($_REQUEST[$field])){
                $pager->addWhere($field,$_REQUEST[$field]);
            }
        }
        return $pager->get();
    }
    /**
     * Accessor / Mutator Methods
     */

    //pass these an ID, not a description
    function location($loc) {
        if (!$loc){
            return $this->location;
        }else{
            $this->location = $loc;
            return $this->location;
        }
    }

    function department($dpt) {
        if (!$dpt){
            return $this->department;
        }else{
            $this->department = $dpt;
            return $this->department;
        }
    }
    
    function office($off) {
        if (!$off){
            return $this->office;
        }else{
            $this->office = $off;
            return $this->office;
        }
 }

?>
