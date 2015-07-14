<?php

class AffiliateAgreementUI implements UI
{
    public static function display()
    {
        /* Check if user should have access to Affiliate Agreement page */
        if(!Current_User::allow('intern', 'affiliate_agreement')){
            NQ::simple('intern', INTERN_WARNING, 'You do not have permission to edit graduate programs.');
            return false;
        }

        $tpl['PAGER'] = self::doPager();

        javascript('/jquery/');
        javascriptMod('intern', 'editMajor', array('EDIT_ACTION' => GradProgram::getEditAction()));

        /* Form for adding new grad program */
        // $form = new PHPWS_Form('add_prog');
        // $form->addText('name');
        // $form->setLabel('name', 'Graduate Program Title');
        // $form->addSubmit('submit','Add Graduate Program');
        // $form->setAction('index.php?module=intern&action=edit_grad');
        // $form->addHidden('add',TRUE);

        $form->mergeTemplate($tpl);
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'edit_grad.tpl');
    }

    public static function doPager()
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern','AffiliateAgreement.php');

        $pager = new DBPager('intern_affiliate_agreement', 'AffiliateAgreement');
        $pager->db->addOrder('endDate asc');
        $pager->setModule('intern');
        $pager->setTemplate('affiliate_pager.tpl');
        $pager->setEmptyMessage('No Affiliate Agreements Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}
