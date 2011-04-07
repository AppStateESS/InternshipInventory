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
     * Get the Student object associated with this Internship.
     */
    public function getStudent()
    {
        PHPWS_Core::initModClass('intern', 'Student.php');
        return new Student($this->student_id);
    }

    /**
     * Get the Agency object associated with this internship.
     */
    public function getAgency()
    {
        PHPWS_Core::initModClass('intern', 'Agency.php');
        return new Agency($this->agency_id);
    }

    /**
     * Get the Faculty Supervisor object associated with this internship.
     */
    public function getFacultySupervisor()
    {
        PHPWS_Core::initModClass('intern', 'FacultySupervisor.php');
        return new FacultySupervisor($this->faculty_supervisor_id);
    }

    /**
     * Get the Department object associated with this internship.
     */
    public function getDepartment()
    {
        PHPWS_Core::initModClass('intern', 'Department.php');
        return new Department($this->department_id);
    }

    /**
     * Row tags for DBPager
     */
    public function getRowTags()
    {
        PHPWS_Core::initModClass('intern', 'Term.php');

        $tags = array();
    
        // Get objects associated with this internship.
        $s = $this->getStudent();
        $a = $this->getAgency();
        $f = $this->getFacultySupervisor();
        $d = $this->getDepartment();

        // Student info.
        $tags['STUDENT_NAME'] = $s->getFullName();
        $tags['STUDENT_BANNER'] = $s->banner;
        $tags['GRAD_UGRAD'] = isset($s->graduated) ? 'Graduate' : 'Undergraduate';
        
        // Agency info.
        $tags['AGENCY_NAME'] = $a->name;

        // Dept. info
        $tags['DEPT_NAME'] = $d->name;
        
        // Faculty info.
        $tags['FACULTY_NAME'] = $f->getFullName();

        // Internship info.
        $tags['START_DATE'] = $this->start_date;
        $tags['END_DATE'] = $this->end_date;
        $tags['TERM'] = Term::rawToRead($this->term);
        $tags['ID'] = $this->id;

        // TODO: Finish off fields.
        return $tags;
    }

    /**
     * Create a new internship. Save to DB.
     */
    public static function addInternship()
    {
        PHPWS_Core::initModClass('intern', 'Student.php');
        PHPWS_Core::initModClass('intern', 'Agency.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'FacultySupervisor.php');

        // Required fields check
        $missing = self::checkRequest();
        if(!is_null($missing)){
            // checkRequest returned some missing fields.
            $url = 'index.php?module=intern&action=edit_internship';
            $url .= '&missing='.implode('+', $missing);
            // Throw in values fields the user typed in
            foreach($_POST as $key=>$val){
                $url .= "&$key=$val";
            }
            NQ::simple('intern', INTERN_ERROR, 'Please fill in highlighted fields.');
            NQ::close();
            return PHPWS_Core::reroute($url);
        }

        PHPWS_DB::begin();
        // Check if this student exists already.
        // The main identifier for a student is their Banner ID.
        $student = Student::getStudentByBanner($_REQUEST['banner']);
        if(is_null($student)){
            $student = new Student();
        }
        $student->first_name = $_REQUEST['student_first_name'];
        $student->middle_name = $_REQUEST['student_middle_name'];
        $student->last_name = $_REQUEST['student_last_name'];
        $student->banner = $_REQUEST['banner'];
        $student->phone = $_REQUEST['student_phone'];
        $student->email = $_REQUEST['student_email'];
        $student->grad_prog = $_REQUEST['grad_prog'];
        $student->ugrad_major = $_REQUEST['ugrad_major'];
        $student->graduated = isset($_REQUEST['graduated']);
        try{
            $studentId = $student->save();
        }catch(Exception $e){
            PHPWS_DB::rollback();
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }

        // Create/Save agency
        $agency = new Agency();
        $agency->name = $_REQUEST['agency_name'];
        $agency->address = $_REQUEST['agency_address'];
        $agency->phone = $_REQUEST['agency_phone'];
        $agency->supervisor_first_name = $_REQUEST['agency_sup_first_name'];
        $agency->supervisor_last_name = $_REQUEST['agency_sup_last_name'];
        $agency->supervisor_phone = $_REQUEST['agency_sup_phone'];
        $agency->supervisor_email = $_REQUEST['agency_sup_email'];
        $agency->supervisor_fax = $_REQUEST['agency_sup_fax'];
        $agency->supervisor_address = $_REQUEST['agency_sup_address'];
        try{
            $agencyId = $agency->save();
        }catch(Exception $e){
            PHPWS_DB::rollback();
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }

        // Create/Save Faculty supervisor
        $faculty = new FacultySupervisor();
        $faculty->first_name = $_REQUEST['supervisor_first_name'];
        $faculty->last_name = $_REQUEST['supervisor_last_name'];
        $faculty->email = $_REQUEST['supervisor_email'];
        $faculty->phone = $_REQUEST['supervisor_phone'];
        $faculty->department_id = $_REQUEST['department'];
        try{
            $facultyId = $faculty->save();
        }catch(Exception $e){
            PHPWS_DB::rollback();
            return NQ::simple('intern', INTERN_ERROR, $e->getMessage());
        }

        // Create/Save internship.
        $i = new Internship();
        $i->term = $_REQUEST['term'];
        $i->student_id = $studentId;
        $i->agency_id = $agencyId;
        $i->faculty_supervisor_id = $facultyId;
        $i->department_id = $_REQUEST['department'];
        $i->start_date = strtotime($_REQUEST['start_date']);
        $i->end_date = strtotime($_REQUEST['end_date']);
        $i->credits = $_REQUEST['credits'] == '' ? NULL : $_REQUEST['credits'];
        $i->avg_hours_week = $_REQUEST['avg_hours_week'] == '' ? NULL : $_REQUEST['avg_hours_week'];
        $i->domestic = isset($_REQUEST['domestic']);
        $i->international = isset($_REQUEST['international']);
        $i->paid = isset($_REQUEST['paid']);
        $i->stipend = isset($_REQUEST['stipend']);
        $i->unpaid = isset($_REQUEST['unpaid']);
        try{
            $i->save();
        }catch(Exception $e){
            PHPWS_DB::rollback();
            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            NQ::close();
            return PHPWS_Core::goBack();
        }
        
        PHPWS_DB::commit();
        NQ::simple('intern', INTERN_SUCCESS, 'Added internship for '.$student->getFullName());
    }

    /**
     * Check that required fields are in the REQUEST.
     */
    private static function checkRequest()
    {
        PHPWS_Core::initModClass('intern', 'UI/InternshipUI.php');
        $vals = null;

        foreach(InternshipUI::$requiredFields as $field){
            if(!isset($_REQUEST[$field]) || $_REQUEST[$field] == '' ){
                $vals[] = $field;
            }
        }

        return $vals;
    }
}

?>