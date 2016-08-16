<?php
namespace Intern\UI;

class AffiliateAgreementUI implements UI
{
    public function display()
    {
        /* Check if user should have access to Affiliate Agreement page */
        if(!\Current_User::allow('intern', 'affiliation_agreement')){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to edit graduate programs.');
            return false;
        }

        if(isset($_POST['search'])){
            $name = $_POST['search'];
        } else {
            $name = null;
        }

        $tpl['PAGER'] = AffiliateAgreementUI::doPager($name);

        if(isset($_POST['search'])){
          $tpl['CLEAR'] = 'index.php?module=intern&action=showAffiliateAgreement';
        }

        javascript('/jquery/');

        /* Form for  */
        $form = new \PHPWS_Form('add_affil');

        $form->addText('search');
        $form->setLabel('search', 'Search by Name');
        $form->addCssClass('search', 'form-control');

        $form->setAction('index.php?module=intern&action=showAffiliateAgreement');

        $form->mergeTemplate($tpl);
        $v = \PHPWS_Template::process($form->getTemplate(), 'intern', 'affiliateList.tpl');

        return $v;
    }

    public static function doPager($name)
    {
        \PHPWS_Core::initCoreClass('DBPager.php');

        // TODO: This probably doesn't need a subselect pager
        $pager = new \Intern\SubselectPager('intern_affiliation_agreement', '\Intern\AffiliationAgreement');
        $pager->db->addColumn("*");

        $now = time();
        $expired = "(case when (end_date - ".$now.") > 0 then 1 else 0 end) AS expired";
        $pager->db->addColumnRaw($expired);

        $orders = array('expired desc', 'end_date asc');
        $pager->db->order = $orders;

        if($name !== null) {
          $pager->db->addWhere("name", $name);
        }

        $pager->setModule('intern');
        $pager->setTemplate('affiliatePager.tpl');
        $pager->setEmptyMessage('No Affiliate Agreements Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}
