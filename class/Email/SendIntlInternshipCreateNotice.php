<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Department;

class SendIntlInternshipCreateNotice extends Email {

  /**
   * Notifies of international internship.
   *
   * @param Internship $i
   */
  public function __construct(Internship $i) {
     $this->sendSpecialMessage($i);
  }

  /**
   * Sets up email as international internship creation notice.
   */
  protected function setUpSpecial() {
    $this->tpl['COUNTRY'] = $this->internship->getLocCountry();
    $dept = new Department($this->internship->getDepartmentId());
    $this->tpl['DEPARTMENT'] = $dept->getName();

    $this->to = $this->settings->getInternationalOfficeEmail();
    $fname = $this->internship->getFirstName();
    $lname = $this->internship->getLastName();
    $this->subject = "International Internship Created - {$fname} {$lname}";
    $this->doc = 'email/IntlInternshipCreateNotice.tpl';
  }
}
