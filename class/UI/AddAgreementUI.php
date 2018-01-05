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

class AddAgreementUI implements UI
{
    public function display()
    {

        /* Check if user should have access to Affiliate Agreement page */
        if(!\Current_User::allow('intern', 'affiliation_agreement')){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to add Affiliate Agreements.');
            return false;
        }

        javascript('jquery');

        /* Form for adding new grad program */
        $form = new \PHPWS_Form('add_prog');

        $form->addText('name');
        $form->setLabel('name', 'Affiliate Name');
        $form->addCssClass('name', 'form-control');

        // Begin and end date fields handled directly in template

        $form->addCheck('auto_renew');
        $form->setLabel('auto_renew', 'Auto-Renew');

        $tpl = array();

        /*
         * If 'missing' is set then we have been redirected
         * back to the form because the user didn't type in something and
         * somehow got past the javascript.
         */
        if (isset($_REQUEST['missing'])) {
            $missing = explode(' ', $_REQUEST['missing']);


            foreach ($missing as $m) {
              $missingError = $m . '_ERROR';
              $tpl[$missingError] = 'has-error';
            }


            /* Plug old values back into form fields. */
            $form->plugIn($_GET);
        }

        $form->setAction('index.php?module=intern&action=addAffiliate');

        $form->mergeTemplate($tpl);
        $v = \PHPWS_Template::process($form->getTemplate(), 'intern', 'addAffiliate.tpl');

        return $v;
    }
}
