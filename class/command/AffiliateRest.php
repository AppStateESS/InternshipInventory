<?php

class AffiliateRest {

	public function execute()
	{

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

	public function get()
	{
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		PHPWS_Core::initModClass('intern', 'AffiliationAgreement.php');
    PHPWS_Core::initModClass('intern', 'AffiliationAgreementFactory.php');

    $agreement = AffiliationAgreementFactory::getAffiliationById($affiliationId);

    return $agreement->getTerminated();
	}

	public function post()
	{
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		// Sanity checking
		if (is_null($affiliationId) || !isset($affiliationId)) {
			throw new InvalidArgumentException('Missing Affiliation ID.');
		}

		$affiliation = AffiliationAgreementFactory::getAffiliationById($affiliationId);

		if (is_null($affiliation) || !isset($affiliation)) {
			throw new Exception('Affiliation returned as null.');
		}

		if($affiliation->getTerminated() == 1)
		{
			$affiliation->setTerminated(0);
		}
		else
		{
			$affiliation->setTerminated(1);
		}

		AffiliationAgreementFactory::save($affiliation);
	}

}
