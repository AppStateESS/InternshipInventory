<?php

namespace Intern\Email;

use Intern\Internship;
use Intern\Department;
use Intern\Term;

class IntlInternshipCreateNoticeStudent extends Email{

    private $internship;

    /**
    * Notifies the student of an international internship.
    *
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $i) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
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
        $this->tpl['TERM'] = Term::rawToRead($this->internship->term);
        $this->tpl['COUNTRY'] = $this->internship->loc_country;

        $dept = new Department($this->internship->department_id);
        $this->tpl['DEPARTMENT'] = $dept->getName();

        $this->to = $this->internship->email . $this->emailSettings->getEmailDomain();

        $this->subject = "International Internship Created - {$this->internship->first_name} {$this->internship->last_name}";
    }
}
