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
    $this->sendSpecialMessage($i);
  }

  /**
   * Sets up email as international internship creation notice for student.
   */
  protected function setUpSpecial() {
    $this->tpl['COUNTRY'] = $this->internship->getLocCountry();
    $dept = new Department($this->internship->getDepartmentId());
    $this->tpl['DEPARTMENT'] = $dept->getName();

    $this->to = $this->internship->getEmailAddress() . '@appstate.edu';
    $this->subject = "International Internship Created -
      {$this->internship->getFirstName()} {$this->internship->getLastName()}";
    $this->doc = 'email/IntStudentInternshipOIEDNotice.tpl';
  }
}
