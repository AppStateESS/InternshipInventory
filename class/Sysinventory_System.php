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
    var $rotation;
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
}

?>
