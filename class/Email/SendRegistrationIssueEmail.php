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
  public static function __construct(Internship $i, Agency $agency, $note) {
      echo("CLASS: RegistrationConfirm");
      $this->sanityCheck();
      $this->sendSpecialMessage($i, $agency, $note);
  }

  /*
   * Sets up special components of registration issue email.
   */
  public function setUpSpecial() {
    $department = $this->internship->getDepartment();
    $this->tpl['DEPT'] = $department->getName();
    $this->tpl['NOTE'] = $this->note;

    if ($faculty instanceof Faculty) {
        $cc = array($faculty->getUsername() . $settings->getEmailDomain());
    } else {
        $cc = array();
    }
    $subject = 'Internship Enrollment Issue';
    $to = $i->email . $settings->getEmailDomain();
    $doc = 'email/RegistrationIssue.tpl';
  }
}
