<?php

class GradProgramUI implements UI
{
    public static function display()
    {
        /* Check if user can add/edit/hide/delete grad programs. */
        if(!Current_User::allow('intern', 'edit_grad_prog') && 
           !Current_User::allow('intern', 'delete_grad_prog')){
            NQ::simple('intern', INTERN_WARNING, 'You do not have permission to edit graduate programs.');
            return false;
        }

        $tpl['PAGER'] = self::doPager();

        javascript('/jquery/');
        javascriptMod('intern', 'editMajor', array('EDIT_ACTION' => GradProgram::getEditAction()));
        
        /* Form for adding new grad program */
        $form = &new PHPWS_Form('add_prog');
        $form->addText('name');
        $form->setLabel('name', 'Graduate Program Title');
        $form->addSubmit('submit','Add Graduate Program');
        $form->setAction('index.php?module=intern&action=edit_grad');
        $form->addHidden('add',TRUE);

        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_grad.tpl');
    }

    public static function doPager() 
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern','GradProgram.php');

        $pager = new DBPager('intern_grad_prog', 'GradProgram');
        $pager->db->addOrder('name asc');
        $pager->setModule('intern');
        $pager->setTemplate('grad_pager.tpl');
        $pager->setEmptyMessage('No Graduate Programs Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}

?>