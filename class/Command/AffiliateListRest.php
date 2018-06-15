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

use \Intern\AffiliationAgreementFactory;

class AffiliateListRest {

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
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    // TODO: this should return the table of affiliation agreement objects
    public function get()
    {
        $db = \phpws2\Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT intern_affiliation_agreement.name,
					   intern_affiliation_agreement.end_date,
                       intern_affiliation_agreement.id,
                       intern_affiliation_agreement.auto_renew
				FROM intern_affiliation_agreement
                ORDER BY intern_affiliation_agreement.end_date ASC";

		$sth = $pdo->prepare($sql);

		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
    }
}
