<?php
/**
 * Class defines an administrator from db data
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_Admin {
    var $id               = NULL;
    var $username         = NULL;
    var $department_id    = NULL;

    function generateAdminList() {
        //$pageTags = array();
        //$pageTags['USERNAME']    = "Username";
        //$pageTags['DEPARTMENT']  = "Department";

        PHPWS_Core::initCoreClass('DBPager.php');
        $pager = new DBPager('sysinventory_admin','Sysinventory_Admin');

        $pager->setModule('sysinventory');
        $pager->setTemplate('admin_pager.tpl');
        $pager->setLink('index.php?module=sysinventory');
        $pager->setEmptyMessage('No admins found.');
        //$pager->addPageTags($pageTags);
        
        return $pager->get();
    }

    function save() {
        $db = new PHPWS_DB('sysinventory_admin');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }
}
