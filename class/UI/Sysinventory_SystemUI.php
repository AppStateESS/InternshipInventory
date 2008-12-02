<?php
/**
 * Class for adding or editing a system
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_SystemUI {

    function showEditSystem($msg = NULL) {
        
        // set the default page title.  Will be reset later if we're editing a system instead of adding a new one.
        $whatWeDo = "Add System";

        javascript('/jquery/');
        javascript('/modules/sysinventory/default_build_picker/');
        javascript('/modules/sysinventory/');

        // Stuff for the template
        $tpl               = array();
        $tpl['PAGE_TITLE'] = $whatWeDo;
        $tpl['HOME_LINK']  = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        if(!is_null($msg)) $tpl['MESSAGE'] = $msg;

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
        
        // Set up the form
        $form = new PHPWS_Form('add_system');
        $form->setAction('index.php?module=sysinventory&action=edit_system');
        $form->addSubmit('submit','Save');
        $form->addHidden('newsystem','yes');
        if (isset($_REQUEST['id'])) {
            $form->addHidden('id',$_REQUEST['id']);
        }else{
            $form->addHidden('id',0);
        }

        PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Default.php');

        $all_defaults = Sysinventory_Default::getAllDefaults();
        // Get the defaults into a state where we can use them for a drop down
        $defaults_list      = array();
        $defaults_list[0]   = 'Select default build';
        foreach($all_defaults as $default){
            $defaults_list[$default['id']] = $default['name'];
        }

        $form->addSelect('build_id', $defaults_list);
        $form->setLabel('build_id', 'Default build: ');

        // Build the form elements using data from above where necessary
        $form->addSelect('department_id',$deptIdSelect);
        $form->setLabel('department_id','Department:');
        $form->addSelect('location_id',$locIdSelect);
        $form->setLabel('location_id','Location');
        $form->addText('room_number');
        $form->setLabel('room_number','Room Number:');
        $form->addText('model');
        $form->setLabel('model','Model:');
        $form->setRequired('model');
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
        $form->setReadOnly('purchase_date');
        $form->setLabel('purchase_date','Purchase Date:');
        $form->setRequired('purchase_date');
        $form->addText('vlan');
        $form->setLabel('vlan','VLAN:');
        $form->addCheck('reformat','yes');
        $form->setLabel('reformat','Reformat?');
        $form->addTextarea('notes');
        $form->setLabel('notes','Notes:');

        // Populate the form if we have a system to edit
        if(isset($_REQUEST['id'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
            // This sets the new system's id.  If this doesn't happen, the saveObject call to the db object in
            // the system class knows to do an insert instead of an update.  PHPWS magic.
            $system = new Sysinventory_System($_REQUEST['id']);

            foreach($system as $column => $value) {
                $element = $form->grab($column);
                if(is_a($element,"Form_TextField") || is_a($element,"Form_Textarea")) {
                    $form->setValue($column,$value);
                }else if(is_a($element,"Form_Checkbox") || is_a($element,"Form_Select")) {
                    $form->setMatch($column,$value);
                }
            }
        }

        $form->mergeTemplate($tpl);
        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','add_system.tpl');

        Layout::addStyle('sysinventory','flora.datepicker.css');
        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);
    }

}
?>
