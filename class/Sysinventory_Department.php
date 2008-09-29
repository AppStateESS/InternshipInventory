<?php
/**
 * Class for adding and editing departments
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Sysinventory_Department {
    
    var $id = NULL;
    var $description = NULL;
    var $last_update = NULL;
    
    function showDepartments() {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_DepartmentUI.php');
        $disp = &new Sysinventory_DepartmentUI;
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }

    function get_row_tags() {
        $template = array();
        $template['ID'] = $this->getID();
        $template['DESCRIPTION'] = $this->getDescription();
        $template['LAST_UPDATE'] = $this->getLastUpdate();
        return $template;
    }

    function addDepartment($depName) {
        $db = &new PHPWS_DB('sysinventory_department');
        $db->addValue('id','NULL');
        $db->addValue('description',$depName);
        $db->addValue('last_update',date('U'));
        $result = $db->insert();
    }

    function getID() {
        return $this->id;
    }

    function getDescription() {
        return $this->description;
    }

    function getLastUpdate() {
        return $this->last_update;
    }

    function setID($newid) {
        $this->id = $newid;
    }

    function setDescription($newdesc) {
        $this->description = $newdesc;
    }

    function setLastUpdate($newupd) {
        $this->last_update = $newupd;
    }
}
?>
