<?php
/**
 * Class for default system templates
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/
class Sysinventory_Default {
    public $id;
    public $name;
    public $model;
    public $hdd;
    public $proc;
    public $ram;
    public $dual_mon;

    function Sysinventory_Default($id=0) {
        $this->id = 0;
        if ($id == 0) return;
        $db = new PHPWS_DB('sysinventory_default_system');
        $db->addWhere('id',$id,'=');
        $db->loadObject($this);
    }

    function save() {
        if(!isset($this->name)) return;
        $db = new PHPWS_DB('sysinventory_default_system');
        return $db->saveObject($this);
    }

    function delete() {
        if(isset($this->id)) {
            $db = new PHPWS_DB('sysinventory_default_system');
            $db->addWhere('id',$this->id,'=');
            return $db->delete();
        }else{
            return;
        }
    }


    /*****************
    * Static Methods *
    *****************/

    function newDefault(){
        $name       = $_REQUEST['name'];
        $model      = $_REQUEST['model'];
        $hdd        = isset($_REQUEST['hdd'])       ? $_REQUEST['hdd']      : NULL;
        $proc       = isset($_REQUEST['proc'])      ? $_REQUEST['proc']     : NULL;
        $ram        = isset($_REQUEST['ram'])       ? $_REQUEST['ram']      : NULL;
        $dual_mon   = isset($_REQUEST['dual_mon'])  ? $_REQUEST['dual_mon'] : NULL;

        $def = new Sysinventory_Default;
        $def->name      = $name;
        $def->model     = $model;
        $def->hdd       = $hdd;
        $def->proc      = $proc;
        $def->ram       = $ram;
        $def->dual_mon  = $dual_mon;

        $result = $def->save();
    }

    function delDefault(){
        $id = $_REQUEST['id'];
        $def = new Sysinventory_Default($id);
        $def->delete();
    }

    function getJSON() {
       return json_encode(Sysinventory_Default::getAllDefaults());
    }

    function getAllDefaults() {
        $properties = array();
        $db = new PHPWS_DB('sysinventory_default_system');
        $ids = $db->select();
        foreach($ids as $key => $value) {
            $properties[$value['id']] = $value;
        }
        return $properties;
    }

    function doPager() {
        PHPWS_Core::initCoreClass('DBPager.php');
        $pager = new DBPager('sysinventory_default_system','Sysinventory_Default');

        $pager->setModule('sysinventory');
        $pager->setTemplate('default_pager.tpl');
        $pager->addRowTags('get_row_tags');
        $pager->setEmptyMessage('No Default Systems Found.');
        return $pager->get();
    }

    function get_row_tags() {
        $template = array();
        $template['DELETE'] = PHPWS_Text::moduleLink('Delete','sysinventory',array('action'=>'edit_default','deldefault'=>TRUE,'id'=>$this->id));
        return $template;
    }
}
?>
