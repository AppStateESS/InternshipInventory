<?php

class DepartmentUI implements UI
{
    public static function display()
    {
        /* Permission check */
        if(!Current_User::allow('intern', Department::getEditPermission())){
            NQ::simple('intern', INTERN_ERROR, "Uh Uh Uh! You didn't say the magic word!");
            return ;
        }
        javascript('/jquery/');
        javascript('/intern/editMajor', array('EDIT_ACTION' => Department::getEditAction()));

        // Set extra page tags
        $tpl['HOMELINK'] = PHPWS_Text::moduleLink('Back to Menu','intern');
        
        // Form for adding new department
        $form = &new PHPWS_Form('add_department');
        $form->addText('name');
        $form->setLabel('name','Department Name');
        $form->addSubmit('submit','Add Department');
        $form->setAction('index.php?module=intern&action='.DEPT_EDIT);
        $form->addHidden('add',TRUE);

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
