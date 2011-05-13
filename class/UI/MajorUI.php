<?php

class MajorUI implements UI
{
    public static function display()
    {
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
        $form = &new PHPWS_Form('add_major');
        $form->addText('name');
        $form->setLabel('name', "Major Title");
        $form->addSubmit('submit','Add Major');
        $form->setAction('index.php?module=intern&action=edit_majors');
        $form->addHidden('add',TRUE);

        $tpl['PAGER'] = MajorUI::doPager();
        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_major.tpl');
    }

    public static function doPager() 
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern','Major.php');

        $pager = new DBPager('intern_major', 'Major');
        $pager->db->addOrder('name asc');
        $pager->setModule('intern');
        $pager->setTemplate('major_pager.tpl');
        $pager->setEmptyMessage('No Majors Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}

?>