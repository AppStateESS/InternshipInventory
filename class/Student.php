<?php

  /**
   * Student
   * 
   * Represents the student that is participating in an internship.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Model.php');
class Student extends Model
{
    public $banner;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $phone;
    public $email;
    public $level;
    public $grad_prog;
    public $ugrad_major;
    public $gpa;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_student');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        PHPWS_Core::initModClass('intern', 'Major.php');

        $csv = array();
        $major = $this->getUgradMajor();
        $prog  = $this->getGradProgram();
        
        $csv['Banner'] = $this->banner;
        $csv['Student First Name'] = $this->first_name;
        $csv['Student Middle Name'] = $this->middle_name;
        $csv['Student Last Name']  = $this->last_name;
        $csv['Student Phone'] = $this->phone;
        $csv['Student Email'] = $this->email;
        
        if($major != null)
            $csv = array_merge($csv, $major->getCSV());
        else
            $csv = array_merge($csv, Major::getEmptyCSV());
        if($prog != null)
            $csv = array_merge($csv, $prog->getCSV());
        else
            $csv = array_merge($csv, GradProgram::getEmptyCSV());
        
        return $csv;
    }

    /**
     * Get a Major object for the major of this student.
     */
    public function getUgradMajor()
    {
        PHPWS_Core::initModClass('intern', 'Major.php');
        if(!is_null($this->ugrad_major) && $this->ugrad_major != 0){
            return new Major($this->ugrad_major);
        }else{
            return null;
        }
    }

    /**
     * Get a GradProgram object for the graduate program of this student.
     */
    public function getGradProgram()
    {
        PHPWS_Core::initModClass('intern', 'GradProgram.php');
        if(!is_null($this->grad_prog) && $this->grad_prog != 0){
            return new GradProgram($this->grad_prog);
        }else{
            return null;
        }
    }

    /**
     * Get the concatenated first name, middle name/initial, and last name.
     */
    public function getFullName()
    {
        $name = $this->first_name;
        // Middle name is not required. If no middle name as input then
        // this will not show the extra space for padding between middle and last name.
        $name .= isset($this->middle_name) ? ' '.$this->middle_name.' ' : null;
        $name .= $this->last_name;
        return $name;
    }

    /**
     * Get a Student object by their banner id.
     */
    public static function getStudentByBanner($bannerId){
        $db = self::getDb();
        $db->addWhere('banner', $bannerId);
        $student = new Student();

        if(!$db->loadObject($student)){
            return null;
        }else {
            return $student;
        }
    }
}

?>