<?php
namespace Intern\Email;

use Intern\Internship;
use Intern\Agency;
use Intern\Faculty;

use Intern\Subject;
use Intern\Term;

class RegistrationConfirmationEmail extends Email {

    private $internship;

    /**
    * Sends the 'Registration Confirmation' email.
    *
    * @param Internship $i
    */
    public function __construct(InternSettings $emailSettings, Internship $internship) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
    }

    protected function getTemplateFileName(){
        return 'email/RegistrationConfirmation.tpl';
    }

    protected function buildMessage()
    {
        $subjects = Subject::getSubjects();
        $faculty = $this->internship->getFaculty();

        $tpl['NAME'] = $this->internship->getFullName();
        $tpl['BANNER'] = $this->internship->banner;
        $tpl['USER'] = $this->internship->email;
        $tpl['PHONE'] = $this->internship->phone;
        $tpl['TERM'] = Term::rawToRead($this->internship->term, false);

        if(isset($this->internship->course_subj)){
            $tpl['SUBJECT'] = $subjects[$this->internship->course_subj];
        }else{
            $tpl['SUBJECT'] = '(No course subject provided)';
        }

        $tpl['COURSE_NUM'] = $this->internship->course_no;

        if(isset($this->internship->course_sect)){
            $tpl['SECTION'] = $this->internship->course_sect;
        }else{
            $tpl['SECTION'] = '(not provided)';
        }

        if(isset($this->internship->course_title)){
            $tpl['COURSE_TITLE'] = $this->internship->course_title;
        }

        if(isset($this->internship->credits)){
            $tpl['CREDITS'] = $this->internship->credits;
        }else{
            $tpl['CREDITS'] = '(not provided)';
        }

        $startDate = $this->internship->getStartDate(true);
        if(isset($startDate)){
            $tpl['START_DATE'] = $startDate;
        }else{
            $tpl['START_DATE'] = '(not provided)';
        }

        $endDate = $this->internship->getEndDate(true);
        if(isset($endDate)){
            $tpl['END_DATE'] = $endDate;
        }else{
            $tpl['END_DATE'] = '(not provided)';
        }

        if($faculty instanceof Faculty){
            $tpl['FACULTY'] = $faculty->getFullName();
        }else{
            $tpl['FACULTY'] = '(not provided)';
        }

        $department = $this->internship->getDepartment();
        $tpl['DEPT'] = $department->getName();
        if($this->internship->international){
            $tpl['COUNTRY'] = $this->internship->loc_country;
            $tpl['INTERNATIONAL'] = 'Yes';
            $intlSubject = '[int\'l] ';
        }else{
            $tpl['STATE'] = $this->internship->loc_state;
            $tpl['INTERNATIONAL'] = 'No';
            $intlSubject = '';
        }
        $to = $this->internship->email . $this->emailSettings->getEmailDomain();
        if ($faculty instanceof Faculty) {
            $cc = array($faculty->getUsername() . $this->emailSettings->getEmailDomain());
        } else {
            $cc = array();
        }

        $subject = 'Internship Registration Confirmation';
    }
}
