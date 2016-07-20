<?php

namespace Intern\Email;

class RegistrationConfirm extends Email{

  /**
   *  Sends the OIED certification email to the given faclty member
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public static function __construct(Internship $i, Agency $agency)
  {
      $tpl = array();

      $subjects = Subject::getSubjects();

      $settings = InternSettings::getInstance();

      $faculty = $i->getFaculty();

      $tpl = array();
      $tpl['NAME'] = $i->getFullName();
      $tpl['BANNER'] = $i->getBannerId();
      $tpl['TERM'] = Term::rawToRead($i->getTerm(), false);
      $tpl['FACULTY'] = $faculty->getFullName();
      $tpl['AGENCY'] = $agency->getName();


      $to = $faculty->getUsername() . $settings->getEmailDomain();

      $subject = 'OIED Certified Internship';

      email::sendTemplateMessage($to, $subject, 'email/OiedCertifiedNotice.tpl', $tpl);
  }
}
