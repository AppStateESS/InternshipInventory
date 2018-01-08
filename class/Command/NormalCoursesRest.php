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

namespace Intern\Command;

use \phpws2\Database;

class NormalCoursesRest {

	public function execute()
	{
		switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->post();
                exit;
            case 'DELETE':
                $this->delete();
                exit;
            case 'GET':
            	$data = $this->get();
				echo (json_encode($data));
				exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
	}
	public function post()
	{
		$subjectId = $_REQUEST['subjectId'];
        $cnum = $_REQUEST['cnum'];

        $subjectId = preg_replace("/^_/", '', $subjectId); // Remove leading underscore in the subject id

        if ($subjectId == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("it is missing a subject id.");
            exit;
		}
		if ($cnum == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("it is missing a course number.");
            exit;
		}

		$db = Database::newDB();
		$pdo = $db->getPDO();
		$sql = "SELECT subject_id, course_num
		          FROM intern_courses
		          WHERE subject_id=:subject_id and course_num=:cnum";
		$sth = $pdo->prepare($sql);

		$sth->execute(array('subject_id'=>$subjectId, 'cnum'=>$cnum));
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		if (sizeof($result) > 0)
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("it already exists.");
            exit;
		}

		$sql = "INSERT INTO intern_courses (id, subject_id, course_num)
				VALUES (nextval('intern_courses_seq'), :subject_id, :cnum)";

		$sth = $pdo->prepare($sql);

		$sth->execute(array('subject_id'=>$subjectId, 'cnum'=>$cnum));
	}
	public function delete()
	{
		$id = $_REQUEST['courseId'];
		$db = Database::newDB();
		$pdo = $db->getPDO();
		$sql = "DELETE FROM intern_courses
				WHERE id = :id";

		$sth = $pdo->prepare($sql);
		$sth->execute(array('id'=>$id));
	}
	public function get()
	{
		$db = Database::newDB();
		$pdo = $db->getPDO();
		$sql = "SELECT intern_courses.course_num,
					   intern_courses.subject_id,
					   intern_courses.id,
					   intern_subject.abbreviation,
					   intern_subject.description
				FROM intern_courses
				INNER JOIN intern_subject
				    ON intern_courses.subject_id = intern_subject.id
                ORDER BY intern_subject.description ASC, intern_courses.course_num ASC";


		$sth = $pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
	}
}
