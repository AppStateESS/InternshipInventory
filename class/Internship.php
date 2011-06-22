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
    public $notes;
    public $internship;
    public $service_learn;
    public $independent_study;
    public $research_assist;
    public $student_teaching;
    public $clinical_practica;
    public $special_topics;
    public $other_type;

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
        $i['Internship'] = $this->internship==1 ? 'Yes' : 'No';
        $i['Service Learning']   = $this->service_learn==1 ? 'Yes' : 'No';
        $i['Independent Study']  = $this->independent_study==1 ? 'Yes' : 'No';
        $i['Research Assistant'] = $this->research_assist==1 ? 'Yes' : 'No';
        $i['Student Teaching']   = $this->student_teaching==1 ? 'Yes' : 'No';
        $i['Clinical Practica']  = $this->clinical_practica==1 ? 'Yes' : 'No';
        $i['Special Topics']     = $this->special_topics==1 ? 'Yes' : 'No';
        $i['Other Type']         = $this->other_type;
        $i['Notes']              = $this->notes;
        // Merge data from other objects.
        $i = array_merge($s->getCSV(), $i );
        $i = array_merge($i, $a->getCSV());
        $i = array_merge($i, $f->getCSV());
        $i = array_merge($i, $d->getCSV());
        
        return $i;
    }


    private function toggle($pdf, $header=true){
        if($header){
            // Set header font.
            $pdf->setFont('Arial', 'B', '10');
        }else{
            // Set data font.
            $pdf->setFont('Courier', '', '10');
        }
    }

    /**
     * Get a comma separated list of the types for
     * this internship.
     */
    public function getReadableTypes()
    {
        $types = array();
        
        if($this->internship == 1){
            $types[] = 'Internship';
        }
        if($this->service_learn == 1){
            $types[] = 'Service Learning';
        }
        if($this->independent_study == 1){
            $types[] = 'Independent Study';
        }
        if($this->research_assist == 1){
            $types[] = 'Research Assistant';
        }
        if($this->student_teaching == 1){
            $types[] = 'Student Teaching';
        }
        if($this->clinical_practica == 1){
            $types[] = 'Clinical Practica';
        }
        if($this->special_topics == 1){
            $types[] = 'Special Topics';
        }
        if($this->other_type != '' && $this->other_type != null){
            $types[] = $this->other_type;
        }
        
        return implode(', ', $types);
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
     * Is this internship domestic?
     */
    public function isDomestic()
    {
        if($this->domestic == 1){
            return true;
        }else{
            return false;
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
        $tags['PDF'] = PHPWS_Text::moduleLink('Summary Report', 'intern', array('action' => 'pdf', 'id' => $this->id));

        // TODO: Finish off fields.
        return $tags;
    }

    /**
     * Get internship types in an associative array.
     */
    public static function getTypesAssoc()
    {
        return array('internship' => 'Internship',
                     'service_learn' => 'Service Learning',
                     'independent_study' => 'Independent Study',
                     'research_assistant' => 'Research Assistant',
                     'student_teaching' => 'Student Teaching',
                     'clinical_practica' => 'Clinical Practica In Health Fields',
                     'special_topics' => 'Special Topics');
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
        if(!is_null($missing) && !empty($missing)){
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
        /* See if this student exists already */
        $student = Student::getStudentByBanner($_REQUEST['banner']);
        if(isset($_REQUEST['student_id'])){
            /* User is attempting to edit internship. Student ID should be passed in. */
            try{
                $student = new Student($_REQUEST['student_id']);
            }catch(Exception $e){
                PHPWS_DB::rollback();
                NQ::simple('intern', INTERN_ERROR, 'Invalid Student ID.');
                NQ::close();
                return PHPWS_Core::goBack();
            }
        }else if(is_null($student)){
            /* Not student exists by banner ID and this is a new internship. */
            $student = new Student();
        }

        $student->first_name = $_REQUEST['student_first_name'];
        $student->middle_name = $_REQUEST['student_middle_name'];
        $student->last_name = $_REQUEST['student_last_name'];
        $student->banner = $_REQUEST['banner'];
        $student->phone = $_REQUEST['student_phone'];
        $student->email = $_REQUEST['student_email'];
        $student->grad_prog   = $_REQUEST['grad_prog'] == -1 ? null : $_REQUEST['grad_prog'];
        $student->ugrad_major = $_REQUEST['ugrad_major'] == -1 ? null : $_REQUEST['ugrad_major'];

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
        $agency->name    = $_REQUEST['agency_name'];
        $agency->address = $_REQUEST['agency_address'];
        $agency->city    = $_REQUEST['agency_city'];
        $agency->zip     = $_REQUEST['agency_zip'];
        $agency->phone   = $_REQUEST['agency_phone'];

        if($_REQUEST['location'] == 'internat'){
            /* Location is INTERNATIONAL. Country is required. Province was typed in. */
            $agency->state   = $_REQUEST['agency_state'];
            $agency->country = $_REQUEST['agency_country'];
            $agency->supervisor_state   = $_REQUEST['agency_state'];
            $agency->supervisor_country = $_REQUEST['agency_sup_country'];
        }else{
            /* Location is DOMESTIC. Country is U.S. State was chosen from drop down */
            $agency->state   = $_REQUEST['agency_state'] == -1 ? null : $_REQUEST['agency_state'];
            $agency->country = 'United States';
            $agency->supervisor_state   = $_REQUEST['agency_state'] == -1 ? null : $_REQUEST['agency_state'];
            $agency->supervisor_country = 'United States';
        }

        $agency->supervisor_first_name = $_REQUEST['agency_sup_first_name'];
        $agency->supervisor_last_name  = $_REQUEST['agency_sup_last_name'];
        $agency->supervisor_phone = $_REQUEST['agency_sup_phone'];
        $agency->supervisor_email = $_REQUEST['agency_sup_email'];
        $agency->supervisor_fax   = $_REQUEST['agency_sup_fax'];
        $agency->supervisor_address = $_REQUEST['agency_sup_address'];
        $agency->supervisor_city    = $_REQUEST['agency_sup_city'];
        $agency->supervisor_zip     = $_REQUEST['agency_sup_zip'];
        $agency->address_same_flag  = isset($_REQUEST['copy_address']) ? 't' : 'f';

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
        // Check dates
        if($i->start_date > $i->end_date){
            PHPWS_DB::rollback();
            NQ::simple('intern', INTERN_WARNING, 'Start date needs to be before end date.');
            NQ::close();
            return PHPWS_Core::goBack();
        }
        $i->credits = $_REQUEST['credits'] == '' ? NULL : $_REQUEST['credits'];
        $i->avg_hours_week = $_REQUEST['avg_hours_week'] == '' ? NULL : $_REQUEST['avg_hours_week'];
        $i->domestic = $_REQUEST['location'] == 'domestic';
        $i->international = $_REQUEST['location'] == 'internat';
        $i->paid = $_REQUEST['payment'] == 'paid';
        $i->stipend = isset($_REQUEST['stipend']) && $i->paid;
        $i->unpaid = $_REQUEST['payment'] == 'unpaid';
        $i->internship = isset($_REQUEST['internship_default_type']);
        $i->service_learn = isset($_REQUEST['service_learning_type']);
        $i->independent_study = isset($_REQUEST['independent_study_type']);
        $i->research_assist = isset($_REQUEST['research_assist_type']);
        $i->student_teaching = isset($_REQUEST['student_teaching_type']);
        $i->clinical_practica = isset($_REQUEST['clinical_practica_type']);
        $i->special_topics = isset($_REQUEST['special_topics_type']);
        $i->other_type =  isset($_REQUEST['check_other_type']) ? $_REQUEST['other_type'] : null;
        $i->notes = $_REQUEST['notes'];

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
            /* If not set or is empty (For text fields) */
            if(!isset($_REQUEST[$field]) || $_REQUEST[$field] == '' ){
                $vals[] = $field;
            }
        }

        /* Required select boxes should not equal -1 */
        if(!isset($_REQUEST['department']) ||
           $_REQUEST['department'] == -1){
            $vals[] = 'department';
        }
        if(!isset($_REQUEST['term']) ||
           $_REQUEST['term'] == -1){
            $vals[] = 'term';
        }

        /**
         * Funky stuff here for location. 
         * If location is DOMESTIC then State and Zip are required.
         * If location is INTERNATIONAL then state and zip are not required
         * and are set to null though Country is required.
         */

        if($_REQUEST['location'] == 'internat'){
            if(!isset($_REQUEST['agency_country']) || $_REQUEST['agency_country'] == '')
                // Add country to missing for Agency.
                $vals[] = 'agency_country';
            if(!isset($_REQUEST['agency_sup_country']) || $_REQUEST['agency_sup_country'] == '')
                // Add country to missing for Agency Supervisor.
                $vals[] = 'agency_sup_country';
        }else if($_REQUEST['location'] == 'domestic'){
            if(!isset($_REQUEST['agency_state']) || $_REQUEST['agency_state'] == -1){
                // Add state to missing
                $vals[] = 'agency_state';
            }
            if(!isset($_REQUEST['agency_zip']) || $_REQUEST['agency_zip'] == ''){
                // Add zip to missing
                $vals[] = 'agency_zip';
            }
            if(!isset($_REQUEST['agency_sup_state']) || $_REQUEST['agency_sup_state'] == -1){
                // Add state to missing (supervisor)
                $vals[] = 'agency_sup_state';
            }
            if(!isset($_REQUEST['agency_sup_zip']) || $_REQUEST['agency_sup_zip'] == ''){
                // Add zip to missing (supervisor)
                $vals[] = 'agency_sup_zip';
            }
        }

        return $vals;
    }

    /**
     * Generate a PDF Report for this internship.
     * Filename is returned.
     */
    public function getPDF()
    {
        require_once('fpdf.php');
        PHPWS_Core::initModClass('intern', 'Term.php');


        $pdf = new FPDF('P', 'mm', 'A4');
        $s = $this->getStudent();
        $a = $this->getAgency();
        $d = $this->getDepartment();
        $f = $this->getFacultySupervisor();
        $m = $s->getUgradMajor();
        $g = $s->getGradProgram();

        $pdf->addPage();

        /* Student name/banner */
        $pdf->setFont('Arial', 'BI', '16');
        $pdf->setLineWidth(.8);
        $pdf->multiCell(0, 10, $s->getFullName().' -- '.$s->banner. ' -- '.$a->name, 'B');
        $pdf->ln(2);

        $pdf->setDrawColor(150);
        $pdf->setLineWidth(0);
        /* 
         * Internship information
         */
        $pdf->cell(0, 12, 'Internship Details');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(15, 5, 'Term:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(175, 5, Term::rawToRead($this->term, false), 'RT');
        $pdf->ln();
        $this->toggle($pdf);
        $types = $this->getReadableTypes();
        // If the width of the string of types is greater than 139 (found by trial/error)
        // then we need to correct the header's alignment and left border.
        if($pdf->getStringWidth($types) > 139){
            $pdf->cell(15, 10, 'Type:', 'LT');
        }else{
            $pdf->cell(15, 5, 'Type:', 'LT');
        }
        $this->toggle($pdf, false);
        $pdf->multiCell(175, 5, $types, 'RT');
        $this->toggle($pdf);
        $pdf->cell(20, 5, 'Start Date:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(75, 5, $this->getStartDate(true), 'RT');
        $this->toggle($pdf);
        $pdf->cell(20, 5, 'End Date:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(75, 5, $this->getEndDate(true), 'RT');
        $pdf->ln();
        
        /* Department */
        $this->toggle($pdf);
        $pdf->cell(25, 5, 'Department:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(165, 5, $d->name, 'RT');
        $pdf->ln();

        /* Hours */
        $this->toggle($pdf);
        $pdf->cell(25, 5, 'Credit Hours:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(70, 5, $this->credits, 'RT');
        $this->toggle($pdf);
        $pdf->cell(32, 5, 'Avg. Hours/Week:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(63, 5, $this->avg_hours_week, 'RT');
        $pdf->ln();
        
        /* Location */
        $this->toggle($pdf);
        $pdf->cell(20, 5, 'Domestic:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(75, 5, $this->domestic==1?'Yes':'No', 'RT');
        $this->toggle($pdf);
        $pdf->cell(25, 5, 'International:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(70, 5, $this->international==1?'Yes':'No', 'RT');
        $pdf->ln();

        /* Payment */
        $this->toggle($pdf);
        $pdf->cell(12, 5, 'Paid:', 'LTB');
        $this->toggle($pdf, false);
        $pdf->cell(83, 5, $this->paid==1 && $this->unpaid==0?'Yes':'No', 'RTB');// TODO: Verify logic for paid/unpaid.
        $this->toggle($pdf);
        $pdf->cell(30, 5, 'Stipend Based:', 'LTB');
        $this->toggle($pdf, false);
        $pdf->cell(65, 5, $this->stipend==1?'Yes':'No', 'RTB');
        $pdf->ln();
        
        
        /*
         * Student information.
         */
        $pdf->setFont('Arial', 'BI', '16');
        $pdf->cell(0, 12, 'Student');
        $pdf->ln();
        
        $this->toggle($pdf);
        $pdf->cell(15, 5, 'Name:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(175, 5, $s->getFullName(), 'RT');
        $pdf->ln();
        
        $this->toggle($pdf);
        $pdf->cell(18, 5, 'Banner:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(77, 5, $s->banner, 'RT');
        $this->toggle($pdf);
        $pdf->cell(15, 5, 'Email:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(80, 5, $s->email, 'RT');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(15, 5, 'Major:', 'LT');
        $this->toggle($pdf, false);
        if(!is_null($m)){
            $pdf->cell(175, 5, $m->getName(), 'RT');
        }else{
            $pdf->cell(175, 5, 'N/A', 'RT');
        }
        $pdf->ln();
        $this->toggle($pdf);
        $pdf->cell(35, 5, 'Graduate Program:', 'LTB');
        $this->toggle($pdf, false);
        if(!is_null($g)){
            $pdf->cell(155, 5, $g->getName(), 'RTB');
        }else{
            $pdf->cell(155, 5, 'N/A', 'RTB');
        }
        $pdf->ln();

        /**
         * Faculty supervisor information.
         */
        $pdf->setFont('Arial', 'BI', '16');
        $pdf->cell(0, 12, 'Faculty Supervisor');
        $pdf->ln();
        $this->toggle($pdf);
        $pdf->cell(18, 5, 'Name:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(172,  5, $f->getFullName(), 'RT');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(18, 5, 'Phone:', 'LTB');
        $this->toggle($pdf, false);
        $pdf->cell(77, 5, $f->phone, 'RTB');
        $this->toggle($pdf);
        $pdf->cell(15, 5, 'Email:', 'LTB');
        $this->toggle($pdf, false);
        $pdf->cell(80, 5, $f->email, 'RTB');
        $pdf->ln();

        /**
         * Agency information.
         */
        $pdf->setFont('Arial', 'BI', '16');
        $pdf->cell(0, 12, 'Agency');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(18, 5, 'Name:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(172, 5, $a->name, 'RT');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(20, 5, 'Address:', 'LT');
        $this->toggle($pdf, false);
        if($this->domestic == 1)
            $pdf->cell(170, 5, $a->getDomesticAddress(), 'RT');
        else
            $pdf->cell(170, 5, $a->getInternationalAddress(), 'RT');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(18, 5, 'Phone:', 'LTB');
        $this->toggle($pdf, false);
        $pdf->cell(172, 5, $a->phone, 'RTB');
        $pdf->ln();

        /** 
         * Agency supervisor info.
         */
        $pdf->setFont('Arial', 'BI', '16');
        $pdf->cell(0, 12, 'Agency Supervisor');
        $pdf->ln();
        
        $this->toggle($pdf);
        $pdf->cell(15, 5, 'Name:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(175, 5, $a->getSupervisorFullName(), 'RT');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(20, 5, 'Address:', 'LT');
        $this->toggle($pdf, false);
        if($this->domestic == 1)
            $pdf->cell(170, 5, $a->getSuperDomesticAddress(), 'RT');
        else
            $pdf->cell(170, 5, $a->getSuperInternationalAddress(), 'RT');
        $pdf->ln();

        $this->toggle($pdf);
        $pdf->cell(18, 5, 'Email:', 'LT');
        $this->toggle($pdf, false);
        $pdf->cell(172, 5, $a->supervisor_email, 'RT');
        $pdf->ln();
        
        $this->toggle($pdf);
        $pdf->cell(18, 5, 'Phone:', 'LTB');
        $this->toggle($pdf, false);
        $pdf->cell(77, 5, $a->supervisor_phone, 'RTB');
        $this->toggle($pdf);
        $pdf->cell(12, 5, 'Fax:', 'LTB');
        $this->toggle($pdf, false);
        $pdf->cell(83, 5, $a->supervisor_fax, 'RTB');
        $pdf->ln();

        /* Notes */
        $pdf->setFont('Arial', 'BI', '16');
        $pdf->cell(0, 12, 'Notes');
        $pdf->ln();
        $this->toggle($pdf, false);
        $pdf->multiCell(0, 5, $this->notes, 1);
        $pdf->ln();

        
        $pdf->output();
    }
}

?>