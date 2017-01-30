<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use Intern\Faculty;

class SendRegistrationConfirmationEmail extends Email{

  /**
   * Sends the 'Registration Confirmation' email.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public function __construct(Internship $i, Agency $a) {
      $this->sendSpecialMessage($i, $a);
  }

  /*
   * Sets up special components of registration confirmation email.
   */
  protected function setUpSpecial() {
    $this->sanityCheck();

    $this->tpl['DEPT'] = $this->internship->getDepartment()->getName();

    $this->to = $this->internship->email . $this->settings->getEmailDomain();
    if ($this->faculty instanceof Faculty) {
        $this->cc = array($this->faculty->getUsername() . $this->settings->getEmailDomain());
    } else {
        $this->cc = array();
    }
    $this->subject = 'Internship Approved';
    $this->doc = 'email/RegistrationConfirmation.tpl';
  }
}
