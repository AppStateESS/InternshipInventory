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
        
        // Plug in the passed in Internship object (sets default/selected values)
        // $this->plugInternship();
    }

    /**
     * Loads the form's fields with the internship's information.
     * TODO: Use getter methods instead of just accessing Internship member variables directly.
     */
    public function plugInternship()
    {
        $vals = array();
        
        // Student
        $vals['student_first_name'] = $this->intern->first_name;
        $vals['student_middle_name'] = $this->intern->middle_name;
        $vals['student_last_name'] = $this->intern->last_name;
        $vals['banner'] = $this->intern->banner;
        $vals['student_phone'] = $this->intern->phone;
        $vals['student_email'] = $this->intern->email;
        $vals['student_level'] = $this->intern->level;
        $vals['grad_prog'] = $this->intern->grad_prog;
        $vals['ugrad_major'] = $this->intern->ugrad_major;
        $vals['student_gpa'] = $this->intern->gpa;
        $vals['campus'] = $this->intern->campus;
        
        // Student address
        $vals['student_address'] = $this->intern->student_address;
        $vals['student_city'] = $this->intern->student_city;
        $vals['student_state'] = $this->intern->student_state;
        $vals['student_zip'] = $this->intern->student_zip;
        
        // Faculty Supervisor
        $facultyId = $this->intern->getFacultyId();
        if (isset($facultyId) && $facultyId != 0) {
            $vals['faculty_id'] = $facultyId;
        }
        
        // Agency
        $this->form->addHidden('agency_id', $this->agency->id);
        $vals['agency_name'] = $this->agency->name;
        $vals['agency_address'] = $this->agency->address;
        $vals['agency_city'] = $this->agency->city;
        $vals['agency_state'] = $this->agency->state;
        $vals['agency_zip'] = $this->agency->zip;
        $vals['agency_country'] = $this->agency->country;
        $vals['agency_phone'] = $this->agency->phone;
        $vals['agency_sup_first_name'] = $this->agency->supervisor_first_name;
        $vals['agency_sup_last_name'] = $this->agency->supervisor_last_name;
        $vals['agency_sup_title'] = $this->agency->supervisor_title;
        $vals['agency_sup_phone'] = $this->agency->supervisor_phone;
        $vals['agency_sup_email'] = $this->agency->supervisor_email;
        $vals['agency_sup_fax'] = $this->agency->supervisor_fax;
        $vals['agency_sup_address'] = $this->agency->supervisor_address;
        $vals['agency_sup_city'] = $this->agency->supervisor_city;
        $vals['agency_sup_state'] = $this->agency->supervisor_state;
        $vals['agency_sup_zip'] = $this->agency->supervisor_zip;
        $vals['agency_sup_country'] = $this->agency->supervisor_country;
        $vals['copy_address'] = $this->agency->address_same_flag == 't';
        
        // Internship
        $this->form->addHidden('internship_id', $this->intern->id);
        $vals['start_date'] = $this->intern->start_date ? date('m/d/Y', $this->intern->start_date) : null;
        $vals['end_date'] = $this->intern->end_date ? date('m/d/Y', $this->intern->end_date) : null;
        $vals['credits'] = $this->intern->credits;
        $vals['avg_hours_week'] = $this->intern->avg_hours_week;
        $vals['loc_address'] = $this->intern->loc_address;
        $vals['loc_city'] = $this->intern->loc_city;
        $vals['loc_state'] = $this->intern->loc_state;
        $vals['loc_zip'] = $this->intern->loc_zip;
        $vals['loc_province'] = $this->intern->loc_province;
        $vals['loc_country'] = $this->intern->loc_country;
        
        // Course Info
        $this->form->setMatch('course_subj', $this->intern->course_subj);
        $vals['course_no'] = $this->intern->course_no;
        $vals['course_sect'] = $this->intern->course_sect;
        $vals['course_title'] = $this->intern->course_title;
        
        if ($this->intern->isMultipart()) {
            $this->form->setMatch('multipart', '1');
        }
        
        if ($this->intern->isSecondaryPart()) {
            $this->form->setMatch('secondary_part', '1');
        }
        
        $vals['corequisite_course_num'] = $this->intern->getCorequisiteNum();
        $vals['corequisite_course_sect'] = $this->intern->getCorequisiteSection();
        
        // Department
        $vals['department'] = $this->intern->department_id;
        
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
        
        $vals['pay_rate'] = $this->intern->pay_rate;
        
        if ($this->intern->oied_certified) {
            $this->form->setMatch('oied_certified', true);
            $this->form->setValue('oied_certified_hidden', 'true');
        } else {
            $this->form->setValue('oied_certified_hidden', 'false');
        }
        
        $this->form->setMatch('term', $this->intern->term);
        $this->form->setMatch('experience_type', $this->intern->getExperienceType());
        
        // Plug
        $this->form->plugIn($vals);
        
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
}

?>
