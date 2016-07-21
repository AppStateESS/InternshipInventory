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
  public static function __construct(Internship $i, Agency $a) {
      echo("CLASS: RegistrationConfirm");
      $this->sanityCheck();
      $this->sendSpecialMessage($i, $a);
  }

  /*
   * Sets up special components of registration confirmation email.
   */
  public function setUpSpecial() {
    $department = $this->internship->getDepartment();
    $this->tpl['DEPT'] = $department->getName();

    $to = $this->internship->email . $settings->getEmailDomain();
    if ($faculty instanceof Faculty) {
        $cc = array($faculty->getUsername() . $settings->getEmailDomain());
    } else {
        $cc = array();
    }
    $this->subject = 'Internship Approved';
    $this->doc = 'email/RegistrationConfirmation.tpl';
  }
}
