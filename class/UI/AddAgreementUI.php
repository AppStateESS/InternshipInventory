<?php

class AddAgreementUI implements UI
{
    public static function display()
    {

        /* Check if user should have access to Affiliate Agreement page */
        if(!Current_User::allow('intern', 'affiliate_agreement')){
            NQ::simple('intern', INTERN_WARNING, 'You do not have permission to add Affiliate Agreements.');
            return false;
        }

        javascript('/jquery/');

        /* Form for adding new grad program */
        $form = new PHPWS_Form('add_prog');

        $form->addText('name');
        $form->setLabel('name', 'Affiliate Name');
        $form->addCssClass('name', 'form-control');

        $form->addText('begin_date');
        $form->setLabel('begin_date', 'Beginning Date');
        $form->addCssClass('begin_date', 'form-control');

        $form->addText('end_date');
        $form->setLabel('end_date', 'Ending Date');
        $form->addCssClass('end_date', 'form-control');

        $form->addCheck('auto_renew');
        $form->setLabel('auto_renew', 'Auto-Renew');
        $form->addCssClass('auto_renew', 'form-control');

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

        $tpl['BACK'] = "index.php?module=intern&action=AFFIL_AGREE_LIST";


        $form->setAction('index.php?module=intern&action=AFFIL_AGREE_ADD');

        $form->mergeTemplate($tpl);
        $v = PHPWS_Template::process($form->getTemplate(), 'intern', 'add_affiliate.tpl');

        return $v;
    }
}
