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
     * @Override Model::getCSV
     * Get a CSV formatted for for this internship.
     */
    public function getCSV()
    {
        PHPWS_Core::initModClass('intern', 'Term.php');

        $i = array();
        $s = $this->getStudent();
        $a = $this->getAgency();
        $f = $this->getFacultySupervisor();
        $d = $this->getDepartment();
        $i['Start Date'] = $this->getStartDate(true);
        $i['End Date']   = $this->getEndDate(true);
        $i['Term']       = Term::rawToRead($this->term, false);
        $i['Credits']    = $this->credits;
        $i['Average Hours Per Week'] = $this->avg_hours_week;
        $i['Domestic']   = $this->domestic==1 ? 'Yes' : 'No';
        $i['International'] = $this->international==1 ? 'Yes' : 'No';
        $i['Paid']       = $this->paid==1 ? 'Yes' : 'No';
        $i['Stipend']    = $this->stipend==1 ? 'Yes' : 'No';
        $i['Unpaid']     = $this->unpaid==1 ? 'Yes' : 'No';
        // Merge data from other objects.
        $i = array_merge($s->getCSV(), $i );
        $i = array_merge($i, $a->getCSV());
        $i = array_merge($i, $f->getCSV());
        $i = array_merge($i, $d->getCSV());
        
        return $i;
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
     * Get Document objects associated with this internship.
     */
    public function getDocuments()
    {
        PHPWS_Core::initModClass('intern', 'Intern_Document.php');
        $db = Intern_Document::getDB();
        $db->addWhere('internship_id', $this->id);
        return $db->getObjects('Intern_Document');
    }
    
    /**
     * Get formatted dates.
     */
    public function getStartDate($formatted=false)
    {
        if($formatted){
            return date('F j, Y', $this->start_date);
        }else{
            return $this->start_date;
        }
    }
    public function getEndDate($formatted=false)
    {
        if($formatted){
            return date('F j, Y', $this->end_date);
        }else{
            return $this->end_date;
        }
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

        $tags['EDIT'] = PHPWS_Text::moduleLink('Edit', 'intern', array('action' => 'edit_internship', 'id' => $this->id));

        // TODO: Finish off fields.
        return $tags;
    }

    /**
     * Create a CSV file with rows for each internship with
     * ID in $internships.
     * @return filename
     */
    public static function getCSVFile($internships)
    {
        if(sizeof($internships) < 1){
            return null;
        }

        // Create a temporary file for CSV.
        $tmpName = tempnam('/tmp', 'php');
        $handle = fopen($tmpName, 'w');

        // Get the first internship manually so we can fetch the header names.
        $i = new Internship($internships[0]);
        $line = $i->getCSV();
        $headers = array_keys($line);
        fputcsv($handle, $headers); // Write header first.
        fputcsv($handle, $line); // Write first data line.

        // Continue writing the rest of lines.
        for($index = 1; $index < sizeof($internships); $index++){
            $i = new Internship($internships[$index]);
            $line = $i->getCSV();
            fputcsv($handle, $line);
        }

        fclose($handle);
        
        return $tmpName;
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
        $student = new Student();
        if(isset($_REQUEST['student_id'])){
            // User is attempting to edit an internship.
            try{
                $student = new Student($_REQUEST['student_id']);
            }catch(Exception $e){
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Student ID.');
                NQ::close();
                return PHPWS_Core::goBack();
            }
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
        if(isset($_REQUEST['agency_id'])){
            // User is editing internship
            try{
                $agency = new Agency($_REQUEST['agency_id']);
            }catch(Exception $e){
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Agency ID.');
                NQ::close();
                return PHPWS_Core::goBack();
            }
        }
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
        if(isset($_REQUEST['supervisor_id'])){
            // User is editing internship
            try{
                $faculty = new FacultySupervisor($_REQUEST['supervisor_id']);
            }catch(Exception $e){
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Faculty Supervisor ID.');
                NQ::close();
                return PHPWS_Core::goBack();
            }
        }
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
        if(isset($_REQUEST['internship_id'])){
            // User is editing internship
            try{
                $i = new Internship($_REQUEST['internship_id']);
            }catch(Exception $e){
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Internship ID.');
                NQ::close();
                return PHPWS_Core::goBack();
            }
        }
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
        if(isset($_REQUEST['student_id'])){
            // Show message if user edited internship
            NQ::simple('intern', INTERN_SUCCESS, 'Saved internship for '.$student->getFullName());
            NQ::close();
            return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&id='.$i->id);
        }else{
            NQ::simple('intern', INTERN_SUCCESS, 'Added internship for '.$student->getFullName());
            NQ::close();
            return PHPWS_Core::reroute('index.php?module=intern&action=edit_internship&id='.$i->id);
        }
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