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

  public function setUpSpecial() {
    $this->tpl['COUNTRY'] = $this->internship->loc_country;

    $dept = new Department($this->internship->department_id);
    $this->tpl['DEPARTMENT'] = $dept->getName();
    $this->to = $this->settings->getInternationalOfficeEmail();

    $this->subject = "International Internship Created - {$this->internship->first_name} {$this->internship->last_name}";
    $this->doc = 'email/IntlInternshipCreateNotice.tpl';
  }
}
