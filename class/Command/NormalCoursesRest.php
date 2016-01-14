<?php
namespace Intern\Command;

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
			echo("Missing a subject id.");
            exit;
		}
		if ($cnum == '')
		{
			header('HTTP/1.1 500 Internal Server Error');
			echo("Missing a course number.");
            exit;
		}	
		
		$db = \Database::newDB();
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
			echo("Multiple courses with the same course number.");
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
		$db = \Database::newDB();
		$pdo = $db->getPDO();
		$sql = "DELETE FROM intern_courses
				WHERE id = :id";
	
		$sth = $pdo->prepare($sql);
		$sth->execute(array('id'=>$id));
	}
	public function get()
	{
		$db = \Database::newDB();
		$pdo = $db->getPDO();
		$sql = "SELECT intern_courses.course_num,
					   intern_courses.subject_id,
					   intern_courses.id,
					   intern_subject.abbreviation,
					   intern_subject.description
				FROM intern_courses
				INNER JOIN intern_subject
				ON 	 intern_courses.subject_id = intern_subject.id";


		$sth = $pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		
		return $result;
	}
}