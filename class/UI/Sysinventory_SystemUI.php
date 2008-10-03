<?php
/**
 * Class for adding or editing a system
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_SystemUI {

    function showAddSystem() {
        
        // see if we were passed a system to attempt to insert into the database
        if(isset($_REQUEST['newsystem'])) {
            Sysinventory_SystemUI::addSystem();
        }

        // Stuff for the template
        $tpl = array();
        $tpl['PAGE_TITLE'] = 'Add System';
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Back to Menu','sysinventory');

        // Grab data for form selects
        
        // Departments
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        $depts = Sysinventory_Department::getDepartmentsByUsername();
        if(empty($depts)) { //some priviledge checking
            $error = "You are not the administrator of any departments.";
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($error);
            return;
        }

        $deptIdSelect = array();
        foreach($depts as $dept) {
            $deptIdSelect[$dept['id']] = $dept['description'];
        }

        // Locations
        $db = new PHPWS_DB('sysinventory_location');
        $locations = $db->select();
        $locIdSelect = array();
        foreach($locations as $location) {
            $locIdSelect[$location['id']] = $location['description'];
        }
        
        // Rotations
        $rots = array('1'=>'one','2'=>'two','3'=>'three','4'=>'four');

        // Set up the form
        $form = new PHPWS_Form('add_system');
        $form->setAction('index.php?module=sysinventory&action=add_system');
        $form->addSubmit('submit','Create System');
        $form->addHidden('newsystem','yes');

        // Build the form elements using data from above where necessary
        $form->addSelect('department_id',$deptIdSelect);
        $form->setLabel('department_id','Department:');
        $form->addSelect('location_id',$locIdSelect);
        $form->setLabel('location_id','Location');
        $form->addText('room_number');
        $form->setLabel('room_number','Room Number:');
        $form->addText('model');
        $form->setLabel('model','Model:');
        $form->addText('hdd');
        $form->setLabel('hdd','Hard Disk Size:');
        $form->addText('proc');
        $form->setLabel('proc','Processor:');
        $form->addText('ram');
        $form->setLabel('ram','RAM:');
        $form->addCheck('dual_mon','yes');
        $form->setLabel('dual_mon','Dual Monitor?');
        $form->addText('mac');
        $form->setLabel('mac','MAC Address:');
        $form->addText('printer');
        $form->setLabel('printer','Printer:');
        $form->addText('staff_member');
        $form->setLabel('staff_member','Staff Member:');
        $form->addText('username');
        $form->setLabel('username','Username:');
        $form->addText('telephone');
        $form->setLabel('telephone','Telephone Number:');
        $form->addCheck('docking_stand','yes');
        $form->setLabel('docking_stand','Docking Stand?');
        $form->addCheck('deep_freeze','yes');
        $form->setLabel('deep_freeze','Deep Freeze?');
        $form->addText('purchase_date');
        $form->setLabel('purchase_date','Purchase Date:');
        $form->addSelect('rotation',$rots);
        $form->setLabel('rotation','Rotation:');
        $form->addText('vlan');
        $form->setLabel('vlan','VLAN:');
        $form->addCheck('reformat',TRUE);
        $form->setLabel('reformat','Reformat?');
        $form->addTextarea('notes');
        $form->setLabel('notes','Notes:');

        $form->mergeTemplate($tpl);
        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','add_system.tpl');

        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);
    }

    function addSystem() {
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        $sys = new Sysinventory_System;

        $sys->department_id       = $_REQUEST['department_id'];
        $sys->location_id         = $_REQUEST['location_id'];
        $sys->room_number         = $_REQUEST['room_number'];
        $sys->model               = $_REQUEST['model'];
        $sys->hdd                 = $_REQUEST['hdd'];
        $sys->proc                = $_REQUEST['proc'];
        $sys->ram                 = $_REQUEST['ram'];
        $sys->dual_mon            = $_REQUEST['dual_mon'];
        $sys->mac                 = $_REQUEST['mac'];
        $sys->printer             = $_REQUEST['printer'];
        $sys->staff_member        = $_REQUEST['staff_member'];
        $sys->username            = $_REQUEST['username'];
        $sys->telephone           = $_REQUEST['telephone'];
        $sys->docking_stand       = $_REQUEST['docking_stand'];
        $sys->deep_freeze         = $_REQUEST['deep_freeze'];
        $sys->purchase_date       = $_REQUEST['purchase_date'];
        $sys->rotation            = $_REQUEST['rotation'];
        $sys->vlan                = $_REQUEST['vlan'];
        $sys->reformat            = $_REQUEST['reformat'];
        $sys->notes               = $_REQUEST['notes'];

        $sys->save();
    }
}

?>
