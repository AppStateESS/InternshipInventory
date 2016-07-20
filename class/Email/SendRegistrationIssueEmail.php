<?php

namespace Intern\Email;

class SendRegistrationIssueEmail extends Email {

  /**
   *  Sends the 'Registration Issue' notification email.
   *
   * @param Internship $i
   * @param Agency $agency
   * @param string $note
   */
  public static function __construct(Internship $i, Agency $agency, $note)
  {
      $tpl = array();

      $subjects = Subject::getSubjects();

      $settings = InternSettings::getInstance();

      $faculty = $i->getFaculty();

      $tpl = array();
      $tpl['NAME'] = $i->getFullName();
      $tpl['BANNER'] = $i->banner;
      $tpl['USER'] = $i->email;
      $tpl['PHONE'] = $i->phone;

      $tpl['TERM'] = Term::rawToRead($i->term, false);
      if(isset($i->course_subj)){
          $tpl['SUBJECT'] = $subjects[$i->course_subj];
      }else{
          $tpl['SUBJECT'] = '(No course subject provided)';
      }
      $tpl['COURSE_NUM'] = $i->course_no;

      if(isset($i->course_sect)){
          $tpl['SECTION'] = $i->course_sect;
      }else{
          $tpl['SECTION'] = '(not provided)';
      }

      if(isset($i->course_title)){
          $tpl['COURSE_TITLE'] = $i->course_title;
      }

      if(isset($i->credits)){
          $tpl['CREDITS'] = $i->credits;
      }else{
          $tpl['CREDITS'] = '(not provided)';
      }

      $startDate = $i->getStartDate(true);
      if(isset($startDate)){
          $tpl['START_DATE'] = $startDate;
      }else{
          $tpl['START_DATE'] = '(not provided)';
      }

      $endDate = $i->getEndDate(true);
      if(isset($endDate)){
          $tpl['END_DATE'] = $endDate;
      }else{
          $tpl['END_DATE'] = '(not provided)';
      }

      if($faculty instanceof Faculty){
          $tpl['FACULTY'] = $faculty->getFullName();
      }else{
          $tpl['FACULTY'] = '(not provided)';
      }

      $department = $i->getDepartment();
      $tpl['DEPT'] = $department->getName();

      if($i->international){
          $tpl['COUNTRY'] = $i->loc_country;
          $tpl['INTERNATIONAL'] = 'Yes';
          $intlSubject = '[int\'l] ';
      }else{
          $tpl['STATE'] = $i->loc_state;
          $tpl['INTERNATIONAL'] = 'No';
          $intlSubject = '';
      }

      $tpl['NOTE'] = $note;

      $to = $i->email . $settings->getEmailDomain();
      if ($faculty instanceof Faculty) {
          $cc = array($faculty->getUsername() . $settings->getEmailDomain());
      } else {
          $cc = array();
      }

      $subject = 'Internship Enrollment Issue';

      email::sendTemplateMessage($to, $subject,
        'email/RegistrationIssue.tpl', $tpl, $cc);
  }
}
