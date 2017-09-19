<?php
namespace Intern\Email;

use \Intern\InternSettings;
use \Intern\Internship;
use \Intern\Department;
use \Intern\Term;

/**
 * Email to notify the Internal Ed Office that a new international internship
 * has been created, so they can be expecting contact from the student.
 *
 * @author jbooker
 * @package Intern
 */
class IntlInternshipCreateNotice extends Email {

    private $internship;
    private $term;

    /**
     * @param InternSettings $emailSettings
     * @param Internship $internship
     */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
    }

    protected function getTemplateFileName(){
        return 'email/IntlInternshipCreateNotice.tpl';
    }

    protected function buildMessage()
    {
        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['USER'] = $this->internship->email;
        $this->tpl['PHONE'] = $this->internship->phone;
        $this->tpl['TERM'] = $this->term->getDescription();
        $this->tpl['COUNTRY'] = $this->internship->loc_country;

        $dept = new Department($this->internship->department_id);
        $this->tpl['DEPARTMENT'] = $dept->getName();

        $this->to = $this->emailSettings->getInternationalOfficeEmail();

        $this->subject = "International Internship Created - {$this->internship->first_name} {$this->internship->last_name}";
    }
}
