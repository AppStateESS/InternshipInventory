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
        $pager->db->addColumn('sysinventory_location.id');
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
<?php

/**
 * Class defines a system
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Sysinventory_System {
    public $id;
    public $location_id;
    public $department_id;
    public $room_number;
    public $model;
    public $hdd;
    public $proc;
    public $ram;
    public $dual_mon;
    public $mac;
    public $printer;
    public $staff_member;
    public $username;
    public $telephone;
    public $docking_stand;
    public $deep_freeze;
    public $purchase_date;
    public $vlan;
    public $reformat;
    public $notes;

    function Sysinventory_System($sysid=0) {
        if ($sysid == 0) return;

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysid);
        $result = $db->loadObject($this);
    }

    function save() {
        $db = new PHPWS_DB('sysinventory_system');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }

    function deleteSystem($sysId) {
        if (!isset($sysId)) {
            return 'No System ID set.';
        }

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysId);
        $result = $db->delete();

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        if($db->affectedRows() == 1) {
            return 'true';
        }else{
            return 'Database Error';
        }
    }
    function get_row_tags() {
       $rowTags = array();

       // edit and delete links
       $rowTags['EDIT'] = PHPWS_Text::moduleLink('Edit','sysinventory',array('action'=>'edit_system','id'=>$this->id,'redir'=>'1'));
       $rowTags['DELETE'] = '<a href="javascript:void(0);" class="delete" id=' . $this->id . '>Delete</a>'; 
       // get department and location names 
       $rowTags['DEPARTMENT'] = $this->getDepartment();
       $rowTags['LOCATION'] = $this->getLocation();

       return $rowTags;
    }

    public function report_row() {
        $row['Department']       = $this->getDepartment();
        $row['Location']         = $this->getLocation();
        $row['Room_Number']      = $this->room_number;
        $row['Model']            = $this->model;
        $row['HDD']              = $this->hdd;
        $row['Processor']        = $this->proc;
        $row['RAM']              = $this->ram;
        $row['Dual_Monitor']     = $this->dual_mon;
        $row['MAC']              = $this->mac;
        $row['Printer']          = $this->printer;
        $row['Staff_Member']     = $this->printer;
        $row['Username']         = $this->username;
        $row['Telephone']        = $this->telephone;
        $row['Docking_Stand']    = $this->docking_stand;
        $row['Deep_Freeze']      = $this->deep_freeze;
        $row['Purchase_Date']    = $this->purchase_date;
        $row['VLAN']             = $this->vlan;
        $row['Reformat']         = $this->reformat;
        $row['Notes']            = $this->notes;

        return $row;
    }

    function getDepartment() {
        $db = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$this->department_id);
        $dept = $db->select('row');
        return $dept['description'];
    }

    function getLocation() {
        $db = new PHPWS_DB('sysinventory_location');
        $db->addWhere('id',$this->location_id);
        $loc = $db->select('row');
        return $loc['description'];
    }

    function addSystem($id) {
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        if(!isset($_REQUEST['dual_mon'])) $_REQUEST['dual_mon'] = 'no';
        if(!isset($_REQUEST['docking_stand'])) $_REQUEST['docking_stand'] = 'no';
        if(!isset($_REQUEST['deep_freeze'])) $_REQUEST['deep_freeze'] = 'no';
        if(!isset($_REQUEST['reformat'])) $_REQUEST['reformat'] = 'no';


        $sys = new Sysinventory_System;

        $sys->id                  = $id;
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
        $sys->vlan                = $_REQUEST['vlan'];
        $sys->reformat            = $_REQUEST['reformat'];
        $sys->notes               = $_REQUEST['notes'];

        $result = $sys->save();
        if (PEAR::isError($result)) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($result);
        }
        PHPWS_Core::reroute('index.php?module=sysinventory&action=report&redir=1');
    }

}
?>
<?php
/**
 * Class for building the report page
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_ReportUI {
    function display() {

        // Check Permissions
        if (!Current_User::allow('sysinventory','admin')) {
           $error = 'You must be an administrator of at least one department and use the query builder to create a report.';
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($error);
            return;
        }

        // Get stuff from the pager
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Report.php');
        if (isset($_REQUEST['redir'])) {
            if(!isset($_SESSION['query'])) $_SESSION['query'] = array();
            $report = Sysinventory_Report::generateReport($_SESSION['query']);
        }else{
            $report = Sysinventory_Report::generateReport($_REQUEST);
        }

        Layout::addStyle('sysinventory','style.css');
        Layout::add($report);
    }

}
?>
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
        
        // Set up the form
        $form = new PHPWS_Form('add_system');
        $form->setAction('index.php?module=sysinventory');
        $form->setMethod('get');
        $form->addSubmit('submit','Run Query');
        $form->addReset('reset','Reset');
        $form->addHidden('showreport','yes');
        $form->addHidden('action','report');

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
        $form->setReadOnly('purchase_date');
        $form->setLabel('purchase_date','Purchase Date:');
        $form->addText('vlan');
        $form->setLabel('vlan','VLAN:');
        $form->addCheck('reformat','yes');
        $form->setLabel('reformat','Reformat?');
        $form->addTextarea('notes');
        $form->setLabel('notes','Notes:');

        Sysinventory_QueryUI::populate($form);

        $form->mergeTemplate($tpl);
        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','build_query.tpl');

        javascript('/jquery/');
        Layout::addStyle('sysinventory','flora.datepicker.css');
        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);

        
    }

    function populate(&$form) {
        if(isset($_SESSION['query'])) {
            $query = $_SESSION['query'];
            foreach($query as $column => $value) {
                $element = $form->grab($column);
                if(is_a($element,"Form_TextField") || is_a($element,"Form_Textarea")) {
                    $form->setValue($column,$value);
                }else if(is_a($element,"Form_Checkbox") || is_a($element,"Form_Select")) {
                    $form->setMatch($column,$value);
                }
            }
        }
    }

    function showQueryBuilder() {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_QueryUI.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        $disp = new Sysinventory_QueryUI;
        $disp->departments = Sysinventory_Department::getDepartmentsByUsername();
        
        Layout::addStyle('sysinventory','flora.datepicker.css');
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }

 }
?>
<?php
/**
 * Class for displaying departments
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

 class Sysinventory_DepartmentUI {

    function display() {
        
        // Check permissions.  Non-deities should never see this page
        // unless they're trying to be sneaky, since the link to it would
        // be hidden.
        if(!Current_User::isDeity()){
            return "Uh Uh Uh! You didn't say the magic word!";
        }

        // Set extra page tags
        $tpl['HOMELINK'] = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        
        // Form for adding new department
        $form = &new PHPWS_Form('add_department');
        $form->addText('description');
        $form->setLabel('description','Description');
        $form->addSubmit('submit','Add Department');
        $form->setAction('index.php?module=sysinventory&action=edit_departments');
        $form->addHidden('addDep',TRUE);

        $tpl['PAGER'] = Sysinventory_DepartmentUI::doPager();
        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'sysinventory', 'edit_department.tpl');
    }

    function doPager() {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');

        $pager = &new DBPager('sysinventory_department','Sysinventory_Department');
        $pager->setModule('sysinventory');
        $pager->setTemplate('department_pager.tpl');
        $pager->addRowTags('get_row_tags');
        $pager->setEmptyMessage('No Departments Found.');
        return $pager->get();
    }
 }
?>
<?php
/**
 * Class for handling UI for Location editing and creation
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_LocationUI {
    
    function showLocations() {
        // see if we need to do anything to the db
        if(isset($_REQUEST['newloc'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Location.php');
            $loc = new Sysinventory_Location;
            $loc->description = $_REQUEST['description'];
            $loc->save();
        }else if (isset($_REQUEST['delloc'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Location.php');
            $loc = new Sysinventory_Location;
            $loc->id = $_REQUEST['id'];
            $loc->delete();
        }

        // set up some stuff for the page template
        $tpl                     = array();
        $tpl['PAGE_TITLE']       = 'Edit Locations';
        $tpl['HOME_LINK']        = PHPWS_Text::moduleLink('Back to menu','sysinventory');

        // create the list of locations and stick it in the template array
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Location.php');
        $locList = Sysinventory_Location::generateLocationList();
        $tpl['PAGER'] = $locList;

        // make the form for adding a new location
        $form = new PHPWS_Form('add_location');
        $form->addText('description');
        $form->setLabel('description','Description');
        $form->addSubmit('submit','Create Location');
        $form->setAction('index.php?module=sysinventory&action=edit_locations');
        $form->addHidden('newloc','add');

        $form->mergeTemplate($tpl);

        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','edit_location.tpl');

        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);

    }

}

?>
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
        
        // Set up the form
        $form = new PHPWS_Form('add_system');
        $form->setAction('index.php?module=sysinventory');
        $form->setMethod('get');
        $form->addSubmit('submit','Run Query');
        $form->addReset('reset','Reset');
        $form->addHidden('showreport','yes');
        $form->addHidden('action','report');

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
        $form->setReadOnly('purchase_date');
        $form->setLabel('purchase_date','Purchase Date:');
        $form->addText('vlan');
        $form->setLabel('vlan','VLAN:');
        $form->addCheck('reformat','yes');
        $form->setLabel('reformat','Reformat?');
        $form->addTextarea('notes');
        $form->setLabel('notes','Notes:');

        Sysinventory_QueryUI::populate($form);

        $form->mergeTemplate($tpl);
        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','build_query.tpl');

        javascript('/jquery/');
        Layout::addStyle('sysinventory','flora.datepicker.css');
        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);

        
    }

    function populate(&$form) {
        if(isset($_SESSION['query'])) {
            $query = $_SESSION['query'];
            foreach($query as $column => $value) {
                $element = $form->grab($column);
                if(is_a($element,"Form_TextField") || is_a($element,"Form_Textarea")) {
                    $form->setValue($column,$value);
                }else if(is_a($element,"Form_Checkbox") || is_a($element,"Form_Select")) {
                    $form->setMatch($column,$value);
                }
            }
        }
    }

    function showQueryBuilder() {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_QueryUI.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        $disp = new Sysinventory_QueryUI;
        $disp->departments = Sysinventory_Department::getDepartmentsByUsername();
        
        Layout::addStyle('sysinventory','flora.datepicker.css');
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }

 }
?>
<?php
/**
 * Class for building the report page
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_ReportUI {
    function display() {

        // Check Permissions
        if (!Current_User::allow('sysinventory','admin')) {
           $error = 'You must be an administrator of at least one department and use the query builder to create a report.';
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($error);
            return;
        }

        // Get stuff from the pager
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Report.php');
        if (isset($_REQUEST['redir'])) {
            if(!isset($_SESSION['query'])) $_SESSION['query'] = array();
            $report = Sysinventory_Report::generateReport($_SESSION['query']);
        }else{
            $report = Sysinventory_Report::generateReport($_REQUEST);
        }

        Layout::addStyle('sysinventory','style.css');
        Layout::add($report);
    }

}
?>
<?php
/**
 * Class for handling UI for Location editing and creation
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_LocationUI {
    
    function showLocations() {
        // see if we need to do anything to the db
        if(isset($_REQUEST['newloc'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Location.php');
            $loc = new Sysinventory_Location;
            $loc->description = $_REQUEST['description'];
            $loc->save();
        }else if (isset($_REQUEST['delloc'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Location.php');
            $loc = new Sysinventory_Location;
            $loc->id = $_REQUEST['id'];
            $loc->delete();
        }

        // set up some stuff for the page template
        $tpl                     = array();
        $tpl['PAGE_TITLE']       = 'Edit Locations';
        $tpl['HOME_LINK']        = PHPWS_Text::moduleLink('Back to menu','sysinventory');

        // create the list of locations and stick it in the template array
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Location.php');
        $locList = Sysinventory_Location::generateLocationList();
        $tpl['PAGER'] = $locList;

        // make the form for adding a new location
        $form = new PHPWS_Form('add_location');
        $form->addText('description');
        $form->setLabel('description','Description');
        $form->addSubmit('submit','Create Location');
        $form->setAction('index.php?module=sysinventory&action=edit_locations');
        $form->addHidden('newloc','add');

        $form->mergeTemplate($tpl);

        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','edit_location.tpl');

        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);

    }

}

?>
<?php
/**
 * Class for handling UI for Admin editing and creation
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_AdminUI {

    // Show a list of admins and a form to add a new one.
    function showAdmins() {
        // permissions...
        if(!Current_User::isDeity()) {
           PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
           $error = 'Uh Uh Uh! You didn\'t say the magic word!';
           Sysinventory_Menu::showMenu($error);
           return;
        }
        // see if we need to do anything to the db
        if(isset($_REQUEST['newadmin'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Admin.php');
            $admin = new Sysinventory_Admin;
            $admin->department_id = $_REQUEST['department_id'];
            $admin->username = $_REQUEST['username'];
            $admin->save();
        }else if (isset($_REQUEST['deladmin'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Admin.php');
            $admin = new Sysinventory_Admin;
            $admin->id = $_REQUEST['id'];
            $admin->delete();
        }

        // set up some stuff for the page template
        $tpl                     = array();
        $tpl['PAGE_TITLE']       = 'Edit Administrators';
        $tpl['HOME_LINK']        = PHPWS_Text::moduleLink('Back to menu','sysinventory');

        // create the list of admins
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Admin.php');
        $adminList = Sysinventory_Admin::generateAdminList();
        // get the list of departments
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        $depts = Sysinventory_Department::getDepartmentsByUsername();

        // build the array for the department drop-down box
        $deptIdSelect = array();
        foreach($depts as $dept) {
            $deptIdSelect[$dept['id']] = $dept['description'];
        }

        // make the form for adding a new admin
        $form = new PHPWS_Form('add_admin');
        $form->addSelect('department_id',$deptIdSelect);
        $form->setLabel('department_id','Department');
        $form->addText('username');
        $form->setLabel('username','Username');
        $form->addSubmit('submit','Create Admin');
        $form->setAction('index.php?module=sysinventory&action=edit_admins');
        $form->addHidden('newadmin','add');

        $tpl['PAGER'] = $adminList;

        $form->mergeTemplate($tpl);

        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','edit_admin.tpl');

        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);

    }
}
<?php
/**
 * display the menu page based on what the logged user can do
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

 class Sysinventory_MenuUI {
    
    var $errorMsg = NULL;

    function display() {

        //housekeeping
        if(isset($_SESSION['query'])) unset($_SESSION['query']);


        $tags = array();
        $tags['TITLE']                  = "Options";
        $tags['SEARCH_LINK']            = PHPWS_Text::secureLink('Search Systems','sysinventory', array('action' => 'build_query'));
        $tags['ADD_SYSTEM_LINK']        = PHPWS_Text::secureLink('Add a System','sysinventory', array('action' => 'edit_system'));
        $tags['EDIT_LOCATIONS_LINK']    = PHPWS_Text::secureLink('Edit Locations','sysinventory',array('action' => 'edit_locations'));
        if(!empty($this->errorMsg)) {
            $tags['ERROR_MSG'] = $this->errorMsg;
        }

        // Deity Stuff
        if(Current_User::isDeity()){
            $tags['DEITY']                     = '<h2>Deity Options</h2>';
            $tags['HR']                        = '<hr width="75%"/>';
            $tags['EDIT_DEPARTMENTS_LINK']     = PHPWS_Text::secureLink('Edit Departments','sysinventory',array('action' => 'edit_departments'));
            $tags['EDIT_ADMINS_LINK']          = PHPWS_Text::secureLink('Edit Administrators','sysinventory',array('action' => 'edit_admins'));
            $tags['GRAND_TOTAL_LABEL']         = _('Total Number of Systems in Database: ');
            $db = new PHPWS_DB('sysinventory_system');
            $gt = $db->select('count');
            $tags['GRAND_TOTAL']               = $gt;
        }

        return PHPWS_Template::process($tags,'sysinventory','menu.tpl');

    }
 }
 ?>
<?php

class Sysinventory_UI {

}

?>
<?php
/**
 * Class for adding or editing a system
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_SystemUI {

    function showEditSystem($msg = NULL) {
        
        // set the default page title.  Will be reset later if we're editing a system instead of adding a new one.
        $whatWeDo = "Add System";

        // see if we need to do anything before displaying
        if(isset($_REQUEST['newsystem'])) {
            $sysid = 0;
            if (isset($_REQUEST['id'])) {
                $sysid    = $_REQUEST['id'];
                $whatWeDo = 'Edit System';
                }
            PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
            Sysinventory_System::addSystem($sysid);
        }

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

        javascript('/jquery/');
        Layout::addStyle('sysinventory','flora.datepicker.css');
        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);
    }

}
?>
<?php
/**
 * Class for displaying departments
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

 class Sysinventory_DepartmentUI {

    function display() {
        
        // Check permissions.  Non-deities should never see this page
        // unless they're trying to be sneaky, since the link to it would
        // be hidden.
        if(!Current_User::isDeity()){
            return "Uh Uh Uh! You didn't say the magic word!";
        }

        // Set extra page tags
        $tpl['HOMELINK'] = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        
        // Form for adding new department
        $form = &new PHPWS_Form('add_department');
        $form->addText('description');
        $form->setLabel('description','Description');
        $form->addSubmit('submit','Add Department');
        $form->setAction('index.php?module=sysinventory&action=edit_departments');
        $form->addHidden('addDep',TRUE);

        $tpl['PAGER'] = Sysinventory_DepartmentUI::doPager();
        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'sysinventory', 'edit_department.tpl');
    }

    function doPager() {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');

        $pager = &new DBPager('sysinventory_department','Sysinventory_Department');
        $pager->setModule('sysinventory');
        $pager->setTemplate('department_pager.tpl');
        $pager->addRowTags('get_row_tags');
        $pager->setEmptyMessage('No Departments Found.');
        return $pager->get();
    }
 }
?>
8

dir
31
https://blackfoot.appstate.edu/asu1x/sysinventory/trunk/class/UI
https://blackfoot.appstate.edu/asu1x/sysinventory



2008-10-14T16:51:55.418600Z
29
mcarter


svn:special svn:externals svn:needs-lock











d2c5e7b5-1efd-4e3c-a33f-12aa7fafcf09

Sysinventory_UI.php
file



delete
2008-10-10T14:34:52.000000Z
022c846e245f27ab814aec9d891413ff
2008-09-22T17:36:06.699189Z
6
mcarter

Sysinventory_DepartmentUI.php
file




2008-10-10T14:34:52.000000Z
0e7e6c81c4c8906e5dfdb9b95f9506ea
2008-09-29T17:36:30.544472Z
13
mcarter

Sysinventory_MenuUI.php
file




2008-10-10T14:34:52.000000Z
eec0c3c423724d4e7ac5c0a6224e7e9e
2008-10-10T13:22:03.221074Z
25
mcarter

Sysinventory_SystemUI.php
file




2008-10-14T16:25:43.000000Z
949c1a0e39417651063e96744e707526
2008-10-14T16:51:55.418600Z
29
mcarter

Sysinventory_QueryUI.php
file




2008-10-14T16:47:05.000000Z
edb113791bf43a929258158f79e602e2
2008-10-14T16:51:55.418600Z
29
mcarter

Sysinventory_AdminUI.php
file




2008-10-10T14:34:52.000000Z
0cac50824bd7457924fce091bd9229a2
2008-10-01T17:57:57.300995Z
20
mcarter

Sysinventory_LocationUI.php
file




2008-10-10T14:34:52.000000Z
616fba62a732e10ac8fa7437eb91603e
2008-10-01T19:25:17.466882Z
21
mcarter

Sysinventory_ReportUI.php
file




2008-10-10T14:34:52.000000Z
d1fa94a3a987d13084642f362cac7457
2008-10-10T13:22:03.221074Z
25
mcarter

8
K 25
svn:wc:ra_dav:version-url
V 46
/asu1x/sysinventory/!svn/ver/29/trunk/class/UI
END
Sysinventory_UI.php
K 25
svn:wc:ra_dav:version-url
V 65
/asu1x/sysinventory/!svn/ver/6/trunk/class/UI/Sysinventory_UI.php
END
Sysinventory_DepartmentUI.php
K 25
svn:wc:ra_dav:version-url
V 76
/asu1x/sysinventory/!svn/ver/13/trunk/class/UI/Sysinventory_DepartmentUI.php
END
Sysinventory_MenuUI.php
K 25
svn:wc:ra_dav:version-url
V 70
/asu1x/sysinventory/!svn/ver/25/trunk/class/UI/Sysinventory_MenuUI.php
END
Sysinventory_SystemUI.php
K 25
svn:wc:ra_dav:version-url
V 72
/asu1x/sysinventory/!svn/ver/29/trunk/class/UI/Sysinventory_SystemUI.php
END
Sysinventory_QueryUI.php
K 25
svn:wc:ra_dav:version-url
V 71
/asu1x/sysinventory/!svn/ver/29/trunk/class/UI/Sysinventory_QueryUI.php
END
Sysinventory_AdminUI.php
K 25
svn:wc:ra_dav:version-url
V 71
/asu1x/sysinventory/!svn/ver/20/trunk/class/UI/Sysinventory_AdminUI.php
END
Sysinventory_LocationUI.php
K 25
svn:wc:ra_dav:version-url
V 74
/asu1x/sysinventory/!svn/ver/21/trunk/class/UI/Sysinventory_LocationUI.php
END
Sysinventory_ReportUI.php
K 25
svn:wc:ra_dav:version-url
V 72
/asu1x/sysinventory/!svn/ver/25/trunk/class/UI/Sysinventory_ReportUI.php
END
<?php
/**
 * Class for adding or editing a system
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_SystemUI {

    function showEditSystem($msg = NULL) {
        
        // set the default page title.  Will be reset later if we're editing a system instead of adding a new one.
        $whatWeDo = "Add System";

        // see if we need to do anything before displaying
        if(isset($_REQUEST['newsystem'])) {
            $sysid = 0;
            if (isset($_REQUEST['id'])) {
                $sysid    = $_REQUEST['id'];
                $whatWeDo = 'Edit System';
                }
            PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
            Sysinventory_System::addSystem($sysid);
        }

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

        javascript('/jquery/');
        Layout::addStyle('sysinventory','flora.datepicker.css');
        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);
    }

}
?>
<?php
/**
 * display the menu page based on what the logged user can do
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

 class Sysinventory_MenuUI {
    
    var $errorMsg = NULL;

    function display() {

        //housekeeping
        if(isset($_SESSION['query'])) unset($_SESSION['query']);


        $tags = array();
        $tags['TITLE']                  = "Options";
        $tags['SEARCH_LINK']            = PHPWS_Text::secureLink('Search Systems','sysinventory', array('action' => 'build_query'));
        $tags['ADD_SYSTEM_LINK']        = PHPWS_Text::secureLink('Add a System','sysinventory', array('action' => 'edit_system'));
        $tags['EDIT_LOCATIONS_LINK']    = PHPWS_Text::secureLink('Edit Locations','sysinventory',array('action' => 'edit_locations'));
        if(!empty($this->errorMsg)) {
            $tags['ERROR_MSG'] = $this->errorMsg;
        }

        // Deity Stuff
        if(Current_User::isDeity()){
            $tags['DEITY']                     = '<h2>Deity Options</h2>';
            $tags['HR']                        = '<hr width="75%"/>';
            $tags['EDIT_DEPARTMENTS_LINK']     = PHPWS_Text::secureLink('Edit Departments','sysinventory',array('action' => 'edit_departments'));
            $tags['EDIT_ADMINS_LINK']          = PHPWS_Text::secureLink('Edit Administrators','sysinventory',array('action' => 'edit_admins'));
            $tags['GRAND_TOTAL_LABEL']         = _('Total Number of Systems in Database: ');
            $db = new PHPWS_DB('sysinventory_system');
            $gt = $db->select('count');
            $tags['GRAND_TOTAL']               = $gt;
        }

        return PHPWS_Template::process($tags,'sysinventory','menu.tpl');

    }
 }
 ?>
<?php
/**
 * Class for handling UI for Admin editing and creation
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_AdminUI {

    // Show a list of admins and a form to add a new one.
    function showAdmins() {
        // permissions...
        if(!Current_User::isDeity()) {
           PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
           $error = 'Uh Uh Uh! You didn\'t say the magic word!';
           Sysinventory_Menu::showMenu($error);
           return;
        }
        // see if we need to do anything to the db
        if(isset($_REQUEST['newadmin'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Admin.php');
            $admin = new Sysinventory_Admin;
            $admin->department_id = $_REQUEST['department_id'];
            $admin->username = $_REQUEST['username'];
            $admin->save();
        }else if (isset($_REQUEST['deladmin'])) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Admin.php');
            $admin = new Sysinventory_Admin;
            $admin->id = $_REQUEST['id'];
            $admin->delete();
        }

        // set up some stuff for the page template
        $tpl                     = array();
        $tpl['PAGE_TITLE']       = 'Edit Administrators';
        $tpl['HOME_LINK']        = PHPWS_Text::moduleLink('Back to menu','sysinventory');

        // create the list of admins
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Admin.php');
        $adminList = Sysinventory_Admin::generateAdminList();
        // get the list of departments
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        $depts = Sysinventory_Department::getDepartmentsByUsername();

        // build the array for the department drop-down box
        $deptIdSelect = array();
        foreach($depts as $dept) {
            $deptIdSelect[$dept['id']] = $dept['description'];
        }

        // make the form for adding a new admin
        $form = new PHPWS_Form('add_admin');
        $form->addSelect('department_id',$deptIdSelect);
        $form->setLabel('department_id','Department');
        $form->addText('username');
        $form->setLabel('username','Username');
        $form->addSubmit('submit','Create Admin');
        $form->setAction('index.php?module=sysinventory&action=edit_admins');
        $form->addHidden('newadmin','add');

        $tpl['PAGER'] = $adminList;

        $form->mergeTemplate($tpl);

        $template = PHPWS_Template::process($form->getTemplate(),'sysinventory','edit_admin.tpl');

        Layout::addStyle('sysinventory','style.css');
        Layout::add($template);

    }
}
<?php
/**
 * handle the menu options based on who is logged in
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Sysinventory_Menu {

    function showMenu($errorMsg) {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_MenuUI.php');
        $disp = &new Sysinventory_MenuUI;
        if(!empty($errorMsg)) {
            $disp->errorMsg = $errorMsg;
        }
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }
}
?>
<?php
/**
 * Class defines an administrator from db data
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_Admin {
    var $id               = NULL;
    var $username         = NULL;
    var $department_id    = NULL;
    var $description      = NULL; //for addRowTags in the pager

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
        $pager->addToggle('class="toggle1"');
        $pager->addToggle('class="toggle2"');
        //$pager->addPageTags($pageTags);

        $pager->db->addJoin('left outer','sysinventory_admin','sysinventory_department','department_id','id');
        $pager->db->addColumn('sysinventory_department.description');
        $pager->db->addColumn('sysinventory_admin.username');
        $pager->db->addColumn('sysinventory_admin.id');
        $pager->addRowTags('get_row_tags');
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
}
<?php

/**
 * Class for reporting stuff back from the db
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

 class Sysinventory_Report {
    var $location = NULL;
    var $department = NULL;
    var $office = NULL;
    var $employee = NULL;
    
    function generateReport($data) {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        
        // Stuff for the template
        $tpl = array();
        $tpl['PAGE_TITLE'] = 'System Report';
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        $tpl['QUERY_LINK'] = PHPWS_Text::moduleLink('New Query','sysinventory',array('action'=>'build_query'));
        $tpl['ADD_SYSTEM_LINK'] = PHPWS_Text::moduleLink('Add New System','sysinventory',array('action'=>'edit_system'));

        // set up the pager
        $pager = &new DBPager('sysinventory_system','Sysinventory_System');
        $pager->setModule('sysinventory');
        $pager->setTemplate('sysinventory_list_results.tpl');
        $pager->setReportRow('report_row');
        $pager->allowPartialReport(false);
        
        // Make an array of possible request variables
        $fields = array('model',
                        'hdd',
                        'proc',
                        'ram',
                        'dual_mon',
                        'mac',
                        'printer',
                        'staff_member',
                        'username',
                        'telephone',
                        'room_number',
                        'docking_stand',
                        'deep_freeze',
                        'purchase_date',
                        'vlan',
                        'reformat',
                        'notes');

        // Set up the array for the session...
        $query = array();

        // These fields must match exactly
        $intfields = array('department_id','location_id');
        foreach ($intfields as $intfield) {
            if(isset($data[$intfield]) && $data[$intfield] != 0){
                $pager->addWhere($intfield,$data[$intfield],'=');
                $query[$intfield] = $data[$intfield];
            }
        }
        
        // Need to make "department_id" match only departmens one is an admin of if they're not a deity...
        if(isset($data['department_id']) && $data['department_id'] == 0 && !Current_User::isDeity()) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
            $deps = Sysinventory_Department::getDepartmentsByUsername();
            foreach($deps as $dept) {
                $pager->addWhere('department_id',$dept['id'],'=');
            }
        }
        // determine what other stuff we got from the request and add restrictions for it
        foreach ($fields as $field) {
            if(isset($data[$field])){
                $pager->addWhere($field,"%$data[$field]%",'LIKE');
                $query[$field] = $data[$field];
            }
        }
        
        // now session that request
        $_SESSION['query'] = $query;

        javascript('/jquery/');
        
        $pager->addRowTags('get_row_tags'); 
        $pager->addPageTags($tpl);

        return $pager->get();
    }
 }

?>
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
<?php

/**
 * Class for reporting stuff back from the db
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

 class Sysinventory_Report {
    var $location = NULL;
    var $department = NULL;
    var $office = NULL;
    var $employee = NULL;
    
    function generateReport($data) {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        
        // Stuff for the template
        $tpl = array();
        $tpl['PAGE_TITLE'] = 'System Report';
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        $tpl['QUERY_LINK'] = PHPWS_Text::moduleLink('New Query','sysinventory',array('action'=>'build_query'));
        $tpl['ADD_SYSTEM_LINK'] = PHPWS_Text::moduleLink('Add New System','sysinventory',array('action'=>'edit_system'));

        // set up the pager
        $pager = &new DBPager('sysinventory_system','Sysinventory_System');
        $pager->setModule('sysinventory');
        $pager->setTemplate('sysinventory_list_results.tpl');
        $pager->setReportRow('report_row');
        $pager->allowPartialReport(false);
        
        // Make an array of possible request variables
        $fields = array('model',
                        'hdd',
                        'proc',
                        'ram',
                        'dual_mon',
                        'mac',
                        'printer',
                        'staff_member',
                        'username',
                        'telephone',
                        'room_number',
                        'docking_stand',
                        'deep_freeze',
                        'purchase_date',
                        'vlan',
                        'reformat',
                        'notes');

        // Set up the array for the session...
        $query = array();

        // These fields must match exactly
        $intfields = array('department_id','location_id');
        foreach ($intfields as $intfield) {
            if(isset($data[$intfield]) && $data[$intfield] != 0){
                $pager->addWhere($intfield,$data[$intfield],'=');
                $query[$intfield] = $data[$intfield];
            }
        }
        
        // Need to make "department_id" match only departmens one is an admin of if they're not a deity...
        if(isset($data['department_id']) && $data['department_id'] == 0 && !Current_User::isDeity()) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
            $deps = Sysinventory_Department::getDepartmentsByUsername();
            foreach($deps as $dept) {
                $pager->addWhere('department_id',$dept['id'],'=');
            }
        }
        // determine what other stuff we got from the request and add restrictions for it
        foreach ($fields as $field) {
            if(isset($data[$field])){
                $pager->addWhere($field,"%$data[$field]%",'LIKE');
                $query[$field] = $data[$field];
            }
        }
        
        // now session that request
        $_SESSION['query'] = $query;

        javascript('/jquery/');
        
        $pager->addRowTags('get_row_tags'); 
        $pager->addPageTags($tpl);

        return $pager->get();
    }
 }

?>
<?php
/**
 * handle the menu options based on who is logged in
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Sysinventory_Menu {

    function showMenu($errorMsg) {
        PHPWS_Core::initModClass('sysinventory','UI/Sysinventory_MenuUI.php');
        $disp = &new Sysinventory_MenuUI;
        if(!empty($errorMsg)) {
            $disp->errorMsg = $errorMsg;
        }
        Layout::addStyle('sysinventory','style.css');
        Layout::add($disp->display());
    }
}
?>
<?php
/**
 * Class defines an administrator from db data
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
**/

class Sysinventory_Admin {
    var $id               = NULL;
    var $username         = NULL;
    var $department_id    = NULL;
    var $description      = NULL; //for addRowTags in the pager

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
        $pager->addToggle('class="toggle1"');
        $pager->addToggle('class="toggle2"');
        //$pager->addPageTags($pageTags);

        $pager->db->addJoin('left outer','sysinventory_admin','sysinventory_department','department_id','id');
        $pager->db->addColumn('sysinventory_department.description');
        $pager->db->addColumn('sysinventory_admin.username');
        $pager->db->addColumn('sysinventory_admin.id');
        $pager->addRowTags('get_row_tags');
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
}
<?php
class Sysinventory_System {
    var $id;
    var $location_id;
    var $department_id;
    var $room_number;
    var $model;
    var $hdd;
    var $proc;
    var $ram;
    var $dual_mon;
    var $mac;
    var $printer;
    var $staff_member;
    var $username;
    var $telephone;
    var $docking_stand;
    var $deep_freeze;
    var $purchase_date;
    var $vlan;
    var $reformat;
    var $notes;

    function Sysinventory_System($sysid=0) {
        if ($sysid == 0) return;

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysid);
        $result = $db->loadObject($this);
    }

    function save() {
        $db = new PHPWS_DB('sysinventory_system');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }

    function deleteSystem($sysId) {
        if (!isset($sysId)) {
            return 'No System ID set.';
        }

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysId);
        $result = $db->delete();

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        if($db->affectedRows() == 1) {
            return 'true';
        }else{
            return 'Database Error';
        }
    }
    function get_row_tags() {
       $rowTags = array();

       // edit and delete links
       $rowTags['EDIT'] = PHPWS_Text::moduleLink('Edit','sysinventory',array('action'=>'edit_system','id'=>$this->id,'redir'=>'1'));
       $rowTags['DELETE'] = '<a href="javascript:void(0);" class="delete" id=' . $this->id . '>Delete</a>'; 
       // get department and location names 
       $rowTags['DEPARTMENT'] = $this->getDepartment();
       $rowTags['LOCATION'] = $this->getLocation();

       return $rowTags;
    }

    public function report_row() {
        $row['Department']       = $this->getDepartment();
        $row['Location']         = $this->getLocation();
        $row['Room_Number']      = $this->room_number;
        $row['Model']            = $this->model;
        $row['HDD']              = $this->hdd;
        $row['Processor']        = $this->proc;
        $row['RAM']              = $this->ram;
        $row['Dual_Monitor']     = $this->dual_mon;
        $row['MAC']              = $this->mac;
        $row['Printer']          = $this->printer;
        $row['Staff_Member']     = $this->printer;
        $row['Username']         = $this->username;
        $row['Telephone']        = $this->telephone;
        $row['Docking_Stand']    = $this->docking_stand;
        $row['Deep_Freeze']      = $this->deep_freeze;
        $row['Purchase_Date']    = $this->purchase_date;
        $row['VLAN']             = $this->vlan;
        $row['Reformat']         = $this->reformat;
        $row['Notes']            = $this->notes;

        return $row;
    }

    function getDepartment() {
        $db = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$this->department_id);
        $dept = $db->select('row');
        return $dept['description'];
    }

    function getLocation() {
        $db = new PHPWS_DB('sysinventory_location');
        $db->addWhere('id',$this->location_id);
        $loc = $db->select('row');
        return $loc['description'];
    }

    function addSystem($id) {
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        if(!isset($_REQUEST['dual_mon'])) $_REQUEST['dual_mon'] = 'no';
        if(!isset($_REQUEST['docking_stand'])) $_REQUEST['docking_stand'] = 'no';
        if(!isset($_REQUEST['deep_freeze'])) $_REQUEST['deep_freeze'] = 'no';
        if(!isset($_REQUEST['reformat'])) $_REQUEST['reformat'] = 'no';


        $sys = new Sysinventory_System;

        $sys->id                  = $id;
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
        $sys->vlan                = $_REQUEST['vlan'];
        $sys->reformat            = $_REQUEST['reformat'];
        $sys->notes               = $_REQUEST['notes'];

        $result = $sys->save();
        if (PEAR::isError($result)) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($result);
        }
        PHPWS_Core::reroute('index.php?module=sysinventory&action=report&redir=1');
    }

}
?>
<?php
class Sysinventory_Printer {

}
?>
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
        $pager->db->addColumn('sysinventory_location.id');
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
8

dir
31
https://blackfoot.appstate.edu/asu1x/sysinventory/trunk/class
https://blackfoot.appstate.edu/asu1x/sysinventory



2008-10-14T17:40:03.820221Z
31
jbooker


svn:special svn:externals svn:needs-lock











d2c5e7b5-1efd-4e3c-a33f-12aa7fafcf09

Sysinventory_Department.php
file




2008-10-14T17:41:06.000000Z
58b937ac7e50157e95eaffc4c25d4504
2008-10-14T17:40:03.820221Z
31
jbooker

Sysinventory_Printer.php
file



delete
2008-10-10T14:34:52.000000Z
e4a6a0318aed17e364f8b40803d63cb4
2008-09-22T17:36:06.699189Z
6
mcarter

Sysinventory_Menu.php
file




2008-10-10T14:34:52.000000Z
cd273d1e1a0455a1798e0128b7ff184d
2008-09-30T18:22:24.172492Z
15
mcarter

Sysinventory_System.php
file




2008-10-14T16:06:46.000000Z
50f0418c5710392c04719c1d8256a587
2008-10-14T16:51:55.418600Z
29
mcarter

Sysinventory_Admin.php
file




2008-10-10T14:34:52.000000Z
c232498b0d0e96d563574f5158492120
2008-10-01T17:57:57.300995Z
20
mcarter

Sysinventory_Location.php
file




2008-10-10T14:34:52.000000Z
7b57e4e5e69c2117008f5e7f34bafab8
2008-10-02T00:31:20.599045Z
22
mcarter

UI
dir

Sysinventory_Report.php
file




2008-10-10T14:34:52.000000Z
28f6d71d58dee169916253e200fa0658
2008-10-10T13:22:03.221074Z
25
mcarter

8
K 25
svn:wc:ra_dav:version-url
V 43
/asu1x/sysinventory/!svn/ver/31/trunk/class
END
Sysinventory_Department.php
K 25
svn:wc:ra_dav:version-url
V 71
/asu1x/sysinventory/!svn/ver/31/trunk/class/Sysinventory_Department.php
END
Sysinventory_Printer.php
K 25
svn:wc:ra_dav:version-url
V 67
/asu1x/sysinventory/!svn/ver/6/trunk/class/Sysinventory_Printer.php
END
Sysinventory_Menu.php
K 25
svn:wc:ra_dav:version-url
V 65
/asu1x/sysinventory/!svn/ver/15/trunk/class/Sysinventory_Menu.php
END
Sysinventory_System.php
K 25
svn:wc:ra_dav:version-url
V 67
/asu1x/sysinventory/!svn/ver/29/trunk/class/Sysinventory_System.php
END
Sysinventory_Admin.php
K 25
svn:wc:ra_dav:version-url
V 66
/asu1x/sysinventory/!svn/ver/20/trunk/class/Sysinventory_Admin.php
END
Sysinventory_Location.php
K 25
svn:wc:ra_dav:version-url
V 69
/asu1x/sysinventory/!svn/ver/22/trunk/class/Sysinventory_Location.php
END
Sysinventory_Report.php
K 25
svn:wc:ra_dav:version-url
V 67
/asu1x/sysinventory/!svn/ver/25/trunk/class/Sysinventory_Report.php
END
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
        return $dep->save();
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
