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

    function Sysinventory_System($sysid=0) {
        if ($sysid == 0) return;

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysid);
        $result = $db->loadObject($this);
    }

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

       // edit and delete links
       $rowTags['EDIT'] = PHPWS_Text::moduleLink('Edit','sysinventory',array('action'=>'edit_system','id'=>$this->id,'redir'=>'1'));
       $rowTags['DELETE'] = '<a href="javascript:void(0);" class="delete" id=' . $this->id . '>Delete</a>'; 
       // get department and location names 
       $rowTags['DEPARTMENT'] = $this->getDepartment();
       $rowTags['LOCATION'] = $this->getLocation();

       return $rowTags;
    }

    public function report_row() {
        $row['Department']       = $this->getDepartment();
        $row['Location']         = $this->getLocation();
        $row['Room_Number']      = $this->room_number;
        $row['Model']            = $this->model;
        $row['HDD']              = $this->hdd;
        $row['Processor']        = $this->proc;
        $row['RAM']              = $this->ram;
        $row['Dual_Monitor']     = $this->dual_mon;
        $row['MAC']              = $this->mac;
        $row['Printer']          = $this->printer;
        $row['Staff_Member']     = $this->printer;
        $row['Username']         = $this->username;
        $row['Telephone']        = $this->telephone;
        $row['Docking_Stand']    = $this->docking_stand;
        $row['Deep_Freeze']      = $this->deep_freeze;
        $row['Purchase_Date']    = $this->purchase_date;
        $row['VLAN']             = $this->vlan;
        $row['Reformat']         = $this->reformat;
        $row['Notes']            = $this->notes;

        return $row;
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
