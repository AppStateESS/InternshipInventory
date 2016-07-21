<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;

class RegistrationConfirm extends Email{

  /**
   * Sends the 'Registration Confirmation' email.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public static function __construct(Internship $i, Agency $a) {
      echo("CLASS: RegistrationConfirm");
      sendSpecialMessage($i, $a);
  }

  public function setUpSpecial() {
    if(isset($this->internship->getCour)){
        $this->tpl['SUBJECT'] = $this->subjects[$this->internship->course_subj];
    }else{
        $this->tpl['SUBJECT'] = '(No course subject provided)';
    }
    $this->tpl['COURSE_NUM'] = $this->internship->course_no;

    if(isset($this->internship->course_sect)){
        $this->tpl['SECTION'] = $this->internship->course_sect;
    }else{
        $this->tpl['SECTION'] = '(not provided)';
    }

    if(isset($this->internship->course_title)){
        $this->tpl['COURSE_TITLE'] = $this->internship->course_title;
    }

    if(isset($this->internship->credits)){
        $this->tpl['CREDITS'] = $this->internship->credits;
    }else{
        $this->tpl['CREDITS'] = '(not provided)';
    }

    $startDate = $this->internship->getStartDate(true);
    if(isset($startDate)){
        $this->tpl['START_DATE'] = $startDate;
    }else{
        $this->tpl['START_DATE'] = '(not provided)';
    }

    $endDate = $this->internship->getEndDate(true);
    if(isset($endDate)){
        $this->tpl['END_DATE'] = $endDate;
    }else{
        $this->tpl['END_DATE'] = '(not provided)';
    }

    if($faculty instanceof Faculty){
        $this->tpl['FACULTY'] = $faculty->getFullName();
    }else{
        $this->tpl['FACULTY'] = '(not provided)';
    }

    $department = $this->internship->getDepartment();
    $this->tpl['DEPT'] = $department->getName();

    if($this->internship->international){
        $this->tpl['COUNTRY'] = $this->internship->loc_country;
        $this->tpl['INTERNATIONAL'] = 'Yes';
        $intlSubject = '[int\'l] ';
    }else{
        $this->tpl['STATE'] = $i->loc_state;
        $this->tpl['INTERNATIONAL'] = 'No';
        $intlSubject = '';
    }

    $to = $this->internship->email . $settings->getEmailDomain();
    if ($faculty instanceof Faculty) {
        $cc = array($faculty->getUsername() . $settings->getEmailDomain());
    } else {
        $cc = array();
    }
    $this->subject = 'Internship Approved';
    $this->doc = 'email/RegistrationConfirmation.tpl';
  }
}
