<?php

namespace Intern\Email;

class BackgroundCheck extends Email{

  /**
   *  Sends the Background or Drug check notification email.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public static function sendEmail(Internship $i, Agency $agency, $backgroundCheck, $drugCheck)
  {
      $tpl = array();
      $background = '';
      $drugTest = '';

      $settings = InternSettings::getInstance();

      $tpl = array();
      $tpl['NAME'] = $i->getFullName();
      $tpl['BANNER'] = $i->banner;
      $tpl['BIRTHDAY'] = $i->getBirthDateFormatted();
      $tpl['EMAIL'] = $i->getEmailAddress() . $settings->getEmailDomain();
      $tpl['AGENCY'] = $agency->getName();

      if ($backgroundCheck)
          $background = 'Background';

      if ($drugCheck)
          $drugTest = 'Drug';

      if ($backgroundCheck && $drugCheck)
      {
          $subject = 'Internship Background/Drug Check Needed ' . $i->getFullName();
          $tpl['CHECK'] = $background . '/' . $drugTest;
      }else{
          $subject = 'Internship ' . $background . $drugTest . ' Check Needed ' . $i->getFullName();
          $tpl['CHECK'] = $background . $drugTest;
      }

      $to = $settings->getBackgroundCheckEmail();

      email::sendTemplateMessage($to, $subject, 'email/BackgroundDrugCheck.tpl', $tpl);
  }
}
