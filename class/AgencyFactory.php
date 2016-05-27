<?php

namespace Intern;
use \Database;

class AgencyFactory {

    public static function getAgencyById($id) {
        if(is_null($id) || !isset($id)) {
            throw new \InvalidArgumentException('Agency ID is required.');
        }

        if($id <= 0) {
            throw new \InvalidArgumentException('Invalid agency ID.');
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_agency WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Intern\AgencyRestored');

        return $stmt->fetch();
    }
}
