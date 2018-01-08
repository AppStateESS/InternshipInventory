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

namespace Intern;

use \phpws2\Database;
use \Intern\Term;

class InternshipFactory {

    /**
     * Generates an Internship object by attempting to load the internship from the database with the given id.
     *
     * @param int $id
     * @return Internship
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws InternshipNotFoundException
     */
    public static function getInternshipById($id)
    {
        if(is_null($id) || !isset($id)){
            throw new \InvalidArgumentException('Internship ID is required.');
        }

        if($id <= 0){
            throw new \InvalidArgumentException('Internship ID must be greater than zero.');
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_internship WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Intern\InternshipRestored');

        return $stmt->fetch();
    }

    /**
     * Returns an array of Internship objects which are in the early stages of pending approval.
     * Mainly used to send reminders to internships that aren't getting approved in a timely manner.
     * As long as the Sig Auth person has approved it (i.e. it's pending Dean approval), then we don't
     * need to send a reminder. This does include internships in the 'Registration Issue' status.
     *
     * @param int $term
     * @return Array<Internship> Array of all pending Internship objects in the given term
     * @throws InvalidArgumentException
     */
    public static function getPendingInternshipsByTerm(Term $term)
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT *
                               FROM intern_internship
                               WHERE state IN ('NewState', 'SigAuthReadyState', 'RegistrationIssueState')
                                    AND term = :term");
        $stmt->execute(array('term'  => $term->getTermCode()));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Intern\InternshipRestored');

        return $stmt->fetchAll();
    }
}
