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
        javascriptMod('intern', 'affiliationAgreement');

        /* Form for adding new grad program */
        $form = new PHPWS_Form('add_prog');

        $form->addText('name');
        $form->setLabel('name', 'Affiliate Name');
        $form->addCssClass('name', 'form-control');
        //$form->addHidden('add',TRUE);

        $form->addText('begin_date');
        $form->setLabel('begin_date', 'Beginning Date');
        $form->addCssClass('begin_date', 'form-control');

        $form->addText('end_date');
        $form->setLabel('end_date', 'Ending Date');
        $form->addCssClass('end_date', 'form-control');

        $form->setAction('index.php?module=intern&action=AFFIL_AGREE_EDIT');
        $form->addSubmit('submit','Add Affiliate');

        $form->mergeTemplate($tpl);
        $v = PHPWS_Template::process($form->getTemplate(), 'intern', 'add_affiliate.tpl');

        return $v;
    }
}
