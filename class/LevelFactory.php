<?php

namespace Intern;

use \phpws2\Database;

class LevelFactory {

    public static function getLevelObjectById($code)
    {
        if(!isset($code)) {
            throw new \InvalidArgumentException('Missing student code.');
        }
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_student_level WHERE code = :code");
        $stmt->execute(array('code' => $code));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Intern\LevelDB');
        $result = $stmt->fetch();

        return $result;
    }

    public static function checkLevelExist($code)
    {
        $results = LevelFactory::getLevelObjectById($code);

        if (!$results) {
            return false;
        }
        return true;
    }
}
