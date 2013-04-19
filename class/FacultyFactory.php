<?php

PHPWS_Core::initModClass('intern', 'Faculty.php');

class FacultyFactory {
	
	public static function getFacultyById($id)
	{
	    $sql = "SELECT intern_faculty.* FROM intern_faculty J WHERE intern_faculty.id = {$id}";
	     
	    $row = PHPWS_DB::getRow($sql);
	    
	    $faculty = new FacultyDB();
	     
	    $faculty->setId($row['id']);
	    $faculty->setBannerId($row['banner_id']);
	    $faculty->setUsername($row['username']);
	    $faculty->setFirstName($row['first_name']);
	    $faculty->setLastName($row['last_name']);
	    $faculty->setPhone($row['phone']);
	    $faculty->setFax($row['fax']);
	    $faculty->setStreetAddress1($row['street_address1']);
	    $faculty->setStreetAddress2($row['street_address2']);
	    $faculty->setCity($row['city']);
	    $faculty->setState($row['state']);
	    $faculty->setZip($row['zip']);
	    
	    return $faculty;
	}
	
	/**
	 * Returns an array of Faculty objects for the given department.
	 * @param Department $department
	 * @return Array List of faculty for requested department.
	 */
	public static function getFacultyByDepartmentAssoc(Department $department)
	{
	    $sql = "SELECT intern_faculty.* FROM intern_faculty JOIN intern_faculty_department ON intern_faculty.banner_id = intern_faculty_department.banner_id WHERE intern_faculty_department.department_id = {$department->getId()}";
	            
	    $result = PHPWS_DB::getAll($sql);
	    
	    /*
	    $deptFaculty = array();
	    
	    // For each row in the results set, create an object
	    foreach($result as $row)
	    {
	        $faculty = new FacultyDB();
	        
	        $faculty->setId($row['id']);
	        $faculty->setBannerId($row['banner_id']);
	        $faculty->setUsername($row['username']);
	        $faculty->setFirstName($row['first_name']);
	        $faculty->setLastName($row['last_name']);
	        $faculty->setPhone($row['phone']);
	        $faculty->setFax($row['fax']);
	        $faculty->setStreetAddress1($row['street_address1']);
	        $faculty->setStreetAddress2($row['street_address2']);
	        $faculty->setCity($row['city']);
	        $faculty->setState($row['state']);
	        $faculty->setZip($row['zip']);
	        
	        $deptFaculty[] = $faculty;
	    }
	    */
	    return $result;
	}
}