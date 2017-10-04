<?php

namespace Intern\Email;

use \Intern\InternSettings;
use \Intern\Term;
use \Intern\Internship;

class EnrollmentReminderEmail extends Email {

    private $internship;
    private $term;
    private $censusTimestamp;
    private $toUsername;
    private $templateFile;

    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term, $censusTimestamp, $toUsername, $templateFile)
    {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;

        // Double check that we have a valid census timestamp. Try to avoid sending emails with the date set to December 31, 1969
        if($censusTimestamp === 0 || $censusTimestamp === '' || $censusTimestamp === null || !isset($censusTimestamp) || empty($censusTimestamp)){
            throw new \InvalidArgumentException('Census timestamp is 0, null, empty, or not set.');
        }
        $this->censusTimestamp = $censusTimestamp;

        $this->toUsername = $toUsername;
        $this->templateFile = $templateFile;
    }

    protected function getTemplateFileName(){
        // Prepend path to template file name
        return 'email/' . $this->templateFile;
    }

    protected function buildMessage()
    {
        $faculty = $this->internship->getFaculty();
        $agency = $this->internship->getAgency();

        $this->tpl['NAME']    = $this->internship->getFullName();
        $this->tpl['BANNER']  = $this->internship->getBannerId();
        $this->tpl['EMAIL']   = $this->internship->getEmailAddress() . $this->emailSettings->getEmailDomain();
        $this->tpl['TERM']    = $this->term->getDescription();

        if($this->internship->getSubject() !== null && $this->internship->getSubject()->getId() != 0) {
            $this->tpl['SUBJECT'] = $this->internship->getSubject()->getName();
        }

        if(!is_null($this->internship->getCourseNumber())){
            $this->tpl['COURSE_NUM'] = $this->internship->getCourseNumber();
        }

        if(!is_null($this->internship->getCourseSection())){
            $this->tpl['SECTION'] = $this->internship->getCourseSection();
        }

        if(!is_null($this->internship->getCreditHours())){
            $this->tpl['CREDITS'] = $this->internship->getCreditHours();
        }

        if($this->internship->isInternational()){
            $this->tpl['COUNTRY'] = $this->internship->getLocCountry();
        }else{
            $this->tpl['STATE'] = $this->internship->getLocationState();
        }

        if($this->internship->getStartDate() != 0){
            $this->tpl['START_DATE'] = $this->internship->getStartDate();
        }
        if($this->internship->getEndDate() != 0){
            $this->tpl['END_DATE'] = $this->internship->getEndDate();
        }

        if(!is_null($faculty)){
            $this->tpl['FACULTY'] = $faculty->getFullName();
        }

        $this->tpl['AGENCY'] = $agency->getName();
        $this->tpl['CENSUS_DATE'] = date('l, F j, Y', $this->censusTimestamp);

        $this->subject = 'Internship Registration Pending';

        // Append domain name to username
        $this->to[] = $this->toUsername . $this->emailSettings->getEmailDomain();
    }
}
