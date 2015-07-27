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

        $tpl['PAGER'] = AffiliateAgreementUI::doPager($_POST['search']);
        $tpl['ADD_LINK'] = "index.php?module=intern&action=add_agreement_view";
        if(isset($_POST['search']))
        {
          $tpl['CLEAR'] = "index.php?module=intern&action=AFFIL_AGREE_LIST";
        }

        javascript('/jquery/');

        /* Form for  */
        $form = new PHPWS_Form('add_affil');

        $form->addText('search');
        $form->setLabel('search', 'Search by Name');
        $form->addCssClass('search', 'form-control');

        $form->setAction('index.php?module=intern&action=AFFIL_AGREE_LIST');

        $form->mergeTemplate($tpl);
        $v = PHPWS_Template::process($form->getTemplate(), 'intern', 'affiliate_list.tpl');

        return $v;
    }

    public static function doPager($name)
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        PHPWS_Core::initModClass('intern','AffiliationAgreement.php');

        $pager = new SubselectPager('intern_affiliation_agreement', 'AffiliationAgreement');
        $pager->db->addColumn("*");
        $now = time();
        $expired = "(case when (end_date - ".$now.") > 0 then 1 else 0 end) AS expired";
        $pager->db->addColumnRaw($expired);
        $orders = array('expired desc', 'end_date asc');

        $pager->db->order = $orders;
        if(!empty($name))
        {
          $pager->db->addWhere("name", $name);
        }
        $pager->setModule('intern');
        $pager->setTemplate('affiliate_pager.tpl');
        $pager->setEmptyMessage('No Affiliate Agreements Found.');
        $pager->addRowTags('getRowTags');



        return $pager->get();
    }
}
