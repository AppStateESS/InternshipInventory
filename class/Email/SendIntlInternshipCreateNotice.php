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
     echo("CLASS: SendIntlInternshipCreateNotice");
     $this->sendSpecialMessage($i);
  }

  /**
   * Sets up email as international internship creation notice.
   */
  public function setUpSpecial() {
    $this->tpl['COUNTRY'] = $this->internship->getLocCountry();
    $dept = new Department($this->internship->getDepartmentId());
    $this->tpl['DEPARTMENT'] = $dept->getName();

    $this->to = $this->settings->getInternationalOfficeEmail();
    $this->subject = "International Internship Created - {$this->internship->getFirstName()} {$this->internship->getLastName()}";
    $this->doc = 'email/IntlInternshipCreateNotice.tpl';
  }
}
