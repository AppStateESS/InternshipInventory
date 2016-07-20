<?php

namespace Intern\Email;

class SendIntlInternshipCreateNoticeStudent extends Email{

  /**
   * Notifies the student of an international internship.
   *
   * @param Internship $i
   */
  public static function __construct(Internship $i)
  {

      $settings = InternSettings::getInstance();

      $tpl = array();

      $tpl['NAME'] = $i->getFullName();
      $tpl['BANNER'] = $i->banner;
      $tpl['USER'] = $i->email;
      $tpl['PHONE'] = $i->phone;

      $tpl['TERM'] = Term::rawToRead($i->term);
      $tpl['COUNTRY'] = $i->loc_country;

      $dept = new Department($i->department_id);
      $tpl['DEPARTMENT'] = $dept->getName();
      $to = $i->email . '@appstate.edu';

      $subject = "International Internship Created -
        {$i->first_name} {$i->last_name}";

      Email::sendTemplateMessage($to, $subject,
        'email/IntStudentInternshipOIEDNotice.tpl', $tpl);
  }
}
