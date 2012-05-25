<?php

  /**
   * Displays all information for a given internship identified by it's ID.
   * This is will be called with Ajax.
   */

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class InternshipDetailsUI implements UI
{

    public static function display()
    {
        if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']))
        {
            return false;
        }

        PHPWS_Core::initModClass('intern', 'Internship.php');
        PHPWS_Core::initModClass('intern', 'Intern_Document.php');
        PHPWS_Core::initModClass('intern', 'Intern_Folder.php');

        $tpl = array();
        
        // Load internship.
        $i = new Internship($_REQUEST['id']);
        $m = $i->getUgradMajor();
        $g = $i->getGradProgram();
        $a = $i->getAgency();
        $f = $i->getFacultySupervisor();
        $d = $i->getDepartment();
        $docs = $i->getDocuments();

        // Get vars from objects directly.
        $tpl['internship'] = $i->getReadableTypes();
        //$tpl['student'][] = get_object_vars($s);
        // Plug in major and/or grad program name
        if(!is_null($m))
            $tpl['student'][0]['major'] = $m->getName();
        if(!is_null($g))
            $tpl['student'][0]['grad_prog'] = $g->getName();
        $tpl['agency'][] = get_object_vars($a);
        // Plug in formatted address
        if($i->isDomestic()){
            $tpl['agency'][0]['address'] = $a->getAddress();
            $tpl['agency'][0]['supervisor_address'] = $a->getSuperAddress();
        }else{
            $tpl['agency'][0]['address'] = $a->getInternationalAddress();
            $tpl['agency'][0]['supervisor_address'] = $a->getSuperInternationalAddress();
        }
        $tpl['faculty'][] = get_object_vars($f);
        $tpl['department'][] = get_object_vars($d);
        
        // Loop document for internship.
        if(!is_null($docs)){
            foreach($docs as $doc){
                $tpl['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink(),
                                       'DELETE'   => $doc->getDeleteLink());
            }
        }

        // Show the upload document link.
        $folder = new Intern_Folder(Intern_Document::getFolderId());
        $tpl['UPLOAD_DOC'] = $folder->documentUpload($i->id);
        return PHPWS_Template::process($tpl, 'intern', 'internship_details.tpl');
    }
}

?>