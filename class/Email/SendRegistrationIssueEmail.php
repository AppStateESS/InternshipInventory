<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use Intern\Faculty;

class SendRegistrationIssueEmail extends Email {

  /**
   *  Sends the 'Registration Issue' notification email.
   *
   * @param Internship $i
   * @param Agency $agency
   * @param string $note
   */
  public function __construct(Internship $i, Agency $agency, $note) {
      $this->sendSpecialMessage($i, $agency, $note);
  }

  /*
   * Sets up special components of registration issue email.
   */
  protected function setUpSpecial() {
    $this->sanityCheck();

    $this->tpl['DEPT'] = $this->internship->getDepartment()->getName();
    $this->tpl['NOTE'] = $this->note;

    if ($this->faculty instanceof Faculty) {
        $this->cc = array($this->faculty->getUsername() . $this->settings->getEmailDomain());
    } else {
        $this->cc = array();
    }
    $this->subject = 'Internship Enrollment Issue';
    $this->to = $this->internship->email . $this->settings->getEmailDomain();
    $this->doc = 'email/RegistrationIssue.tpl';
  }
}
