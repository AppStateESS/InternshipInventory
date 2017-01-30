<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use Intern\CountryFactory;

class SendOIEDReinstateEmail extends Email {
  /**
   *  Sends the  reinstate notification email to OIED.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public function __construct(Internship $i, Agency $a) {
      sendSpecialMessage($i, $a);
  }

  protected function setUpSpecial()
  {
    $countries = CountryFactory::getCountries();
    $this->tpl['COUNTRY'] = $countries[$this->internship->getLocCountry()];

    $this->to = $this->settings->getInternationalOfficeEmail();
    $this->subject = 'International Internship Reinstated';
    $this->doc = 'email/OIEDReinstate.tpl';
  }
}
