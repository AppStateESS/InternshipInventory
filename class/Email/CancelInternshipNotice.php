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

        // Email the distance ed, graduate school (for grad level), international, or the registrar's office (for undergrad level)
        if($this->internship->isDistanceEd()){
            $this->to[] = $this->emailSettings->getDistanceEdEmail();
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        } else if($this->internship->isGraduate()){
            $this->to[] = explode(',', $this->emailSettings->getGraduateRegEmail()); // NB: Setting is a comma separated array
            $this->tpl['GRADUATE'] = ''; // Dummy template var to use grad school text
        } else if($this->internship->isInternational()){
            $this->to[] = $this->emailSettings->getInternationalRegEmail();
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        } else {
            $this->to[] = $this->emailSettings->getRegistrarEmail();
            $this->tpl['UNDERGRAD'] = ''; // Dummy template var to use undergrad text
        }

        //CC student
        $this->cc[] = $this->internship->email . $this->emailSettings->getEmailDomain();

        //CC faculty members
        $faculty = $this->internship->getFaculty();
        if ($faculty instanceof Faculty) {
            $this->cc[] = ($faculty->getUsername() . $this->emailSettings->getEmailDomain());
        }

        $this->subject = 'Internship Cancelled ' . Term::rawToRead($this->internship->getTerm()) . '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    }
}
