<?php

class AffiliateDeptRest {

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

		$query = "SELECT department_id FROM intern_agreement_department
							WHERE agreement_id = :aaId";

		$params= array('aaId' => $affiliationId);

		$stmt = $db->prepare($query);
		$stmt->execute($params);

		$results = $stmt->fetchAll();

		$deptNames = array();
		foreach ($results as $id) {

			$dept = DepartmentFactory::getDepartmentById($id['department_id']);

			array_push($deptNames, $dept->getName());
		}

		return $deptNames;

	}

	public function post()
	{
		$deptId = $_REQUEST['department'];
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		// Sanity checking
		if (is_null($deptId) || !isset($deptId)) {
			throw new InvalidArgumentException('Missing Department Id.');
		}

		if (is_null($affiliationId) || !isset($affiliationId)) {
			throw new InvalidArgumentException('Missing Affiliation ID.');
		}


		$db = PdoFactory::getPdoInstance();

		$query = "INSERT INTO intern_agreement_department
							(agreement_id, department_id)
							VALUES (:agreementId, :deptId)";

		$values = array('agreementId' => $affiliationId, 'deptId' => $deptId);

		$stmt = $db->prepare($query);
		$stmt->execute($values);

	}

	public function delete()
	{
		$deptName = $_REQUEST['department'];
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		// Sanity checking
		if (is_null($deptName) || !isset($deptName)) {
			throw new InvalidArgumentException('Missing Department Name.');
		}

		if (is_null($affiliationId) || !isset($affiliationId)) {
			throw new InvalidArgumentException('Missing Affiliation ID.');
		}

		$db = PdoFactory::getPdoInstance();

		$query = "SELECT id FROM intern_department
							WHERE name = :deptName";

		$params = array('deptName' => $deptName);

		$stmt = $db->prepare($query);
		$stmt->execute($params);

		$results = $stmt->fetch();

		$deptId = $results['id'];

		$query = "DELETE FROM intern_agreement_department
							WHERE agreement_id = :agreementId AND department_id = :deptId";

		$values = array('agreementId' => $affiliationId, 'deptId' => $deptId);

		$stmt = $db->prepare($query);
		$stmt->execute($values);
	}

}
