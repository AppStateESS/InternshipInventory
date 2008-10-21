<?php
/**
 * Class for adding and editing departments
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Sysinventory_Department {
    
    public $id = NULL;
    public $description = NULL;
    public $last_update = NULL;

    public function Sysinventory_Department($id = NULL){
        if(is_null($id)) return;
        $db     = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$id,'=');
        $result = $db->loadObject($this);
    }

    public function save(){
        if(isset($this->description)) {
            $db = new PHPWS_DB('sysinventory_department');
            return $db->saveObject($this);
        }
        return FALSE;
    }
    
    public function delete(){
        $db = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$this->getID());
        $result = $db->delete();
        if(PHPWS_Error::logIfError($result)){
            return FALSE; 
        }

        return TRUE;
    }
    
    public function update() {
        if(!is_null($this->id)) {
            $this->last_update = time();
            $result = $this->save();
        }
    }

    /********************
     * Static functions *
     ********************/

    function showDepartments($whatToDo,$department) {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_DepartmentUI.php');
        $disp = new Sysinventory_DepartmentUI;
        if ($whatToDo == 'addDep' && isset($department)) {
            Sysinventory_Department::addDepartment($department);
        }
        else if ($whatToDo == 'delDep' && isset($department)) {
            Sysinventory_Department::delDepartment($department);
        }
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }

    function get_row_tags() {
        $template = array();
        $template['LAST_UPDATE'] = date("r",$this->getLastUpdate());
        $template['DELETE'] = PHPWS_Text::moduleLink('Delete','sysinventory',array('action'=>'edit_departments','delDep'=>TRUE,'id'=>$this->getID()));
        return $template;
    }

    function addDepartment($depName) {
        if (!isset($depName)) return;
        $dep = new Sysinventory_Department();
        $dep->setDescription($depName);
        $dep->setLastUpdate(time());
        $result = $dep->save();
        if(PHPWS_Error::logIfError($result)) {
            $error = "Could not delete department.";
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Error.php');
            Sysinventory_Error::error($error);
        }
        return;
    }

    function delDepartment($depId) {
        $dep = new Sysinventory_Department($depId);
        $result = $dep->delete();
        if(!$result) {
            $error = "Could not delete department.";
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Error.php');
            Sysinventory_Error::error($error);
        }

    }

    function getDepartmentsByUsername(){
        // if a user is a deity, they get everything...
         if(Current_User::isDeity()){
            $db = &new PHPWS_DB('sysinventory_department');
            $db->addColumn('description');
            $db->addColumn('id');
            $list = $db->select();

            if (PEAR::isError($list)){
                PHPWS_Error::log($list);
            }
            return $list;
        // otherwise return a list of departments of which they're an admin   
        }else if(Current_User::allow('sysinventory','admin')){
            $db = new PHPWS_DB('sysinventory_admin');
            $db->addWhere('username',Current_User::getUsername(),'ILIKE');
            $db->addJoin('left outer','sysinventory_admin','sysinventory_department','department_id','id');
            $db->addColumn('sysinventory_department.description');
            $db->addColumn('sysinventory_department.id');
            $list = $db->select();

            if (PEAR::isError($list)){
                PHPWS_Error::log($list);
            }
            
            return $list;
        }else{
            return NULL;
        }
 
    }
    
    public function __set($name,$value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __isset($name) {
        return isset($this->$name);
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
