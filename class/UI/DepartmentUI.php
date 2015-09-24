<?php

namespace Intern\UI;
use Intern\Department;

class DepartmentUI implements UI
{
    public function display()
    {
        /* Permission check */
        if(!\Current_User::allow('intern', Department::getEditPermission())){
            \NQ::simple('intern', NotifyUI::ERROR, "You do not have permission to edit departments.");
            return ;
        }
        javascript('/jquery/');
        javascriptMod('intern', 'editMajor', array('EDIT_ACTION' => Department::getEditAction()));

        // Form for adding new department
        $form = new \PHPWS_Form('add_department');
        $form->addText('name');
        $form->setLabel('name','Department Name');
        $form->addSubmit('submit','Add Department');
        $form->setAction('index.php?module=intern&action=edit_dept');
        $form->addHidden('add',TRUE);

        $tpl['PAGER'] = DepartmentUI::doPager();
        $form->mergeTemplate($tpl);
        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_department.tpl');

    }

    public static function doPager()
    {
        $pager = new \DBPager('intern_department','Intern\Department');
        $pager->db->addOrder('name asc');
        $pager->setModule('intern');
        $pager->setTemplate('department_pager.tpl');
        $pager->setEmptyMessage('No Departments Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}

?>
