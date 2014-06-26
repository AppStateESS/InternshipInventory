<?php
PHPWS_Core::initModClass('intern', 'InternshipFormView.php');

PHPWS_Core::initModClass('intern', 'Department.php');
PHPWS_Core::initModClass('intern', 'Major.php');
PHPWS_Core::initModClass('intern', 'GradProgram.php');
PHPWS_Core::initModClass('intern', 'Subject.php');


/**
 * View class for showing the big internship form for
 * editing an existing internship.
 *
 * @see Internship
 * @see InternshipFormView
 * @author jbooker
 * @package intern
 *         
 */
class EditInternshipFormView extends InternshipFormView {

    private $agency;

    private $department;

    private $formVals;

    /**
     * Constructor for the big Internship form.
     *
     * @param Internship $i
     * @param Agency $a
     */
    public function __construct($pageTitle, Internship $i)
    {
        // Call parent constructor to setup form
        parent::__construct($pageTitle);
        
        $this->intern = $i;
        
        $this->agency = $this->intern->getAgency();
        $this->department = $this->intern->getDepartment();

        $this->formVals = array();
        
        // Plug in the passed in Internship object (sets default/selected values)
        // $this->plugInternship();
    }

    /**
     * Loads the form's fields with the internship's information.
     * TODO: Use getter methods instead of just accessing Internship member variables directly.
     */
    public function plugInternship()
    {
        $this->plugStudent();
        $this->plugDept();
        $this->plugFaculty();
        $this->plugAgency();
        $this->plugInternInfo();
        $this->plugCourseInfo();        
                
        // Remove the term dropdown and repalce it
        $this->form->dropElement('term');
        $this->form->addSelect('term', array($this->intern->term => Term::rawToRead($this->intern->term)));
        $this->form->setLabel('term', 'Select Term');
        $this->form->addCssClass('term', 'form-control');
        $this->form->setMatch('term', $this->intern->term);

        $this->form->setMatch('experience_type', $this->intern->getExperienceType());
        
        // Plug
        $this->form->plugIn($this->formVals);
        
        /**
         * *
         * Emergency Contacts
         */
        //javascript('jquery');
        PHPWS_Core::initModClass('intern', 'EmergencyContactFactory.php');
        $contacts = EmergencyContactFactory::getContactsForInternship($this->intern);
        $emgContactJson = json_encode($contacts);
        Layout::add(javascriptMod('intern', 'emergencyContact', array('existing_contacts_json' => $emgContactJson)));
    }

    private function plugStudent()
    {
        // Student
        $this->formVals['student_first_name'] = $this->intern->first_name;
        $this->formVals['student_middle_name'] = $this->intern->middle_name;
        $this->formVals['student_last_name'] = $this->intern->last_name;
        $this->formVals['banner'] = $this->intern->banner;
        $this->formVals['student_phone'] = $this->intern->phone;
        $this->formVals['student_email'] = $this->intern->email;
        $this->formVals['student_level'] = $this->intern->level;
        $this->formVals['grad_prog'] = $this->intern->grad_prog;
        $this->formVals['ugrad_major'] = $this->intern->ugrad_major;
        $this->formVals['student_gpa'] = $this->intern->gpa;
        $this->formVals['campus'] = $this->intern->campus;
        
        // Student address
        $this->formVals['student_address'] = $this->intern->student_address;
        $this->formVals['student_city'] = $this->intern->student_city;
        $this->formVals['student_state'] = $this->intern->student_state;
        $this->formVals['student_zip'] = $this->intern->student_zip;

    }

    private function plugFaculty()
    {
        // Faculty Supervisor
        $facultyId = $this->intern->getFacultyId();
        if (isset($facultyId) && $facultyId != 0) {
            $this->formVals['faculty_id'] = $facultyId;
        }
    } 

    private function plugAgency()
    {
        // Agency
        $this->form->addHidden('agency_id', $this->agency->id);
        $this->formVals['agency_name']            = $this->agency->name;
        $this->formVals['agency_address']         = $this->agency->address;
        $this->formVals['agency_city']            = $this->agency->city;
        $this->formVals['agency_state']           = $this->agency->state;
        $this->formVals['agency_zip']             = $this->agency->zip;
        $this->formVals['agency_province']        = $this->agency->province;
        $this->formVals['agency_country']         = $this->agency->country;
        $this->formVals['agency_phone']           = $this->agency->phone;
        $this->formVals['agency_sup_first_name']  = $this->agency->supervisor_first_name;
        $this->formVals['agency_sup_last_name']   = $this->agency->supervisor_last_name;
        $this->formVals['agency_sup_title']       = $this->agency->supervisor_title;
        $this->formVals['agency_sup_phone']       = $this->agency->supervisor_phone;
        $this->formVals['agency_sup_email']       = $this->agency->supervisor_email;
        $this->formVals['agency_sup_fax']         = $this->agency->supervisor_fax;
        $this->formVals['agency_sup_address']     = $this->agency->supervisor_address;
        $this->formVals['agency_sup_city']        = $this->agency->supervisor_city;
        $this->formVals['agency_sup_state']       = $this->agency->supervisor_state;
        $this->formVals['agency_sup_zip']         = $this->agency->supervisor_zip;
        $this->formVals['agency_sup_province']    = $this->agency->supervisor_province;
        $this->formVals['agency_sup_country']     = $this->agency->supervisor_country;
        $this->formVals['copy_address']           = $this->agency->address_same_flag == 't';
    }

    private function plugInternInfo()
    {
        // Internship
        $this->form->addHidden('internship_id', $this->intern->id);
        $this->formVals['start_date'] = $this->intern->start_date ? date('m/d/Y', $this->intern->start_date) : null;
        $this->formVals['end_date'] = $this->intern->end_date ? date('m/d/Y', $this->intern->end_date) : null;
        $this->formVals['credits'] = $this->intern->credits;
        $this->formVals['avg_hours_week'] = $this->intern->avg_hours_week;
        $this->formVals['loc_address'] = $this->intern->loc_address;
        $this->formVals['loc_city'] = $this->intern->loc_city;
        $this->formVals['loc_state'] = $this->intern->loc_state;
        $this->formVals['loc_zip'] = $this->intern->loc_zip;
        $this->formVals['loc_province'] = $this->intern->loc_province;

        // Other internship details
        if ($this->intern->domestic) {
            $this->form->setMatch('location', 'domestic');
        } else {
            $this->form->setMatch('location', 'internat');
        }
        if ($this->intern->paid) {
            $this->form->setMatch('payment', 'paid');
            $this->form->setMatch('stipend', $this->intern->stipend);
        } else {
            $this->form->setMatch('payment', 'unpaid');
        }
        
        $this->formVals['pay_rate'] = $this->intern->pay_rate;
        
        if ($this->intern->oied_certified) {
            $this->form->setMatch('oied_certified', true);
            $this->form->setValue('oied_certified_hidden', 'true');
        } else {
            $this->form->setValue('oied_certified_hidden', 'false');
        }       $this->formVals['loc_country'] = $this->intern->loc_country;
    }

    private function plugCourseInfo()
    {
        // Course Info
        $this->form->setMatch('course_subj', $this->intern->course_subj);
        $this->formVals['course_no'] = $this->intern->course_no;
        $this->formVals['course_sect'] = $this->intern->course_sect;
        $this->formVals['course_title'] = $this->intern->course_title;
        
        if ($this->intern->isMultipart()) {
            $this->form->setMatch('multipart', '1');
        }
        
        if ($this->intern->isSecondaryPart()) {
            $this->form->setMatch('secondary_part', '1');
        }
        
        $this->formVals['corequisite_course_num'] = $this->intern->getCorequisiteNum();
        $this->formVals['corequisite_course_sect'] = $this->intern->getCorequisiteSection();

    }

    private function plugDept()
    {
        // Department
        $this->formVals['department'] = $this->intern->department_id;
    }
}

?>
