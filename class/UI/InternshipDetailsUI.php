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
        $s = $i->getStudent();
        $m = $s->getUgradMajor();
        $g = $s->getGradProgram();
        $a = $i->getAgency();
        $f = $i->getFacultySupervisor();
        $d = $i->getDepartment();
        $docs = $i->getDocuments();

        $tpl['internship'] = $i->getReadableTypes();
        $tpl['student'][] = get_object_vars($s);
        $tpl['student'][0]['major'] = $m->getName();
        $tpl['student'][0]['grad_prog'] = $g->getName();
        $tpl['agency'][] = get_object_vars($a);
        $tpl['faculty'][] = get_object_vars($f);
        $tpl['department'][] = get_object_vars($d);
        if(!is_null($docs)){
            foreach($docs as $doc){
                $tpl['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink('blah'),
                                       'DELETE'   => $doc->getDeleteLink());
            }
        }

        $folder = new Intern_Folder(Intern_Document::getFolderId());
        $tpl['UPLOAD_DOC'] = $folder->documentUpload($i->id);

        return PHPWS_Template::process($tpl, 'intern', 'internship_details.tpl');
    }
}

?>