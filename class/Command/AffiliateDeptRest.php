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
use \Intern\DepartmentFactory;

class AffiliateDeptRest {

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

        $query = "SELECT intern_department.id, intern_department.name FROM intern_agreement_department JOIN intern_department ON intern_agreement_department.department_id = intern_department.id
        WHERE agreement_id = :id and hidden = 0
        ORDER BY intern_department.name ASC";

        $params= array('id' => $affiliationId);
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, '\Intern\DepartmentDB');
    }

    public function post()
    {
        $deptId = $_REQUEST['department'];
        $affiliationId = $_REQUEST['affiliation_agreement_id'];

        // Sanity checking
        if (is_null($deptId) || !isset($deptId)) {
            throw new \InvalidArgumentException('Missing Department Id.');
        }

        if (is_null($affiliationId) || !isset($affiliationId)) {
            throw new \InvalidArgumentException('Missing Affiliation ID.');
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
        $deptId = $_REQUEST['department'];
        $affiliationId = $_REQUEST['affiliation_agreement_id'];

        // Sanity checking
        if (is_null($deptId) || !isset($deptId)) {
            throw new \InvalidArgumentException('Missing Department Name.');
        }

        if (is_null($affiliationId) || !isset($affiliationId)) {
            throw new \InvalidArgumentException('Missing Affiliation ID.');
        }

        $db = PdoFactory::getPdoInstance();

        $query = "DELETE FROM intern_agreement_department
        WHERE agreement_id = :agreementId AND department_id = :deptId";

        $values = array('agreementId' => $affiliationId, 'deptId' => $deptId);

        $stmt = $db->prepare($query);
        $stmt->execute($values);
    }

}
