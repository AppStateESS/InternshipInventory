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
    public $grad_prog;
    public $ugrad_major;
    public $graduated;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_student');
    }

    /**
     * Get the concatenated first name, middle name/initial, and last name.
     */
    public function getFullName()
    {
        return "$this->first_name $this->middle_name $this->last_name";
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