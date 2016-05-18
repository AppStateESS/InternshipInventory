<?php
namespace Intern\Command;

/**
 * Controller class to save changes (on create or update) to an Internship
 *
 * @author Chris Detsch
 * @package intern
 */
class TerminateAffiliate {

    public function __construct()
    {

    }

    public function execute()
    {

        PHPWS_DB::begin();

        $aaId = $_REQUEST['affiliation_agreement_id'];

        $affiliate_agreement = AffiliationAgreementFactory::getAffiliationById($aaId);

        $affiliate_agreement->setTerminated(true);

        try {
            AffiliationAgreementFactory::save($affiliate_agreement);
        } catch (Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            PHPWS_DB::rollback();
            throw $e;
        }

        PHPWS_DB::commit();

        NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Terminated internship for ' . $affiliate_agreement->getName());
        NQ::close();
        return PHPWS_Core::reroute('index.php?module=intern&action=showAffiliateEditView');
    }
}
