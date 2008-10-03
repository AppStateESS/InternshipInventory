<?php
/**
 * Class for making the query UI
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

 class Sysinventory_QueryUI {
    var $departments = NULL;

    function display() {

        // Stuff for the template
        $tpl = array();
        $tpl['PAGE_TITLE'] = 'Search Systems';
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
        $deptIdSelect[0] = "Show All Departments";
        foreach($depts as $dept) {
            $deptIdSelect[$dept['id']] = $dept['description'];
        }

        // Locations
        $db = new PHPWS_DB('sysinventory_location');
        $locations = $db->select();
        $locIdSelect = array();
        $locIdSelect[0] = "Show All Locations";
        foreach($locations as $location) {
            $locIdSelect[$location['id']] = $location['description'];
        }
        
        // Rotations
        $rots = array('0'=>'Show All','1'=>'one','2'=>'two','3'=>'three','4'=>'four');

        // Set up the form
        $form = new PHPWS_Form('add_system');
        $form->setAction('index.php?module=sysinventory&action=report');
        $form->addSubmit('submit','Run Query');
        $form->addHidden('showreport','yes');

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
        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','build_query.tpl');

        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);

        
    }
 }
?>
