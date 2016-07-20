<?php

namespace Intern\Email;

class SendRegistrarEmail extends Email {

  /**
  * Sends an email to the registrar notifying them to register
  * the student for the appropriate internship course.
  *
  * @param Internship $i
  * @param Agency $a
  */
  public function __construct(Internship $i, Agency $a){
    sendSpecialMessage($i, $a);
  }

  public function setUpSpecial()
  {
    $subjects = Subject::getSubjects();

    $faculty = $i->getFaculty();

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
      $faculty = $i->getFaculty();
      $tpl['FACULTY'] = $faculty->getFullName() . ' ('
      . $faculty->getId() . ')';
    }else{
      $tpl['FACULTY'] = '(not provided)';
    }

    $department = $i->getDepartment();
    $tpl['DEPT'] = $department->getName();

    $campus = $i->getCampus();
    if ($campus == 'distance_ed') {
      $tpl['CAMPUS'] = 'Distance Ed';
    } else if ($campus == 'main_campus') {
      $tpl['CAMPUS'] = 'Main campus';
    } else {
      $tpl['CAMPUS'] = $campus;
    }

    /**** Corequisite Checking ****/
    $coreq = $i->getCorequisiteNum();
    if (!is_null($coreq) && $coreq != '') {
      $tpl['COREQ_SUBJECT'] = $subjects[$i->course_subj];
      $tpl['COREQ_COURSE_NUM'] = $coreq;
      $tpl['COREQ_COURSE_SECT'] = $i->getCorequisiteSection();
    }

    /**** International Checking ***/
    if ($i->international) {
      $tpl['COUNTRY'] = $i->loc_country;
      $tpl['INTERNATIONAL'] = 'Yes';
      $intlSubject = '[int\'l] ';
    } else {
      $tpl['STATE'] = $i->loc_state;
      $tpl['INTERNATIONAL'] = 'No';
      $intlSubject = '';
    }

    /**** Multi-part checking ***/
    if ($i->isMultipart() && $i->isSecondaryPart()) {
      $tpl['SECONDARY_PART'] = '';
    }

    /***
    * Figure out who the notification email goes to
    */
    // Send distance ed internship to speedse, per trac #110
    if ($i->isDistanceEd()) {
      $to = $settings->getDistanceEdEmail();

      // Send all international or graduate internships to
      //  'hicksmp', per trac #102
    } else if ($i->isInternational() || $i->isGraduate()) {
      $to = $settings->getGraduateRegEmail();

      // Otherwise, send it to the general Registrar address
    } else {
      $to = $settings->getRegistrarEmail();
    }

    if(!isset($to) || $to == null) {
      throw new \InvalidArgumentException('Missing configurating for email
      addresses (registrar)');
    }

    // CC the faculty members
    if ($faculty instanceof Faculty) {
      $cc = array($faculty->getUsername() . $settings->getEmailDomain());
    } else {
      $cc = array();
    }



    $ouputs = array();
    $outputs['to'] = $to;
    $outputs['subject'] = $term . ' ' . $intlSubject . '[' . $i->getBannerId()
    . '] ' . $i->getFullName();
    $outputs['doc'] = 'email/RegistrarEmail.tpl';
    $outputs['cc'] = $cc;
    return $outputs;
  }
}
