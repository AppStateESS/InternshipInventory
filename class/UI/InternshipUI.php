<?php

  /**
   * This class holds the form for adding/editing an internship.
   */
PHPWS_Core::initModClass('intern', 'UI/UI.php');
class InternshipUI implements UI
{
    public static function display()
    {
        PHPWS_Core::initModClass('intern', 'Internship.php');

        $tpl = array();
        $tpl['HOME_LINK'] = PHPWS_Text::moduleLink('Back to Menu', 'intern');

        // TODO: PERMISSIONS
        $form = self::getInternshipForm();

        if(isset($_REQUEST['id'])){
            try{
                $internship = new Internship($_REQUEST['id']);
                self::plugInternship($form, $internship);
            }catch(Exception $e){
                NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            }
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

        $form = new PHPWS_Form('internship');
        $form->setAction('index.php?module=intern&action=add_internship');
        $form->addSubmit('submit', 'Save');
        
        // Add form fields.
        $form->addSelect('term', Term::getTermsAssoc());
        $form->setLabel('term', 'Select Term');
        
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
        // TODO: DB table for majors
        $majors = array('none'=> 'None', 'comp sci' => 'comp sci', 'art' => 'art', 'math' => 'math');
        $form->addSelect('ugrad_major', $majors);
        $form->setLabel('ugrad_major', 'Undergraduate Major');
        // TODO: DB table for grad programs
        $gradProgs = array('none'=>'None', 'accounting' => 'accounting', 'something' => 'something');
        $form->addSelect('grad_prog', $gradProgs);
        $form->setLabel('grad_prog', 'Graduate Program');
        $form->addCheck('graduated');
        $form->setLabel('graduated', 'Graduated?');

        /**
         * Faculty supervisor info.
         */
        $form->addText('supervisor_first_name');
        $form->setLabel('supervisor_first_name', 'First Name');
        $form->addText('supervisor_middle_name');
        $form->setLabel('supervisor_middle_name', 'Middle Name/Initial');
        $form->addText('supervisor_last_name');
        $form->setLabel('supervisor_last_name', 'Last Name');
        $form->addText('supervisor_email');
        $form->setLabel('supervisor_email', 'Email');
        $form->addText('supervisor_phone');
        $form->setLabel('supervisor_phone', 'Phone');
        $depts = Department::getDepartmentsAssoc();
        $form->addSelect('department', $depts);
        $form->setLabel('department', 'Department');

        /**
         * Agency supervisor info.
         */
        $form->addText('agency_name');
        $form->setLabel('agency_name', 'Name');
        $form->addText('agency_address');
        $form->setLabel('agency_address', 'Address');
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
        $form->addText('agency_sup_address');
        $form->setLabel('agency_sup_address', 'Address');
        $form->addText('agency_sup_fax');
        $form->setLabel('agency_sup_fax', 'Fax');

        /**
         * Internship details.
         */
        $form->addText('start_date');
        $form->setLabel('start_date', 'Start Date');
        $form->addText('end_date');
        $form->setLabel('end_date', 'End Date');
        $form->addText('credits');
        $form->setLabel('credits', 'Credits');
        $form->addText('avg_hours_week');
        $form->setLabel('avg_hours_week', 'Average Hours per Week');
        $form->addCheck('domestic');
        $form->setLabel('domestic', 'Domestic?');
        $form->addCheck('internat');
        $form->setLabel('internat', 'International?');
        $form->addCheck('paid');
        $form->setLabel('paid', 'Paid?');
        $form->addCheck('unpaid', 'Paid?');
        $form->setLabel('unpaid', 'Unpaid?');
        $form->addCheck('stipend');
        $form->setLabel('stipend', 'Stipend?');

        javascript('/jquery/');
        javascript('/jquery_ui/');

        return $form;
    }

    /**
     * Load up the passed form's fields with the internship's information.
     */
    private static function plugInternship(PHPWS_Form $form, Internship $i)
    {
        $vals = array();

        $s = $i->getStudent();
        $a = $i->getAgency();
        $f = $i->getFacultySupervisor();
        $d = $i->getDepartment();

        // Student
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
        $vals['agency_name'] = $a->name;
        $vals['agency_address'] = $a->address;
        $vals['agency_phone'] = $a->phone;
        $vals['agency_sup_first_name'] = $a->supervisor_first_name;
        $vals['agency_sup_last_name'] = $a->supervisor_last_name;
        $vals['agency_sup_phone'] = $a->supervisor_phone;
        $vals['agency_sup_email'] = $a->supervisor_email;
        $vals['agency_sup_fax'] = $a->supervisor_fax;
        $vals['agency_sup_address'] = $a->supervisor_address;

        // Faculty supervisor
        $vals['supervisor_first_name'] = $f->first_name;
        $vals['supervisor_last_name'] = $f->last_name;
        $vals['supervisor_email'] = $f->email;
        $vals['supervisor_phone'] = $f->phone;

        // Internship
        $vals['start_date'] = date('m/d/Y', $i->start_date);
        $vals['end_date'] = date('m/d/Y', $i->end_date);
        $vals['credits'] = $i->credits;
        $vals['avg_hours_week'] = $i->avg_hours_week;

        // Department
        $vals['department'] = $i->department_id;

        // Other internship details
        $form->setMatch('domestic', $i->domestic);
        $form->setMatch('international', $i->international);
        $form->setMatch('paid', $i->paid);
        $form->setMatch('unpaid', $i->unpaid);
        $form->setMatch('stipend', $i->stipend);
        $form->setMatch('term', $i->term);
        // Plug 
        $form->plugIn($vals);
    }
}

?>