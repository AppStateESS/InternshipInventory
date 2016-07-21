<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Department;

class SendIntlInternshipCreateNoticeStudent extends Email{

  /**
   * Notifies the student of an international internship.
   *
   * @param Internship $i
   */
  public function __construct(Internship $i) {
    echo("CLASS: SendIntlInternshipCreateNoticeStudent");
    sendSpecialMessage($i);
  }

  public function setUpSpecial() {
    $this->tpl['COUNTRY'] = $this->internship->loc_country;

    $dept = new Department($this->internship->department_id);
    $this->tpl['DEPARTMENT'] = $dept->getName();
    $this->to = $this->internship->email . '@appstate.edu';

    $subject = "International Internship Created -
      {$this->internship->first_name} {$this->internship->last_name}";
    $this->doc = 'email/IntStudentInternshipOIEDNotice.tpl';
  }
}
