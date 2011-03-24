<?php

  /**
   * Internship
   *
   * Forms relationship between a student, department, and agency.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Model.php');

class Internship extends Model
{
    public $term;
    public $student_id;
    public $agency_id;
    public $faculty_supervisor_id;
    public $department_id;
    public $start_date;
    public $end_date;
    public $credits;
    public $avg_hours_week;
    public $domestic;
    public $international;
    public $paid;
    public $stipend;
    public $unpaid;
    
    /**
     * @Override Model::getDb
     */
    public function getDb(){
        return new PHPWS_DB('intern_internship');
    }

    /**
     * Create a new internship. Save to DB.
     */
    public static function addInternship()
    {
        // Create Student.
        
        $i = new Internship();
        $i->term = $_REQUEST['term'];
        $i->student_id = $_REQUEST['student_id'];
        $i->agency_id = $_REQUEST['agency_id'];
        $i->faculty_supervisor_id = $_REQUEST['faculty_supervisor_id'];
        $i->department_id = $_REQUEST['department_id'];
        $i->start_date = $_REQUEST['start_date'];
        $i->end_date = $_REQUEST['end_date'];
        $i->credits = $_REQUEST['credits'];
        $i->avg_hours_week = $_REQUEST['avg_hours_week'];
        $i->domestic = $_REQUEST['domestic'];
        $i->international = $_REQUEST['international'];
        $i->paid = $_REQUEST['paid'];
        $i->stipend = $_REQUEST['stipend'];
        $i->unpaid = $_REQUEST['unpaid'];
        $i->save();
        test($i);
    }
}

?>