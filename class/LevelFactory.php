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

    public static function getLevelObjectByLevel($level)
    {
        if(!isset($level)) {
            throw new \InvalidArgumentException('Missing student level.');
        }
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_student_level WHERE level = :level");
        $stmt->execute(array('level' => $level));
        $result = $stmt->fetchAll(\PDO::FETCH_CLASS);
        return $result;
    }

    public static function saveNewCode($code)
    {
        if(!isset($code) || is_null($code)){
            throw new \InvalidArgumentException('Missing new code.');
        }
        $db = PdoFactory::getPdoInstance();
        $values = array(
            'cod' => $code,
            'descr' => 'Unknown Level',
            'lev' => 'Unknown');

        $query = "INSERT INTO intern_student_level (code, description, level)
            VALUES (:cod, :descr, :lev)";
        $stmt = $db->prepare($query);
        $stmt->execute($values);
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
