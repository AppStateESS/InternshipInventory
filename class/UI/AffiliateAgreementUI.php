<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
        $tpl = array();

        $tpl['vendor_bundle'] = AssetResolver::resolveJsPath('assets.json', 'vendor');
        $tpl['entry_bundle'] = AssetResolver::resolveJsPath('assets.json', 'affiliateList');

        return \PHPWS_Template::process($tpl, 'intern','affiliateList.tpl');
    }
}

// public static function doPager($name)
// {
//     \PHPWS_Core::initCoreClass('DBPager.php');
//
//     $pager = new \DBPager('intern_affiliation_agreement', '\Intern\AffiliationAgreement');
//     $pager->db->addColumn("*");
//
//     if($name !== null) {
//         $pager->db->addWhere("name", '%' . $name . '%', 'ILIKE');
//     }
//
//     $pager->setModule('intern');
//     $pager->setTemplate('affiliatePager.tpl');
//     $pager->setEmptyMessage('No Affiliate Agreements Found.');
//     $pager->addRowTags('getRowTags');
//
//     return $pager->get();
// }
