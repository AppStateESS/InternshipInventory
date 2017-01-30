<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use Intern\CountryFactory;

class OIEDCancellationEmail extends Email {

  /**
   * Cancelation email for OIED.
   *
   * @param Internship $i
   * @param Agency $a
   */
  public function __construct(Internship $i, Agency $a) {
    sendSpecialMessage($i, $a);
  }

  /*
   * Sets up OIED cancelation email.
   */
  protected function setUpSpecial() {
    $countries = CountryFactory::getCountries();
    $this->tpl['COUNTRY'] = $countries[$internship->getLocCountry()];

    $this->to = $this->settings->getInternationalOfficeEmail();
    $this->subject = 'International Internship Cancellation';
    $this->doc = 'email/OIEDCancellation.tpl';
  }
}
