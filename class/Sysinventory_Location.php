<?php
/**
 * Class for a location
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_Location {
    
    var $id            = NULL;
    var $description   = NULL;

    function generateLocationList() {
        PHPWS_Core::initCoreClass('DBPager.php');
        $pager = new DBPager('sysinventory_location','Sysinventory_Location');
        $pager->setModule('sysinventory');
        $pager->setTemplate('location_pager.tpl');
        $pager->setLink('index.php?module=sysinventory');
        $pager->setEmptyMessage('No Locations found.');
        $pager->db->addColumn('sysinventory_location.description');
        $pager->addRowTags('get_row_tags');
        $pager->setSearch('description');
        $pager->setOrder('description','asc');


        return $pager->get();
    }

    function get_row_tags() {
        $template = array();
        $template['DELETE'] = PHPWS_Text::moduleLink('Delete','sysinventory',array('action'=>'edit_locations','delloc'=>TRUE,'id'=>$this->id));
        return $template;
    }

    function save() {
        $db = new PHPWS_DB('sysinventory_location');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }

    function delete() {
        $db = new PHPWS_DB('sysinventory_location');
        $db->addWhere('id',$this->id);
        $db->delete();
    }
}
?>
