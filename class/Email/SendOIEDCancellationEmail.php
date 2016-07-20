<?php

namespace Intern\Email;

class OIEDCancellation extends Email {

  /**
   * Sends an email to the registrar notifying them to register
   * the student for the appropriate internship course.
   *
   * @param Internship $i
   * @param Agency $a
   */
  public static function __construct(Internship $i, Agency $a) {
        $tpl = array();

        $settings = InternSettings::getInstance();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;

        $tpl['TERM'] = Term::rawToRead($i->term, false);

        $countries = \Intern\CountryFactory::getCountries();

        $tpl['COUNTRY'] = $countries[$i->loc_country];

        $to = $settings->getInternationalOfficeEmail();
        $subject = 'International Internship Cancellation';

        email::sendTemplateMessage($to, $subject, 'email/OIEDCancellation.tpl', $tpl);
    }
}
