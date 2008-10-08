<?php
class Sysinventory_System {
    var $id;
    var $location_id;
    var $department_id;
    var $room_number;
    var $model;
    var $hdd;
    var $proc;
    var $ram;
    var $dual_mon;
    var $mac;
    var $printer;
    var $staff_member;
    var $username;
    var $telephone;
    var $docking_stand;
    var $deep_freeze;
    var $purchase_date;
    var $vlan;
    var $reformat;
    var $notes;

    function save() {
        $db = new PHPWS_DB('sysinventory_system');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }

    function deleteSystem($sysId) {
        if (!isset($sysId)) {
            return 'No System ID set.';
        }

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysId);
        $result = $db->delete();

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        if($db->affectedRows() == 1) {
            return 'true';
        }else{
            return 'Database Error';
        }
    }
    function get_row_tags() {
       $rowTags = array();
       // Fix the bools
       if(isset($this->dual_mon)) $rowTags['DUAL_MON'] = 'Yes'; else $rowTags['DUAL_MON'] = 'No';
       if(isset($this->docking_stand)) $rowTags['DOCKING_STAND'] = 'Yes'; else $rowTags['DOCKING_STAND'] = 'No';
       if(isset($this->reformat)) $rowTags['REFORMAT'] = 'Yes'; else $rowTags['REFORMAT'] = 'No';
       if(isset($this->deep_freeze)) $rowTags['DEEP_FREEZE'] = 'Yes'; else $rowTags['DEEP_FREEZE'] = 'No';

       // edit and delete links
       $rowTags['EDIT'] = PHPWS_Text::moduleLink('Edit','sysinventory',array('action'=>'edit_system','systemid'=>$this->id));
       $rowTags['DELETE'] = '<a href="javascript:void(0);" class="delete" id=' . $this->id . '>Delete</a>'; 
       // get department and location names 
       $rowTags['DEPARTMENT'] = $this->getDepartment();
       $rowTags['LOCATION'] = $this->getLocation();

       return $rowTags;
    }

    function getDepartment() {
        $db = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$this->department_id);
        $dept = $db->select('row');
        return $dept['description'];
    }

    function getLocation() {
        $db = new PHPWS_DB('sysinventory_location');
        $db->addWhere('id',$this->location_id);
        $loc = $db->select('row');
        return $loc['description'];
    }
}

?>
