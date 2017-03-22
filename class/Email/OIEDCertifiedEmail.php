<?php
namespace Intern\Email;

use \Intern\Internship;
use \Intern\Term;
use \Intern\InternSettings;

/**
 * Email notification that an internship has be certified by the international
 * Education Office. Generally sent to the faculty instructor, if one is available
 *
 * @author jbooker
 * @package Intern
 */
class OIEDCertifiedEmail extends Email{

    private $internship;
    private $agency;

    /**
    * @param InternSettings $emailSettings
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->agency = $internship->getAgency();
    }

    protected function getTemplateFileName() {
        return 'email/OiedCertifiedNotice.tpl';
    }

    protected function buildMessage()
    {
        $faculty = $this->internship->getFaculty();

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->getBannerId();
        $this->tpl['TERM'] = Term::rawToRead($this->internship->getTerm(), false);
        $this->tpl['FACULTY'] = $faculty->getFullName();
        $this->tpl['AGENCY'] = $this->agency->getName();

        $this->to = $faculty->getUsername() . $this->emailSettings->getEmailDomain();

        $this->subject = 'OIED Certified Internship';
    }
}
