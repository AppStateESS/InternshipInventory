<?php

namespace Intern;
use \Database;

class InternshipAgencyFactory {

	public static function getHostInfoById($id)
	{
		if(is_null($id) || !isset($id)) {
            throw new \InvalidArgumentException('Internship ID is required.');
        }

        if($id <= 0) {
            throw new \InvalidArgumentException('Invalid internship ID.');
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_internshipAgency 
                               JOIN intern_agency ON intern_internshipAgency.agency_id = intern_agency.id
                               WHERE intern_internshipAgency.internship_id = :id");
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
//var_dump($id);
        return $result;
	}
}

