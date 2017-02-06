<?php
namespace Intern\Email;

use Intern\Internship;
use Intern\Term;
use Intern\Subject;
use Intern\InternSettings;

class OIEDCertifiedEmail extends Email{

    private $internship;

    /**
    *  Sends the OIED certification email to the given faclty member
    *
    * @param Internship $i
    */
    public function __construct(InternSettings $emailSettings, Internship $internsip) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
    }

    protected function getTemplateFileName() {
        return 'email/OiedCertifiedNotice.tpl';
    }

    protected function buildMessage()
    {
        $subjects = Subject::getSubjects();

        $faculty = $this->inernship->getFaculty();

        $this->tpl['NAME'] = $this->inernship->getFullName();
        $this->tpl['BANNER'] = $this->inernship->getBannerId();
        $this->tpl['TERM'] = Term::rawToRead($this->inernship->getTerm(), false);
        $this->tpl['FACULTY'] = $faculty->getFullName();
        $this->tpl['AGENCY'] = $this->agency->getName();

        $this->to = $faculty->getUsername() . $this->emailSettings->getEmailDomain();

        $this->subject = 'OIED Certified Internship';
    }
}
