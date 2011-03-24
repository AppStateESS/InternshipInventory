<?php
/**
 * User interface for viewing departments.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 **/

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class DepartmentUI implements UI{

    public static function display() {
        
        // Check permissions.  Non-deities should never see this page
        // unless they're trying to be sneaky, since the link to it would
        // be hidden.
        if(!Current_User::isDeity()){
            NQ::simple('intern', INTERN_ERROR, "Uh Uh Uh! You didn't say the magic word!");
            return ;
        }

        // Set extra page tags
        $tpl['HOMELINK'] = PHPWS_Text::moduleLink('Back to Menu','intern');
        
        // Form for adding new department
        $form = &new PHPWS_Form('add_department');
        $form->addText('description');
        $form->setLabel('description','Description');
        $form->addSubmit('submit','Add Department');
        $form->setAction('index.php?module=intern&action=edit_departments');
        $form->addHidden('addDep',TRUE);

        $tpl['PAGER'] = DepartmentUI::doPager();
        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_department.tpl');
    }
    
    public static function doPager() 
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern','Department.php');

        $pager = new DBPager('intern_department','Department');
        $pager->db->addOrder('name asc');
        $pager->setModule('intern');
        $pager->setTemplate('department_pager.tpl');
        $pager->setEmptyMessage('No Departments Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}
?>
