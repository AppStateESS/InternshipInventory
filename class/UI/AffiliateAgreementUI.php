<?php
namespace Intern\UI;
use \Intern\AssetResolver;

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
        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'affiliateList');

        return \PHPWS_Template::process($tpl, 'intern','affiliateList.tpl');
    }

    // public static function doPager($name)
    // {
    //     \PHPWS_Core::initCoreClass('DBPager.php');
    //
    //     $pager = new \DBPager('intern_affiliation_agreement', '\Intern\AffiliationAgreement');
    //     $pager->db->addColumn("*");
    //
    //     if($name !== null) {
    //       $pager->db->addWhere("name", '%' . $name . '%', 'ILIKE');
    //     }
    //
    //     $pager->setModule('intern');
    //     $pager->setTemplate('affiliatePager.tpl');
    //     $pager->setEmptyMessage('No Affiliate Agreements Found.');
    //     $pager->addRowTags('getRowTags');
    //
    //     return $pager->get();
    // }
}
