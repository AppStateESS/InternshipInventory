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
