<?php

namespace Intern\Email;

class GradSchoolNotice extends Email {

  /**
   * Sends an email to the grad school office, letting them know there's someone to notify
   *
   * @param Internship $i
   * @param Agency $a
   */
  public static function sendGradSchoolNotification(Internship $i, Agency $a)
  {
      Email::sendSpecialMessage($i, $a);
  }

  protected function setUpSpecial()
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
        $advisor = $i->getFaculty();
        $tpl['FACULTY'] = $advisor->getFullName();
    }else{
        $tpl['FACULTY'] = '(not provided)';
    }

    $department = $i->getDepartment();
    $tpl['DEPT'] = $department->getName();

    $campus = $i->getCampus();
    if($campus == 'distance_ed'){
        $tpl['CAMPUS'] = 'Distance Ed';
    }else if($campus == 'main_campus'){
        $tpl['CAMPUS'] = 'Main campus';
    }else{
        $tpl['CAMPUS'] = $campus;
    }

    if($i->international){
        $tpl['COUNTRY'] = $i->loc_country;
        $tpl['INTERNATIONAL'] = 'Yes';
        $intlSubject = '[int\'l] ';
    }else{
        $tpl['STATE'] = $i->loc_state;
        $tpl['INTERNATIONAL'] = 'No';
        $intlSubject = '';
    }

    $emails = $settings->getGradSchoolEmail(); // To Holly Hirst, for now

    $ouputs = array();
    $outputs['to'] = = explode(',', $emails);
    $outputs['subject'] = = 'Internship Approval Needed: ' . $intlSubject . '[' . $i->getBannerId() . '] ' . $i->getFullName();
    $outputs['doc'] = 'email/GradSchoolNotification.tpl';
    return $outputs;
  }
}
