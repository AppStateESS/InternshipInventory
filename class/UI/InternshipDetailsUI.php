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

        $tpl = array();
        
        // Load internship.
        $i = new Internship($_REQUEST['id']);
        $s = $i->getStudent();
        $a = $i->getAgency();
        $f = $i->getFacultySupervisor();
        $d = $i->getDepartment();

        $tpl['student'][] = get_object_vars($s);
        $tpl['agency'][] = get_object_vars($a);
        $tpl['faculty'][] = get_object_vars($f);
        $tpl['department'][] = get_object_vars($d);

        return PHPWS_Template::process($tpl, 'intern', 'internship_details.tpl');
    }
}

?>