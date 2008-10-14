<?php
/**
 * Class for adding and editing departments
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Sysinventory_Department {
    
    # TODO: var is depricated, use public/private/protected (see PHP docs)

    var $id = NULL;
    var $description = NULL;
    var $last_update = NULL;

    function Sysinventory_Department($id = NULL){
        #TODO: use loadObject to initialize this object
    }

    function save()
    {
        #TODO - use saveObject here
    }
    
    function delete()
    {
        $db = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$depName);
        $result = $db->delete();

        if(!$result || PHPWS_Error::logIfError($result)){
            return FALSE;
        }

        return TRUE;
    }
    
    /********************
     * Static functions *
     ********************/

    function showDepartments($whatToDo,$department) {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_DepartmentUI.php');
        $disp = &new Sysinventory_DepartmentUI;
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
        # TODO: make this object oriented

        /*
        $dept = new Sysinventory_Department();
        $dept->description = $depName;
        $dept->last_update = mktime();
        $result = $dept->save();
        */

        //test($depName,1);
        if (!isset($depName)) return;
        $db = &new PHPWS_DB('sysinventory_department');
        $db->addValue('id','NULL');
        $db->addValue('description',$depName);
        $db->addValue('last_update',time());
        $result = $db->insert();
    }

    function delDepartment($depId) {
        $dep = new Sysinventory_Department($id);
        $result = $dep->delete();

        //TODO: show an error message here
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
