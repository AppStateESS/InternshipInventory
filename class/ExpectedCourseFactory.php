<?php

namespace Intern;

use \phpws2\Database;

/**
 * Factory class for the intern_courses table.
 *
 * @author Jeremy Booker
 * @package Intern
 */
class ExpectedCourseFactory {

    /**
     * Checks if a given subject and course number is in the table of expected courses.
     *
     * @return boolean True if the course appears in the list of expected courses, false otherwise.
     */
    public static function isExpectedCourse(Subject $subject, $courseNumber)
    {
        if($courseNumber === null || $courseNumber === ''){
            throw new \InvalidArgumentException('Missing course number.');
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();
        $sql = "SELECT id FROM intern_courses
                WHERE subject_id=:subject_id and course_num=:cnum";
        $sth = $pdo->prepare($sql);

        $sth->execute(array('subject_id'=>$subject->getId(), 'cnum'=>$courseNumber));
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if (sizeof($result) === 0) {
            return false;
        }

        return true;
    }
}
