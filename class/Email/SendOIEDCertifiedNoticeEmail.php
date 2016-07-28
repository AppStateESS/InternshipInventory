<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;

class SendOIEDCertifiedNoticeEmail extends Email{

  /**
   *  Sends the OIED certification email to the given faclty member
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public function __construct(Internship $i, Agency $a) {
      sendSpecialMessage($i, $a);
  }

  /*
   * Sets up OIED notice email.
   */
  protected function setUpSpecial() {
    $this->tpl['FACULTY'] = $this->faculty->getFullName();
    $this->tpl['AGENCY'] = $this->agency->getName();

    $this->to = $this->faculty->getUsername() . $this->settings->getEmailDomain();
    $this->subject = 'OIED Certified Internship';
    $this->doc = 'email/OiedCertifiedNotice.tpl';
  }
}
