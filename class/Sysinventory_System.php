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

    public function Sysinventory_System($sysid=0) {
        if ($sysid == 0) return;

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysid);
        $result = $db->loadObject($this);
    }

    public function save() {
        $db = new PHPWS_DB('sysinventory_system');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }

    public function delete(){
        if(!isset($this->id) || $this->id == 0) return;
        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$this->id,'=');
        $db->delete();
        if($db->affectedRows() == 1) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function getDepartment() {
        $db = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$this->department_id);
        $dept = $db->select('row');
        return $dept['description'];
    }

    public function getLocation() {
        $db = new PHPWS_DB('sysinventory_location');
        $db->addWhere('id',$this->location_id);
        $loc = $db->select('row');
        return $loc['description'];
    }
    
    /**
     * Get Document objects related to this System.
     */
    public function getDocuments()
    {
        PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Document.php');
        $db = new PHPWS_DB('sysinventory_document');
        $db->addWhere('system_id', $this->id);
        $result = $db->getObjects('Sysinventory_Document');

        if(PHPWS_Error::logIfError($result)){
            return NULL;
        }

        return $result;
    }
   
    public function get_row_tags() 
    {
        PHPWS_Core::initModClass('filecabinet', 'Cabinet.php');
        PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Document.php');

        $rowTags = array();
        $tmpTpl = array();

        // edit and delete links
        $rowTags['EDIT'] = PHPWS_Text::moduleLink('Edit','sysinventory',array('action'=>'edit_system','id'=>$this->id,'redir'=>'1'));
        $rowTags['DELETE'] = '<a href="javascript:void(0);" class="delete" id=' . $this->id . '>Delete</a>'; 
        // get department and location names 
        $rowTags['DEPARTMENT'] = $this->getDepartment();
        $rowTags['LOCATION'] = $this->getLocation();

        // Get 'Add Document' Link.
        $folder = new Sysinventory_Folder(Sysinventory_Document::getFolderId());
        $tmpTpl['ADD_DOC'] = $folder->documentUpload($this->id);
       
        // Get documents attached to this system.
        $docs = $this->getDocuments();

        if(!is_null($docs)){
            // Build the list of links
            foreach($docs as $doc){
                $tmpTpl['documents'][] = array('DOCUMENT' => $doc->getDownloadLink());
            }
        }

        $rowTags['DOC_LIST'] = PHPWS_Template::process($tmpTpl, 'sysinventory', 'document_list.tpl');

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

    public function createPDF() {
        $name = 'system' . $this->id . '.pdf';
        $filename = '/tmp/' . $name;
        $image = PHPWS_SOURCE_DIR . '/mod/sysinventory/img/surplus_form.jpg';
        require('fpdf.php');
        $pdf=new FPDF('P','mm','Letter');
        $pdf->addPage();
        $pdf->setFont('Arial','B',10);
        $pdf->image($image,0,0,216,279,'jpg');
        
        // Add the "Description" field
        $pdf->setXY(22,99);
        $pdf->cell(150,0,'Computer System:  ' . $this->model,0,0);
        
        // Add the "Department" field
        $pdf->setXY(115,225);
        $pdf->cell(80,0,$this->getDepartment(),0,0);

        // Add preparer's name
        $pdf->setXY(115,234);
        $pdf->cell(80,0,Current_User::getDisplayName(),0,0);

        // Add the date
        $pdf->setXY(125,242);
        $pdf->cell(30,0,date("n/j/Y"),0,0);

        // output to /tmp/ (or whatever directory we specified above
        $pdf->output($filename);
        
        // Output to the browser

        return $name;
    }


    /********************
     * Static Functions *
     ********************/

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

        // Update the department's last_update
        PHPWS_Core::initModClass('sysinventory','Sysinventory_Department.php');
        $dep = new Sysinventory_Department($sys->department_id);
        $dep->update();

        PHPWS_Core::reroute('index.php?module=sysinventory&action=report&redir=1');
    }

    function deleteSystem($sysId) {
        $sys = new Sysinventory_System($sysId);
        $_SESSION['filename'] = $sys->createPDF();
        $result = $sys->delete();
        if($result) return 'true';
        return 'false';
    }
}

PHPWS_Core::initModClass('filecabinet', 'Folder.php');
class Sysinventory_Folder extends Folder 
{
    /**
     * Similar to Folder::uploadLink except this one takes a system id as parameter
     * and links to sysinventory module instead of filecabinet.
     */
    public function documentUpload($sysId){
        $vars['width']   = 600;
        $vars['height']  = 600;

        $link_var['folder_id'] = $this->id;
        $link_var['action'] = 'upload_document_form';
        $link_var['sysId'] = $sysId;
        $label = dgettext('filecabinet', 'Add document');

        $link = new PHPWS_Link(null, 'sysinventory', $link_var, true);
        $link->convertAmp(false);
        $link->setSalted();
        $vars['address'] = $link->getAddress();
        $vars['title'] = & $label;

        $vars['label']   = $label;
        $vars['type']    = 'button';

        return javascript('open_window', $vars);
    }
}

?>