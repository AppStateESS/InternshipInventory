<?php

namespace Intern\UI;

class MajorUI implements UI
{
    public static function display()
    {
        /* Check if user can add/edit/hide/delete majors. */
        if(!\Current_User::allow('intern', 'edit_major') &&
           !\Current_User::allow('intern', 'delete_major')){
            \NQ::simple('intern', NotifyUI::WARNING, 'You do not have permission to edit undergraduate majors.');
            return false;
        }

        $tpl['PAGER'] = MajorUI::doPager();

        javascript('/jquery/');
        javascriptMod('intern', 'editMajor', array('EDIT_ACTION' => Major::getEditAction()));

        /* Form for adding new major */
        $form = new PHPWS_Form('add_major');
        $form->addText('name');
        $form->setLabel('name', 'Major Title');
        $form->addSubmit('submit','Add Major');
        $form->setAction('index.php?module=intern&action=edit_major');
        $form->addHidden('add',TRUE);

        $form->mergeTemplate($tpl);
        return \PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_major.tpl');
    }

    public static function doPager()
    {
        $pager = new \DBPager('intern_major', '\Intern\Major');
        $pager->db->addOrder('name asc');
        $pager->setModule('intern');
        $pager->setTemplate('major_pager.tpl');
        $pager->setEmptyMessage('No Majors Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}

?>
