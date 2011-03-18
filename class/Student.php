<?php

  /**
   * Student
   * 
   * Represents the student that is participating in an internship.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Model.php');
class Student 
{
    public $banner;
    public $fist_name;
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


}

?>