<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;

/**
 * Generates an email to the background check coordinator, notifying
 * them to create/setup a background check for this student's internship.
 * Generally used when someone checks the "background check required" field
 * on the Edit Internship interface.
 *
 * @author jbooker
 * @package Intern
 */
class BackgroundCheckEmail extends Email{

    private $internship;
    private $agency;

    private $backgroundCheck;
    private $drugCheck;

    /**
    * Sends the Background or Drug check notification email.
    *
    * @param Internship $i
    * @param Agency $agency
    * @param bool $backgroundCheck
    * @param bool $drugCheck
    */
    public function __construct(InternSettings $emailSettings, Internship $internship, Agency $agency, $backgroundCheck, $drugCheck) {
        parent::__construct($emailSettings);

        $this->internship = $internship;
        $this->agency = $agency;
    }

    protected function getTemplateFileName()
    {
        return 'email/BackgroundDrugCheck.tpl';
    }

    protected function buildMessage()
    {
        $this->to = $this->settings->getBackgroundCheckEmail();

        $this->tpl['NAME'] = $this->internship->getFullName();
        $this->tpl['BANNER'] = $this->internship->banner;
        $this->tpl['TERM'] = Term::rawToRead($this->internship->getTerm());
        $this->tpl['BIRTHDAY'] = $this->internship->getBirthDateFormatted();
        $this->tpl['EMAIL'] = $this->internship->getEmailAddress() . $this->emailSettings->getEmailDomain();
        $this->tpl['AGENCY'] = $this->agency->getName();

        if ($this->backgroundCheck === true && $this->drugCheck === true) {
            $this->subject = 'Internship Background & Drug Check Needed ' . $this->internship->getFullName();
            $this->tpl['CHECK'] = 'Background & Drug';
        } else if ($backgroundCheck === true) {
            $this->subject = 'Internship Background Check Needed ' . $this->internship->getFullName();
            $this->tpl['CHECK'] = 'Background';
        } else if ($drugCheck === true) {
            $this->subject = 'Internship Drug Test Needed ' . $this->internship->getFullName();
            $this->tpl['CHECK'] = 'Drug';
        } else {
            // Both variables were false, what are we doing here?
            throw new \InvalidArgumentException( 'Both background check and drug test varaibles were false. One or both must be set to true.');
        }
    }
}
