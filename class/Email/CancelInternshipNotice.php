<?php
namespace Intern\Email;

use \Intern\Internship;
use \Intern\Department;
use \Intern\Faculty;
use \Intern\Term;
use \Intern\InternSettings;

/**
 *  Email to notify a student that their internship has been cancelled.
 *
 * @author jbooker
 * @package Intern
 */
class CancelInternshipNotice extends Email {

    private $internship;

    /**
    * Constructor
    *
    * @param InternSettings $emailSettings
    * @param Internship $internship
    */
    public function __construct(InternSettings $emailSettings, Internship $internship) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
    }

    protected function getTemplateFileName()
    {
        return 'email/StudentCancellationNotice.tpl';
    }

    protected function buildMessage()
    {
        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['TERM'] = Term::rawToRead($this->internship->term);

        $dept = new Department($this->internship->department_id);
        $this->tpl['DEPARTMENT'] = $dept->getName();

        $this->to = $this->internship->email . $this->emailSettings->getEmailDomain();

        $faculty = $this->internship->getFaculty();
        if ($faculty instanceof Faculty) {
            $this->cc[] = $faculty->getUsername() . $this->emailSettings->getEmailDomain();
        }

        // CC the graduate school (for grad level) or the registrar's office (for undergrad level)
        if($this->internship->isGraduate()){
            $this->cc[] = explode(',', $this->emailSettings->getGraduateRegEmail()); // NB: Setting is a comma separated array
        } else {
            $this->cc[] = $this->emailSettings->getRegistrarEmail();
        }

        $this->subject = 'Internship Cancelled ' . Term::rawToRead($this->internship->getTerm()) . '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    }
}
