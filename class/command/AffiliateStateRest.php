<?php

class AffiliateStateRest {

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
			case 'DELETE' :
				$this->delete();
				exit;
      default:
        header('HTTP/1.1 405 Method Not Allowed');
        exit;
    }
	}

  public function get()
	{
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		if (is_null($affiliationId) || !isset($affiliationId)) {
			throw new InvalidArgumentException('Missing Affiliation ID.');
		}

		$db = PdoFactory::getPdoInstance();

		$query = "SELECT location FROM intern_agreement_location
							WHERE agreement_id = :aaId";

		$params= array('aaId' => $affiliationId);

		$stmt = $db->prepare($query);
		$stmt->execute($params);

		$results = $stmt->fetchAll();

		$stateNames = array();
    $allStates = State::$UNITED_STATES;

		foreach ($results as $id) {
      $name = $allStates[$id['location']];

			array_push($stateNames, $name);
		}

		return $stateNames;

	}


	public function post()
	{
		$stateId = $_REQUEST['state'];
		$affiliationId = $_REQUEST['affiliation_agreement_id'];


		// Sanity checking
		if (is_null($stateId) || !isset($stateId)) {
			throw new InvalidArgumentException('Missing State Name.');
		}

		if (is_null($affiliationId) || !isset($affiliationId)) {
			throw new InvalidArgumentException('Missing Affiliation ID.');
		}


		$db = PdoFactory::getPdoInstance();

		$query = "INSERT INTO intern_agreement_location
							(agreement_id, location)
							VALUES (:agreementId, :stateId)";

		$values = array('agreementId' => $affiliationId, 'stateId' => $stateId);

		$stmt = $db->prepare($query);
		$stmt->execute($values);

	}

  public function delete()
	{
		$stateName = $_REQUEST['state'];
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		// Sanity checking
		if (is_null($stateName) || !isset($stateName))
    {
			throw new InvalidArgumentException('Missing Department Name.');
		}

		if (is_null($affiliationId) || !isset($affiliationId))
    {
			throw new InvalidArgumentException('Missing Affiliation ID.');
		}

		$db = PdoFactory::getPdoInstance();

		$query = "SELECT abbr FROM intern_state
							WHERE full_name = :stateName";

		$params = array('stateName' => $stateName);

		$stmt = $db->prepare($query);
		$stmt->execute($params);

		$results = $stmt->fetch();

		$stateId = $results['abbr'];

		$query = "DELETE FROM intern_agreement_location
							WHERE agreement_id = :agreementId AND location = :stateId";

		$values = array('agreementId' => $affiliationId, 'stateId' => $stateId);

		$stmt = $db->prepare($query);
		$stmt->execute($values);
	}

}
