<?php

  /**
   * This class holds the form for adding/editing an internship.
   */
PHPWS_Core::initModClass('intern', 'UI/UI.php');
class InternshipUI implements UI
{
    public static $requiredFields = array('student_first_name', 'student_last_name', 
                                          'banner','student_phone','student_email',
                                          'supervisor_first_name', 'supervisor_last_name', 
                                          'supervisor_email','supervisor_phone',
                                          'department', 'agency_name', 'agency_address', 
                                          'agency_city', 'agency_state', 'agency_zip',
                                          'agency_phone', 'agency_sup_first_name',
                                          'agency_sup_last_name', 'agency_sup_phone',
                                          'agency_sup_email', 'agency_sup_fax',
                                          'agency_sup_address', 'agency_sup_city',
                                          'agency_sup_state', 'agency_sup_zip',
                                          'term', 'start_date', 'end_date');
 

    public static function display()
    {
        PHPWS_Core::initModClass('intern', 'Internship.php');

        $tpl = array();

        if(isset($_REQUEST['id'])){
            try{
                $internship = new Internship($_REQUEST['id']);
                $form = self::getInternshipForm($internship);
                $tpl['PDF'] = PHPWS_Text::moduleLink('Download Summary Report', 'intern', array('action' => 'pdf', 'id' => $internship->id));
                self::plugInternship($form, $internship);
            }catch(Exception $e){
                NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            }
        }else{
            $form = self::getInternshipForm();
        }
        // If 'missing' is set then we have been redirected 
        // back to the form because the user didn't type in something and
        // somehow got past the javascript.
        if(isset($_REQUEST['missing'])){
            $missing = explode(' ', $_REQUEST['missing']);
            // Intersect with the required fields array so we're not json encoding
            // any funny stuff.
            $missing = array_intersect(self::$requiredFields, $missing);
            javascript('/modules/intern/missing', array('MISSING' => json_encode($missing)));
            // Set classes on field we are missing. Do this in PHP cuz the user might 
            // have JS disabled...don't know why they would but just in case.
            foreach($missing as $m){
                $form->setClass($m, 'missing');
            }
            
            // Plug old values back into form fields. 
            $form->plugIn($_GET);
        }

        $form->mergeTemplate($tpl);
        Layout::addPageTitle('Add Internship');
        return PHPWS_Template::process($form->getTemplate(), 'intern', 'add_internship.tpl');
    }
    
    /**
     * Build the form for adding/editing an internship.
     *
     * If there is an Internship obj passed as parameter
     * then fill in the form with that Internship's fields.
     */
    public static function getInternshipForm(Internship $i=NULL)
    {
        PHPWS_Core::initModClass('intern', 'Term.php');
        PHPWS_Core::initModClass('intern', 'Department.php');
        PHPWS_Core::initModClass('intern', 'Major.php');
        PHPWS_Core::initModClass('intern', 'GradProgram.php');

        $form = new PHPWS_Form('internship');
        if(!is_null($i))
            $s = $i->getStudent();
        $form->setAction('index.php?module=intern&action=add_internship');
        $form->addSubmit('submit', 'Save');

        /**
         * Student fields
         */
        $form->addText('student_first_name');
        $form->setLabel('student_first_name', 'First Name');
        $form->addText('student_middle_name');
        $form->setLabel('student_middle_name', 'Middle Name/Initial');
        $form->addText('student_last_name');
        $form->setLabel('student_last_name', 'Last Name');
        $form->addText('banner');
        $form->setLabel('banner', 'Banner ID');// Digits only
        $form->addText('student_phone');
        $form->setLabel('student_phone', 'Phone');
        $form->addText('student_email');
        $form->setLabel('student_email', 'Email');
        if(!is_null($i))
            $majors = Major::getMajorsAssoc($s->ugrad_major);
        else
            $majors = Major::getMajorsAssoc();
        $form->addSelect('ugrad_major', $majors);
        $form->setLabel('ugrad_major', 'Undergraduate Major');
        if(!is_null($i))
            $progs = GradProgram::getGradProgsAssoc($s->grad_prog);
        else
            $progs = GradProgram::getGradProgsAssoc();
        $form->addSelect('grad_prog', $progs);
        $form->setLabel('grad_prog', 'Graduate Program');
        $form->addCheck('graduated');
        $form->setLabel('graduated', 'Graduated');

        /**
         * Faculty supervisor info.
         */
        $form->addText('supervisor_first_name');
        $form->setLabel('supervisor_first_name', 'First Name');
        $form->addText('supervisor_last_name');
        $form->setLabel('supervisor_last_name', 'Last Name');
        $form->addText('supervisor_email');
        $form->setLabel('supervisor_email', 'Email');
        $form->addText('supervisor_phone');
        $form->setLabel('supervisor_phone', 'Phone');
        // Deitys can view all departments.
        if(Current_User::isDeity()){
            $depts = Department::getDepartmentsAssoc();
        }else{
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
        }
        $form->addSelect('department', $depts);
        $form->setLabel('department', 'Department');

        /**
         * Agency supervisor info.
         */
        $form->addText('agency_name');
        $form->setLabel('agency_name', 'Name');
        $form->addText('agency_address');
        $form->setLabel('agency_address', 'Address');
        $form->addText('agency_city');
        $form->setLabel('agency_city', 'City');
        $form->addSelect('agency_state', self::$state_list);
        $form->setLabel('agency_state', 'State');
        $form->addText('agency_zip');
        $form->setLabel('agency_zip', 'Zip Code');
        $form->addText('agency_phone');
        $form->setLabel('agency_phone', 'Phone');
        $form->addText('agency_sup_first_name');
        $form->setLabel('agency_sup_first_name', 'First Name');
        $form->addText('agency_sup_last_name');
        $form->setLabel('agency_sup_last_name', 'Last Name');
        $form->addText('agency_sup_phone');
        $form->setLabel('agency_sup_phone', 'Phone');
        $form->addText('agency_sup_email');
        $form->setLabel('agency_sup_email', 'Email');
        $form->addCheck('copy_address');
        $form->setLabel('copy_address', "Supervisor's address is same as agency's");
        $form->addText('agency_sup_address');
        $form->setLabel('agency_sup_address', 'Address');
        $form->addText('agency_sup_city');
        $form->setLabel('agency_sup_city', 'City');
        $form->addSelect('agency_sup_state', self::$state_list);
        $form->setLabel('agency_sup_state', 'State');
        $form->addText('agency_sup_zip');
        $form->setLabel('agency_sup_zip', 'Zip Code');
        $form->addText('agency_sup_fax');
        $form->setLabel('agency_sup_fax', 'Fax');

        /**
         * Internship details.
         */
        $form->addSelect('term', Term::getTermsAssoc());
        $form->setLabel('term', 'Select Term');
        $form->addText('start_date');
        $form->setLabel('start_date', 'Start Date');
        $form->addText('end_date');
        $form->setLabel('end_date', 'End Date');
        $form->addText('credits');
        $form->setLabel('credits', 'Credits');
        $form->addText('avg_hours_week');
        $form->setLabel('avg_hours_week', 'Average Hours per Week');
        $loc = array('domestic' => 'Domestic', 'internat' => 'International');
        $form->addRadioAssoc('location', $loc);
        $pay = array('unpaid' => 'Unpaid', 'paid' => 'Paid');
        $form->addRadioAssoc('payment', $pay);
        $form->addCheck('stipend');
        $form->setLabel('stipend', 'Stipend');
        $form->addCheck('internship_default_type');
        $form->setLabel('internship_default_type', 'Internship');
        $form->addCheck('service_learning_type');
        $form->setLabel('service_learning_type', 'Service Learning');
        $form->addCheck('independent_study_type');
        $form->setLabel('independent_study_type', 'Independent Study');
        $form->addCheck('research_assist_type');
        $form->setLabel('research_assist_type', 'Research Assistant');
        $form->addCheck('check_other_type');
        $form->addText('other_type');
        $form->setLabel('other_type', 'Other Type');
        
        $form->addTextArea('notes');
        $form->setLabel('notes', 'Notes');

        // Label required fields
        foreach(self::$requiredFields as $field){
            $form->setRequired($field);
        }

        javascript('/jquery/');
        javascript('/jquery_ui/');
        javascript('/modules/intern/copyAddress');
        javascript('/modules/intern/formGoodies');

        return $form;
    }

    /**
     * Load up a form's fields with the internship's information.
     */
    private static function plugInternship(PHPWS_Form $form, Internship $i)
    {
        $vals = array();

        $s = $i->getStudent();
        $a = $i->getAgency();
        $f = $i->getFacultySupervisor();
        $d = $i->getDepartment();

        // Student
        $form->addHidden('student_id', $s->id);
        $vals['student_first_name'] = $s->first_name;
        $vals['student_middle_name'] = $s->middle_name;
        $vals['student_last_name'] = $s->last_name;
        $vals['banner'] = $s->banner;
        $vals['student_phone'] = $s->phone;
        $vals['student_email'] = $s->email;
        $vals['grad_prog'] = $s->grad_prog;
        $vals['ugrad_major'] = $s->ugrad_major;
        $vals['graduated'] = $s->graduated;

        // Agency
        $form->addHidden('agency_id', $a->id);
        $vals['agency_name'] = $a->name;
        $vals['agency_address'] = $a->address;
        $vals['agency_city'] = $a->city;
        $vals['agency_state'] = $a->state;
        $vals['agency_zip'] = $a->zip;
        $vals['agency_phone'] = $a->phone;
        $vals['agency_sup_first_name'] = $a->supervisor_first_name;
        $vals['agency_sup_last_name'] = $a->supervisor_last_name;
        $vals['agency_sup_phone'] = $a->supervisor_phone;
        $vals['agency_sup_email'] = $a->supervisor_email;
        $vals['agency_sup_fax'] = $a->supervisor_fax;
        $vals['agency_sup_address'] = $a->supervisor_address;
        $vals['agency_sup_city'] = $a->supervisor_city;
        $vals['agency_sup_state'] = $a->supervisor_state;
        $vals['agency_sup_zip'] = $a->supervisor_zip;
        $vals['copy_address'] = $a->address_same_flag == 't';

        // Faculty supervisor
        $form->addHidden('supervisor_id', $f->id);
        $vals['supervisor_first_name'] = $f->first_name;
        $vals['supervisor_last_name'] = $f->last_name;
        $vals['supervisor_email'] = $f->email;
        $vals['supervisor_phone'] = $f->phone;

        // Internship
        $form->addHidden('internship_id', $i->id);
        $vals['start_date'] = date('m/d/Y', $i->start_date);
        $vals['end_date'] = date('m/d/Y', $i->end_date);
        $vals['credits'] = $i->credits;
        $vals['avg_hours_week'] = $i->avg_hours_week;
        $vals['other_type'] = $i->other_type;
        $vals['notes'] = $i->notes;

        // Department
        $vals['department'] = $i->department_id;

        // Other internship details
        if($i->domestic){
            $form->setMatch('location', 'domestic');
        }else{
            $form->setMatch('location', 'internat');
        }
        if($i->paid){
            $form->setMatch('payment', 'paid');
            $form->setMatch('stipend', $i->stipend);
        }else{
            $form->setMatch('payment', 'unpaid');
        }
        $form->setMatch('term', $i->term);
        $form->setMatch('internship_default_type', $i->internship);
        $form->setMatch('service_learning_type', $i->service_learn);
        $form->setMatch('independent_study_type', $i->independent_study);
        $form->setMatch('research_assist_type', $i->research_assist);
        if($i->other_type != '' && !is_null($i->other_type)){
            $form->setMatch('check_other_type', true);
        }
  
        // Plug 
        $form->plugIn($vals);
    }
    /* http://www.bytemycode.com/snippets/snippet/454/ */
    public static $state_list = array('AL'=>"Alabama",
                                      'AK'=>"Alaska",  
                                      'AZ'=>"Arizona",  
                                      'AR'=>"Arkansas",  
                                      'CA'=>"California",  
                                      'CO'=>"Colorado",  
                                      'CT'=>"Connecticut",  
                                      'DE'=>"Delaware",  
                                      'DC'=>"District Of Columbia",  
                                      'FL'=>"Florida",  
                                      'GA'=>"Georgia",  
                                      'HI'=>"Hawaii",  
                                      'ID'=>"Idaho",  
                                      'IL'=>"Illinois",  
                                      'IN'=>"Indiana",  
                                      'IA'=>"Iowa",  
                                      'KS'=>"Kansas",  
                                      'KY'=>"Kentucky",  
                                      'LA'=>"Louisiana",  
                                      'ME'=>"Maine",  
                                      'MD'=>"Maryland",  
                                      'MA'=>"Massachusetts",  
                                      'MI'=>"Michigan",  
                                      'MN'=>"Minnesota",  
                                      'MS'=>"Mississippi",  
                                      'MO'=>"Missouri",  
                                      'MT'=>"Montana",
                                      'NE'=>"Nebraska",
                                      'NV'=>"Nevada",
                                      'NH'=>"New Hampshire",
                                      'NJ'=>"New Jersey",
                                      'NM'=>"New Mexico",
                                      'NY'=>"New York",
                                      'NC'=>"North Carolina",
                                      'ND'=>"North Dakota",
                                      'OH'=>"Ohio",  
                                      'OK'=>"Oklahoma",  
                                      'OR'=>"Oregon",  
                                      'PA'=>"Pennsylvania",  
                                      'RI'=>"Rhode Island",  
                                      'SC'=>"South Carolina",  
                                      'SD'=>"South Dakota",
                                      'TN'=>"Tennessee",  
                                      'TX'=>"Texas",  
                                      'UT'=>"Utah",  
                                      'VT'=>"Vermont",  
                                      'VA'=>"Virginia",  
                                      'WA'=>"Washington",  
                                      'WV'=>"West Virginia",  
                                      'WI'=>"Wisconsin",  
                                      'WY'=>"Wyoming");
}

?>