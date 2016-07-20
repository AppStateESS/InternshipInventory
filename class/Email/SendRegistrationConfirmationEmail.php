<?php

namespace Intern\Email;

class RegistrationConfirm extends Email{

  /**
   * Sends the 'Registration Confirmation' email.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public static function __construct(Internship $i, Agency $a) {
      sendSpecialMessage($i, $a);
  }

  public function setUpSpecial() {
    $settings = InternSettings::getInstance();

    $tpl = array();

    $subjects = Subject::getSubjects();

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

    $to = $i->email . $settings->getEmailDomain();
    if ($faculty instanceof Faculty) {
        $cc = array($faculty->getUsername() . $settings->getEmailDomain());
    } else {
        $cc = array();
    }
    $subject = 'Internship Approved';

    email::sendTemplateMessage($to, $subject,
      'email/RegistrationConfirmation.tpl', $tpl, $cc);
  }
}
