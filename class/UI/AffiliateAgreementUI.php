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

        $tpl['PAGER'] = AffiliateAgreementUI::doPager();
        $tpl['ADD_LINK'] = "index.php?module=intern&action=add_agreement_view";

        javascript('/jquery/');

        /* Form for adding new grad program */
        $form = new PHPWS_Form('add_prog');
        // $form->addText('name');
        // $form->setLabel('name', 'Graduate Program Title');
        // $form->addSubmit('submit','Add Graduate Program');
        // $form->setAction('index.php?module=intern&action=edit_grad');
        // $form->addHidden('add',TRUE);

        $form->mergeTemplate($tpl);
        $v = PHPWS_Template::process($tpl, 'intern', 'affiliate_list.tpl');

        return $v;
    }

    public static function doPager()
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern','AffiliationAgreement.php');

        $pager = new DBPager('intern_affiliation_agreement', 'AffiliationAgreement');
        $pager->db->addOrder('end_date asc');
        $pager->setModule('intern');
        $pager->setTemplate('affiliate_pager.tpl');
        $pager->setEmptyMessage('No Affiliate Agreements Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}
