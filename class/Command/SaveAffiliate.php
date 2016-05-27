<?php

namespace Intern\Command;

use Intern\AffiliationAgreement;
use Intern\AffiliationAgreementFactory;

/**
* Controller class to save changes (on create or update) to an Internship
*
* @author Chris Detsch
* @package intern
*/
class SaveAffiliate {

    public function __construct()
    {

    }

    public function execute()
    {
        /**************
        * Sanity Checks
        */

        // Required fields check
        $missing = self::checkRequest();
        if (!is_null($missing) && !empty($missing)) {
            // checkRequest returned some missing fields.
            $url = 'index.php?module=intern&action=addAgreementView';
            $url .= '&missing=' . implode('+', $missing);
            // Restore the values in the fields the user already entered
            foreach ($_POST as $key => $val) {
                $url .= "&$key=$val";
            }

            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'Please fill in the highlighted fields.');
            \NQ::close();

            return \PHPWS_Core::reroute($url);
        }

        // Course start date must be before end date
        if(!empty($_REQUEST['begin_date']) && !empty($_REQUEST['end_date'])){
            $start = strtotime($_REQUEST['begin_date']);
            $end   = strtotime($_REQUEST['end_date']);

            if ($start > $end) {
                $url = 'index.php?module=intern&action=addAgreementView&missing=begin_date+end_date';
                // Restore the values in the fields the user already entered
                unset($_POST['begin_date']);
                unset($_POST['end_date']);
                foreach ($_POST as $key => $val) {
                    $url .= "&$key=$val";
                }
                \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'The affiliation agreement begin date must be before the end date.');
                \NQ::close();
                return \PHPWS_Core::reroute($url);
            }
        }

        \PHPWS_DB::begin();

        //Create/Save Affiliation

        if (!isset($_REQUEST['affiliation_agreement_id']) || is_null($_REQUEST['affiliation_agreement_id'])){
            $affiliate_agreement = new AffiliationAgreement();

            $affiliate_agreement->setName($_REQUEST['name']);
            $affiliate_agreement->setBeginDate(strtotime($_REQUEST['begin_date']));
            $affiliate_agreement->setEndDate(strtotime($_REQUEST['end_date']));
        } else {
            $id = $_REQUEST['affiliation_agreement_id'];
            $affiliate_agreement = AffiliationAgreementFactory::getAffiliationById($id);

            $affiliate_agreement->setName($_POST['name']);
            $affiliate_agreement->setBeginDate(strtotime($_POST['begin_date']));
            $affiliate_agreement->setEndDate(strtotime($_POST['end_date']));
            $affiliate_agreement->setNotes($_POST['notes']);
        }

        $auto_renew = isset($_POST['auto_renew']) ? true : false;

        $affiliate_agreement->setAutoRenew($auto_renew);

        try {
            AffiliationAgreementFactory::save($affiliate_agreement);
        } catch (\Exception $e) {
            // Rollback and re-throw the exception so that admins gets an email
            \PHPWS_DB::rollback();
            throw $e;
        }


        \PHPWS_DB::commit();

        // Show message if user edited internship
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, 'Saved internship for ' . $affiliate_agreement->getName());
        \NQ::close();
        return \PHPWS_Core::reroute('index.php?module=intern&action=showAffiliateAgreement');
    }

    /**
    * Check that required fields are in the REQUEST.
    */
    private static function checkRequest()
    {
        $vals = null;

        if(empty($_REQUEST['name']))
        {
            $vals[] = 'name';
        }

        if(empty($_REQUEST['begin_date']))
        {
            $vals[] = 'begin_date';
        }

        if(empty($_REQUEST['end_date']))
        {
            $vals[] = 'end_date';
        }

        return $vals;
    }
}
