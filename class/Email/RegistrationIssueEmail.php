<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Faculty;
use Intern\Subject;

class RegistrationIssueEmail extends Email {

    private $internship;

    /**
    *  Sends the 'Registration Issue' notification email.
    *
    * @param Internship $i
    * @param string $note
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, $note) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
    }

    protected function getTemplateFileName(){
        return 'email/RegistrationIssue.tpl';
    }

    protected function buildMessage()
    {
        $this->subject = 'Internship Enrollment Issue';
        $this->to = $this->internship->email . $this->emailSettings->getEmailDomain();

        $subjects = Subject::getSubjects();

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['USER'] = $this->internship->email;
        $this->tpl['PHONE'] = $this->internship->phone;
        $this->tpl['TERM'] = Term::rawToRead($this->internship->term, false);

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
            $this->tpl['FACULTY'] = $faculty->getFullName();

            // CC the Faculty member
            $this->cc = array($faculty->getUsername() . $this->emailSettings->getEmailDomain());
        }else{
            $this->tpl['FACULTY'] = '(not provided)';
        }

        $department = $this->internship->getDepartment();
        $this->tpl['DEPT'] = $department->getName();

        if($this->internship->international){
            $this->tpl['COUNTRY'] = $this->internship->loc_country;
            $this->tpl['INTERNATIONAL'] = 'Yes';
            $intlSubject = '[int\'l] ';
        }else{
            $this->tpl['STATE'] = $this->internship->loc_state;
            $this->tpl['INTERNATIONAL'] = 'No';
            $intlSubject = '';
        }

        $this->tpl['NOTE'] = $note;
    }
}
