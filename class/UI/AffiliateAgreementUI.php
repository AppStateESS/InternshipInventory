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

        // TODO: Filtering by name should probably be done using DPager's built-in functionality
        if(isset($_POST['search'])){
            $name = $_POST['search'];
        } else {
            $name = null;
        }

        $tpl['PAGER'] = AffiliateAgreementUI::doPager($name);

        if(isset($_POST['search'])){
          $tpl['CLEAR'] = 'index.php?module=intern&action=showAffiliateAgreement';
        }

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

        $pager = new \DBPager('intern_affiliation_agreement', '\Intern\AffiliationAgreement');
        $pager->db->addColumn("*");

        if($name !== null) {
          $pager->db->addWhere("name", '%' . $name . '%', 'ILIKE');
        }

        $pager->setModule('intern');
        $pager->setTemplate('affiliatePager.tpl');
        $pager->setEmptyMessage('No Affiliate Agreements Found.');
        $pager->addRowTags('getRowTags');

        return $pager->get();
    }
}
