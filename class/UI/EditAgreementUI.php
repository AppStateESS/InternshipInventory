<?php
namespace Intern\UI;

use \Intern\AffiliationAgreementFactory;
use \Intern\AffiliateFolder;
use \Intern\AffiliationContract;

class EditAgreementUI implements UI
{
    public function display()
    {
        /* Check if user should have access to Affiliate Agreement page */
        if(!\Current_User::allow('intern', 'affiliation_agreement')){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to add Affiliate Agreements.');
            return false;
        }

        $aaId = $_REQUEST['affiliation_agreement_id'];

        $affiliate_agreement = AffiliationAgreementFactory::getAffiliationById($aaId);

        javascript('jquery');
        javascriptMod('intern', 'affiliationAgreement', array('ID'=>$affiliate_agreement->getId()));

        /* Form for adding new grad program */
        $form = new \PHPWS_Form('edit_affil');


        $form->addText('name', $affiliate_agreement->getName());
        $form->setLabel('name', 'Affiliate Name');
        $form->addCssClass('name', 'form-control');

        $form->addText('begin_date', date('m/d/Y', $affiliate_agreement->getBeginDate()));
        $form->setLabel('begin_date', 'Beginning Date');
        $form->addCssClass('begin_date', 'form-control');

        $form->addText('end_date', date('m/d/Y',$affiliate_agreement->getEndDate()));
        $form->setLabel('end_date', 'Ending Date');
        $form->addCssClass('end_date', 'form-control');

        $form->addCheck('auto_renew', 'yes');
        $form->setLabel('auto_renew', 'Auto-Renew');

        $form->addTextArea('notes', $affiliate_agreement->getNotes());
        $form->setLabel('notes', 'Notes');
        $form->addCssClass('notes', 'form-control');

        if($affiliate_agreement->getAutoRenew())
        {
          $form->setMatch('auto_renew', 'yes');
        }

        $tpl = array();

        /*
         * If 'missing' is set then we have been redirected
         * back to the form because the user didn't type in something
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

        /*** Document List ***/
        $docs = $affiliate_agreement->getDocuments();
        if (!is_null($docs)) {
            //$docs = array_reverse($docs);
            foreach ($docs as $doc) {
                $tpl['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink('blah'), 'DELETE' => $doc->getDeleteLink());
            }
        }

        $folder = new AffiliateFolder(AffiliationContract::getFolderId());
        $tpl['UPLOAD_DOC'] = $folder->documentUpload($affiliate_agreement->getId());

        $form->setAction('index.php?module=intern&action=saveAffiliate&affiliation_agreement_id='.$aaId);

        $form->mergeTemplate($tpl);
        $v = \PHPWS_Template::process($form->getTemplate(), 'intern', 'editAffiliate.tpl');

        return $v;
    }
}
