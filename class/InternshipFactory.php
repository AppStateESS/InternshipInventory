<?php

namespace Intern;

use \Database;

class InternshipFactory {

    /**
     * Generates an Internship object by attempting to load the internship from the database with the given id.
     *
     * @param int $id
     * @returns Internship
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
     * Returns an array of Internship objects which are still pending approval.
     * Returns internships which are not marked as Registered in the workflow
     * and are not cancelled (i.e. state is not 'RegisteredState' nor 'CancelledState'). These are pending
     *
     * @param int $id
     * @return Array<Internship> Array of all pending Internship objects in the given term
     * @throws InvalidArgumentException
     */
    public static function getPendingInternshipsByTerm($term)
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT *
                               FROM intern_internship
                               WHERE state != 'RegisteredState'
                                    AND state != 'CancelledState'
                                    AND term = :term");
        $stmt->execute(array('term'  => $term));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Intern\InternshipRestored');

        return $stmt->fetchAll();
    }
}
