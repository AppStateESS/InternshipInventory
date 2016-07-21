<?php

namespace Intern\Email;
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

  public function setUpSpecial() {
    $dept = new Department($this->internship->department_id);
    $this->tpl['DEPARTMENT'] = $dept->getName();

    $this->to = $this->internship->email . '@appstate.edu';

    $faculty = $internship->getFaculty();
    if ($faculty instanceof Faculty) {
      $this->cc = array($faculty->getUsername() . '@' . $this->settings->getEmailDomain(), $this->settings->getRegistrarEmail());
    } else {
      $this->cc = array();
    }

    $this->subject = 'Internship Cancelled ' . $this->term .
      '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();

    $this->doc = 'email/StudentCancellationNotice.tpl';
  }
}
