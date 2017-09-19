<?php

namespace Intern\Email;

use \Intern\Internship;
use \Intern\Department;
use \Intern\Term;
use \Intern\InternSettings;

/**
 * Notifies the student that an International internship has been created for them,
 * and that they'll need to contact the International Ed Office to complete extra requirements/paperwork.
 *
 * @author jbooker
 * @package Intern
 */
class IntlInternshipCreateNoticeStudent extends Email {

    private $internship;
    private $term;

    /**
     * Constructor
     *
     * @param InternSettings $emailSettings
     * @param Internship $internship
     */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
    }

    protected function getTemplateFileName() {
        return 'email/IntStudentInternshipOIEDNotice.tpl';
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

        $this->to = $this->internship->email . $this->emailSettings->getEmailDomain();

        $this->subject = "International Internship Created - {$this->internship->first_name} {$this->internship->last_name}";
    }
}
