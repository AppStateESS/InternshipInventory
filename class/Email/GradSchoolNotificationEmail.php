<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Subject;
use Intern\Term;
use Intern\Faculty;

class GradSchoolNotificationEmail extends Email {

    private $internship;

    /**
    * Sends an email to the grad school office, letting them know there's someone to notify
    *
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
    }

    protected function getTemplateFileName()
    {
        return 'email/GradSchoolNotification.tpl';
    }

    protected function buildMessage()
    {
        $subjects = Subject::getSubjects();

        $faculty = $this->internship->getFaculty();

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
            $advisor = $this->internship->getFaculty();
            $this->tpl['FACULTY'] = $advisor->getFullName();
        }else{
            $this->tpl['FACULTY'] = '(not provided)';
        }

        $this->tpl['DEPT'] = $this->internship->getDepartment()->getName();

        $campus = $this->internship->getCampus();
        if($campus == 'distance_ed'){
            $this->tpl['CAMPUS'] = 'Distance Ed';
        }else if($campus == 'main_campus'){
            $this->tpl['CAMPUS'] = 'Main campus';
        }else{
            $this->tpl['CAMPUS'] = $campus;
        }

        if($this->internship->international){
            $this->tpl['COUNTRY'] = $this->internship->loc_country;
            $this->tpl['INTERNATIONAL'] = 'Yes';
            $intlSubject = '[int\'l] ';
        }else{
            $this->tpl['STATE'] = $this->internship->loc_state;
            $this->tpl['INTERNATIONAL'] = 'No';
            $intlSubject = '';
        }

        $emails = $this->emailSettings->getGradSchoolEmail();
        $this->to = explode(',', $emails);
        $this->subject = 'Internship Approval Needed: ' . $intlSubject . '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    }
}
