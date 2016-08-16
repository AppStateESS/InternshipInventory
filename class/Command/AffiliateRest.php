<?php
namespace Intern\Command;

use \Intern\AffiliationAgreementFactory;

class AffiliateRest {

    public function execute()
    {
        /* Check if user should have access to Affiliate Agreement page */
        if(!\Current_User::allow('intern', 'affiliation_agreement')){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to add Affiliation Agreements.');
            throw new \Intern\Exception\PermissionException('You do not have permission to add Affiliation Agreements.');
        }

        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            case 'POST':
                $this->post();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    // TODO: this should return the whole affiliation agreement object
    public function get()
    {
        $affiliationId = $_REQUEST['affiliation_agreement_id'];

        $agreement = AffiliationAgreementFactory::getAffiliationById($affiliationId);

        $agreement->states = array();
        $agreement->depratments = array();

        return $agreement;
    }

	public function post()
	{
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		// Sanity checking
		if (is_null($affiliationId) || !isset($affiliationId)) {
			throw new \InvalidArgumentException('Missing Affiliation ID.');
		}

		$affiliation = AffiliationAgreementFactory::getAffiliationById($affiliationId);

        // TODO: Don't just toggle this. Actually get it from the data passed in.
		if($affiliation->getTerminated() == 1) {
			$affiliation->setTerminated(0);
		}else{
			$affiliation->setTerminated(1);
		}

		AffiliationAgreementFactory::save($affiliation);
	}

}
