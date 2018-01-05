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

namespace Intern\Command;

use \Intern\PdoFactory;
use \Intern\State;

class AffiliateStateRest {

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
			throw new \InvalidArgumentException('Missing Affiliation ID.');
		}

		$db = PdoFactory::getPdoInstance();

		$query = "SELECT intern_state.* FROM intern_state JOIN intern_agreement_location ON intern_state.abbr = intern_agreement_location.location
                    WHERE intern_agreement_location.agreement_id = :agreementId
                    ORDER BY intern_state.full_name ASC";

		$params= array('agreementId' => $affiliationId);

		$stmt = $db->prepare($query);
		$stmt->execute($params);

		return $stmt->fetchAll(\PDO::FETCH_CLASS, '\Intern\StateRestored');
	}


	public function post()
	{
		$stateId = $_REQUEST['state'];
		$affiliationId = $_REQUEST['affiliation_agreement_id'];


		// Sanity checking
		if (is_null($stateId) || !isset($stateId)) {
			throw new \InvalidArgumentException('Missing State Name.');
		}

		if (is_null($affiliationId) || !isset($affiliationId)) {
			throw new \InvalidArgumentException('Missing Affiliation ID.');
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
		$stateAbbr = $_REQUEST['state'];
		$affiliationId = $_REQUEST['affiliation_agreement_id'];

		// Sanity checking
		if (is_null($stateAbbr) || !isset($stateAbbr)){
			throw new \InvalidArgumentException('Missing Department Name.');
		}

		if (is_null($affiliationId) || !isset($affiliationId)){
			throw new \InvalidArgumentException('Missing Affiliation ID.');
		}

		$db = PdoFactory::getPdoInstance();

		$query = "DELETE FROM intern_agreement_location
							WHERE agreement_id = :agreementId AND location = :stateAbbr";

		$values = array('agreementId' => $affiliationId, 'stateAbbr' => $stateAbbr);

		$stmt = $db->prepare($query);
		$stmt->execute($values);
	}

}
