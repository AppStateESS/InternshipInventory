<?php

namespace Intern\Email;

use \Intern\InternSettings;
use \Intern\Internship;
use \Intern\Subject;
use \Intern\Term;

/**
 * Responsible for the details of an email to an Internship administrator when
 * the internship course number is not in the list of normal internship courses
 *
 * @author jbooker
 * @package Intern\Email
 */
class UnusualCourseEmail extends Email {

    private $internship;
    private $term;

    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term)
    {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
    }

    protected function getTemplateFileName(){
        return 'email/UnusualCourseRegistration.tpl';
    }

    protected function buildMessage()
    {
        $subjects = Subject::getSubjects();

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->getBannerId();
        $this->tpl['USER'] = $this->internship->getEmailAddress();
        $this->tpl['PHONE'] = $this->internship->getPhoneNumber();

        $term = $this->term->getDescription();

        $this->tpl['TERM'] = $term;

        $subj = $subjects[$this->internship->getSubject()->getId()];
        $courseNum = $this->internship->getCourseNumber();
        $bannerId = $this->internship->getBannerId();

        $this->tpl['SUBJECT'] = $subj;
        $this->tpl['COURSE_NUM'] = $courseNum;

        if($this->internship->getCourseTitle() !== null){
            $this->tpl['COURSE_TITLE'] = $this->internship->getCourseTitle();
        }

        $this->to[] = $this->emailSettings->getUnusualCourseEmail();

        $this->subject = "Unusual internship course: $subj $courseNum for $bannerId";
    }
}
