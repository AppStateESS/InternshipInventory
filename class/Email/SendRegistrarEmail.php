<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use Intern\Faculty;

class SendRegistrarEmail extends Email {

  /**
  * Sends an email to the registrar notifying them to register
  * the student for the appropriate internship course.
  *
  * @param Internship $i
  * @param Agency $a
  */
  public function __construct(Internship $i, Agency $a) {
    echo("CLASS: SendRegistrarEmail");
    self::sendSpecialMessage($i, $a);
  }

  /*
   * Sets up special components of registrar email.
   */
  public function setUpSpecial() {
    if(isset($this->internship->course_subj)){
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

    if($this->faculty instanceof Faculty){
      $this->faculty = $this->internship->getFaculty();
      $this->tpl['FACULTY'] = $this->faculty->getFullName() . ' ('
      . $this->faculty->getId() . ')';
    }else{
      $this->tpl['FACULTY'] = '(not provided)';
    }

    $department = $this->internship->getDepartment();
    $this->tpl['DEPT'] = $department->getName();

    $campus = $this->internship->getCampus();
    if ($campus == 'distance_ed') {
      $this->tpl['CAMPUS'] = 'Distance Ed';
    } else if ($campus == 'main_campus') {
      $this->tpl['CAMPUS'] = 'Main campus';
    } else {
      $this->tpl['CAMPUS'] = $campus;
    }

    /**** Corequisite Checking ****/
    $coreq = $this->internship->getCorequisiteNum();
    if (!is_null($coreq) && $coreq != '') {
      $this->tpl['COREQ_SUBJECT'] = $this->subjects[$this->internship->course_subj];
      $this->tpl['COREQ_COURSE_NUM'] = $coreq;
      $this->tpl['COREQ_COURSE_SECT'] = $this->internship->getCorequisiteSection();
    }

    /**** International Checking ***/
    if ($this->internship->international) {
      $this->tpl['COUNTRY'] = $this->internship->loc_country;
      $this->tpl['INTERNATIONAL'] = 'Yes';
      $intlSubject = '[int\'l] ';
    } else {
      $this->tpl['STATE'] = $this->internship->loc_state;
      $this->tpl['INTERNATIONAL'] = 'No';
      $intlSubject = '';
    }

    /**** Multi-part checking ***/
    if ($this->internship->isMultipart() && $this->internship->isSecondaryPart()) {
      $this->tpl['SECONDARY_PART'] = '';
    }

    /***
    * Figure out who the notification email goes to
    */
    // Send distance ed internship to speedse, per trac #110
    if ($this->internship->isDistanceEd()) {
      $this->to = $this->settings->getDistanceEdEmail();

      // Send all international or graduate internships to
      //  'hicksmp', per trac #102
    } else if ($this->internship->isInternational() || $this->internship->isGraduate()) {
      $this->to = $this->settings->getGraduateRegEmail();

      // Otherwise, send it to the general Registrar address
    } else {
      $this->to = $this->settings->getRegistrarEmail();
    }

    if(!isset($this->to) || $this->to == null) {
      throw new \InvalidArgumentException('Missing configurating for email
      addresses (registrar)');
    }

    // CC the faculty members
    if ($this->faculty instanceof Faculty) {
      $cc = array($this->faculty->getUsername() . $this->settings->getEmailDomain());
    } else {
      $cc = array();
    }

    $this->subject = $this->tpl['TERM'] . ' ' . $intlSubject . '[' . $this->internship->getBannerId()
    . '] ' . $this->internship->getFullName();
    $this->doc = 'email/RegistrarEmail.tpl';
    $this->cc = $cc;
  }
}
