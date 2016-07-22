<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Department;
use Intern\Faculty;
use Intern\Term;

class SendInternshipCancelNotice extends Email {

  /**
  * Notifies of internship cancelation.
  *
  * @param Internship $i
  */
  public function __construct(Internship $i) {
    echo("CLASS: SendInternshipCancelNotice");
    $this->sendSpecialMessage($i);
  }

  /**
   * Sets up email as a cancelation notice.
   */
  public function setupSpecial() {
    $dept = new Department($this->internship->getDepartmentId());
    $this->tpl['DEPARTMENT'] = $dept->getName();

    $this->to = $this->internship->getEmailAddress() . '@appstate.edu';
    if ($this->faculty instanceof Faculty) {
      $this->cc = array($this->faculty->getUsername() . '@' . $this->settings->getEmailDomain(), $this->settings->getRegistrarEmail());
    } else {
      $this->cc = array();
    }
    $this->subject = 'Internship Cancelled ' . $this->term .
      '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    $this->doc = 'email/StudentCancellationNotice.tpl';
  }
}
