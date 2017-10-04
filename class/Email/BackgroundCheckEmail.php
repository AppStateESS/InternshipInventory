<?php

namespace Intern\Email;
use \Intern\Internship;
use \Intern\Agency;
use \Intern\InternSettings;
use \Intern\Term;
use \Intern\TermFactory;

/**
 * Generates an email to the background check coordinator, notifying
 * them to create/setup a background check and/or drug test for this student's internship.
 * Generally used when someone checks the "background check required" or "drug test needed"
 * fields on the Edit Internship interface.
 *
 * @author jbooker
 * @package Intern
 */
class BackgroundCheckEmail extends Email{

    private $internship;
    private $term;
    private $agency;

    private $backgroundCheck;
    private $drugCheck;

    /**
    * Sends the Background or Drug check notification email.
    *
    * @param InternSettings $emailSettings
    * @param Internship $internship
    * @param Term $term
    * @param Agency $agency
    * @param bool $backgroundCheck
    * @param bool $drugCheck
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, Term $term, Agency $agency, $backgroundCheck, $drugCheck) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->term = $term;
        $this->agency = $agency;
        $this->backgroundCheck = $backgroundCheck;
        $this->drugCheck = $drugCheck;
    }

    protected function getTemplateFileName() {
        return 'email/BackgroundDrugCheck.tpl';
    }

    protected function buildMessage()
    {
        $this->to = explode(',', $this->emailSettings->getBackgroundCheckEmail());

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['TERM'] = $this->term->getDescription();
        $this->tpl['LEVEL'] = $this->internship->getLevel();
        $this->tpl['BIRTHDAY'] = $this->internship->getBirthDateFormatted();
        $this->tpl['EMAIL'] = $this->internship->getEmailAddress() . $this->emailSettings->getEmailDomain();
        $this->tpl['AGENCY'] = $this->agency->getName();

        if ($this->internship->getFaculty() !== null) {
            $this->tpl['FACULTY'] = $this->internship->getFaculty();
        } else {
            $this->tpl['FACULTY'] = 'Faculty supervisor not set.';
        }

        if ($this->backgroundCheck === true && $this->drugCheck === true) {
            $this->subject = 'Internship Background & Drug Check Needed for ' . $this->internship->getFullName();
            $this->tpl['CHECK'] = 'Background & Drug';
        } else if ($this->backgroundCheck === true) {
            $this->subject = 'Internship Background Check Needed for ' . $this->internship->getFullName();
            $this->tpl['CHECK'] = 'Background';
        } else if ($this->drugCheck === true) {
            $this->subject = 'Internship Drug Test Needed for ' . $this->internship->getFullName();
            $this->tpl['CHECK'] = 'Drug';
        } else {
            // Both variables were false, what are we doing here?
            throw new \InvalidArgumentException( 'Both background check and drug test varaibles were false. One or both must be set to true.');
        }
    }
}
