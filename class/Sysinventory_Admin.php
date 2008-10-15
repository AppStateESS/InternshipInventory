<?php
/**
 * Class defines an administrator from db data
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_Admin {

    #TODO use public/private
    var $id               = NULL;
    var $username         = NULL;
    var $department_id    = NULL;
    var $description      = NULL; //for addRowTags in the pager

    function save() {
        $db = new PHPWS_DB('sysinventory_admin');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }

    function delete() {
        $db = new PHPWS_DB('sysinventory_admin');
        $db->addWhere('id',$this->id);
        $db->delete();
    }

    function get_row_tags() {
        $template = array();
        $template['DELETE'] = PHPWS_Text::moduleLink('Delete','sysinventory',array('action'=>'edit_admins','deladmin'=>TRUE,'id'=>$this->id));
        $template['DESCRIPTION'] = $this->description;
        return $template;
    }

    /********************
     * Static Functions *
     ********************/

    function generateAdminList() {

        PHPWS_Core::initCoreClass('DBPager.php');
        $pager = new DBPager('sysinventory_admin','Sysinventory_Admin');

        $pager->setModule('sysinventory');
        $pager->setTemplate('admin_pager.tpl');
        $pager->setLink('index.php?module=sysinventory');
        $pager->setEmptyMessage('No admins found.');
        $pager->addToggle('class="toggle1"');
        $pager->addToggle('class="toggle2"');

        $pager->db->addJoin('left outer','sysinventory_admin','sysinventory_department','department_id','id');
        $pager->db->addColumn('sysinventory_department.description');
        $pager->db->addColumn('sysinventory_admin.username');
        $pager->db->addColumn('sysinventory_admin.id');
        $pager->addRowTags('get_row_tags');
        return $pager->get();
    }
}
