<?php

namespace Intern;

use \phpws2\Database;

class LevelFactory {

  public static function getLevelObjectById($code)
  {
    if(!isset($code)) {
          throw new \InvalidArgumentException('Missing student code.');
    }
    $sql = "SELECT intern_student_level.*
            FROM intern_student_level
            WHERE intern_student_level.code = {$code}";
    $row = \PHPWS_DB::getRow($sql);
    if (\PHPWS_Error::logIfError($row)) {
      throw new Exception($row);
    }

    $sLevel = new LevelDB();
    $sLevel->setCode($row['code']);
    $sLevel->setDesc($row['description']);
    $sLevel->setLevel($row['level']);

		return $sLevel;
  }

  public static function checkLevelExist($code)
  {
    var_dump('hi', $code);
    $sql = "SELECT * FROM intern_student_level WHERE code = $code";

    var_dump($sql);
    $result = \PHPWS_DB::getRow($sql);
    
    if (\PHPWS_Error::isError($result)) {
        throw new DatabaseException($result->toString());
    }

    if (sizeof($stmt) == 0) {
      return false;
		}

		return true;
  }
}
