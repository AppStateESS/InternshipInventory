<?php

namespace Intern\Email;

class OIEDReinstate extends Email {
  /**
   *  Sends the  reinstate notification email to OIED.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public static function __construct(Internship $i, Agency $agency)
  {
      $tpl = array();

      $settings = InternSettings::getInstance();

      $tpl = array();
      $tpl['NAME'] = $i->getFullName();
      $tpl['BANNER'] = $i->banner;

      $tpl['TERM'] = Term::rawToRead($i->term, false);

      $countries = \Intern\CountryFactory::getCountries();

      $tpl['COUNTRY'] = $countries[$i->loc_country];

      $to = $settings->getInternationalOfficeEmail();
      $subject = 'International Internship Reinstated';

      email::sendTemplateMessage($to, $subject,
        'email/OIEDReinstate.tpl', $tpl);
  }
}
