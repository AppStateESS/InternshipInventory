<?php
namespace Intern\Email;

use Intern\Internship;
use Intern\Faculty;

/**
 * Class to handle creating an email to the registrar notifying them to register
 * the student for the appropriate internship course.
 * @author jbooker
 * @package Intern
 */
class ReadyToRegisterEmail extends Email {

    private $internship;

    /**
     * Constructor
     * @param Internship $i
     * @param Agency $a
     */
    public function __construct(InternSettings $emailSettings, Internship $internship) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
    }

    protected function getTemplateFileName(){
        return 'email/ReadyToRegisterEmail.tpl';
    }

    protected function buildMessage()
    {
        $subjects = Subject::getSubjects();

        $faculty = $this->internship->getFaculty();

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['USER'] = $this->internship->email;
        $this->tpl['PHONE'] = $this->internship->phone;

        $this->term = Term::rawToRead($this->internship->term, false);
        $this->tpl['TERM'] = $term;

        if(isset($this->internship->course_subj)){
            $this->tpl['SUBJECT'] = $subjects[$this->internship->course_subj];
        }else{
            $this->tpl['SUBJECT'] = '(No course subject provided)';
        }

        $this->tpl['COURSE_NUM'] = $this->internship->course_no;
        if(isset($this->internship->course_sect)){
            $this->tpl['SECTION'] = $this->internship->course_sect;
        }else{
            $this->tpl['SECTION'] = '(not provided)';
        }

        if(isset($this->internship->course_title)){
            $this->tpl['COURSE_TITLE'] = $this->internship->course_title;
        }

        if(isset($this->internship->credits)){
            $this->tpl['CREDITS'] = $this->internship->credits;
        }else{
            $this->tpl['CREDITS'] = '(not provided)';
        }

        $startDate = $this->internship->getStartDate(true);
        if(isset($startDate)){
            $this->tpl['START_DATE'] = $startDate;
        }else{
            $this->tpl['START_DATE'] = '(not provided)';
        }

        $endDate = $this->internship->getEndDate(true);
        if(isset($endDate)){
            $this->tpl['END_DATE'] = $endDate;
        }else{
            $this->tpl['END_DATE'] = '(not provided)';
        }

        if($faculty instanceof Faculty){
            $faculty = $this->internship->getFaculty();
            $this->tpl['FACULTY'] = $faculty->getFullName() . ' (' . $faculty->getId() . ')';
        }else{
            $this->tpl['FACULTY'] = '(not provided)';
        }

        $department = $this->internship->getDepartment();
        $this->tpl['DEPT'] = $department->getName();

        $campus = $this->internship->getCampus();
        if ($campus == 'distance_ed') {
            $this->tpl['CAMPUS'] = 'Distance Ed';
        } else if ($campus == 'main_campus') {
            $this->tpl['CAMPUS'] = 'Main campus';
        } else {
            $this->tpl['CAMPUS'] = $campus;
        }

        /**** Corequisite Checking ****/
        $coreq = $this->internship->getCorequisiteNum();
        if (!is_null($coreq) && $coreq != '') {
            $this->tpl['COREQ_SUBJECT'] = $subjects[$this->internship->course_subj];
            $this->tpl['COREQ_COURSE_NUM'] = $coreq;
            $this->tpl['COREQ_COURSE_SECT'] = $this->internship->getCorequisiteSection();
        }

        /**** International Checking ***/
        if ($this->internship->international) {
            $this->tpl['COUNTRY'] = $this->internship->loc_country;
            $this->tpl['INTERNATIONAL'] = 'Yes';
            $intlSubject = '[int\'l] ';
        } else {
            $this->tpl['STATE'] = $this->internship->loc_state;
            $this->tpl['INTERNATIONAL'] = 'No';
            $intlSubject = '';
        }

        /**** Multi-part checking ***/
        if ($this->internship->isMultipart() && $this->internship->isSecondaryPart()) {
            $this->tpl['SECONDARY_PART'] = '';
        }

        /***
        * Figure out who the notification email goes to
        */
        if ($this->internship->isDistanceEd()) {
            // Send distance ed internship to Distance Ed Office
            $this->to = $this->settings->getDistanceEdEmail();
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        } else if ($this->internship->isGraduate()) {
            // Send all graduate internships to the graduate school (whether international or not)
            $this->to = explode(',', $this->settings->getGraduateRegEmail()); // NB: Setting is a comma separated array
            $this->tpl['GRADUATE'] = ''; // Dummy template var to use grad school text
        } else if ($this->internship->isInternational()){
            // Send international undergraduate internships to a special person
            $this->to = $this->settings->getInternationalRegEmail();
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        } else {
            // Otherwise, send it to the general Registrar address
            $this->to = $this->settings->getRegistrarEmail();
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        }

        // Double check that we set a To address
        if(!isset($this->to) || $this->to == null) {
            throw new \InvalidArgumentException('Missing configurating for email addresses (registrar)');
        }

        // CC the faculty members
        if ($faculty instanceof Faculty) {
            $this->cc = array($faculty->getUsername() . $this->settings->getEmailDomain());
        }

        // Subject line
        $this->subject = $term . ' ' . $intlSubject . '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    }
}
