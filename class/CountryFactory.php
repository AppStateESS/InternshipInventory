<?php

namespace Intern;

use \phpws2\Database;

class CountryFactory {


    public static function getCountries()
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_country");
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

        $countries = array();
        foreach($rows as $row) {
            $countries[$row['id']] = $row['name'];
        }

        return $countries;
    }
}
