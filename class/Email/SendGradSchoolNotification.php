<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use Intern\Faculty;

class SendGradSchoolNotification extends Email {

  /**
   * Sends an email to the grad school office, letting them know there's someone to notify
   *
   * @param Internship $i
   * @param Agency $a
   */
  public function __construct(Internship $i, Agency $a) {
    echo("CLASS: SendGradSchoolNotification");
    $this->sanityCheck();
    $this->sendSpecialMessage($i, $a);
  }

  /**
   * Adds information to email unique to grad school.
   */
  protected function setUpSpecial() {
    $this->tpl['COURSE_NUM'] = $this->internship->getCourseNumber();
    $this->tpl['DEPT'] = $this->internship->getDepartment()->getName();

    $campus = $this->internship->getCampus();
    if($campus == 'distance_ed'){
        $this->tpl['CAMPUS'] = 'Distance Ed';
    }else if($campus == 'main_campus'){
        $this->tpl['CAMPUS'] = 'Main campus';
    }else{
        $this->tpl['CAMPUS'] = $campus;
    }

    $emails = $this->settings->getGradSchoolEmail(); // To Holly Hirst, for now

    $this->to = explode(',', $emails);
    $this->subject = 'Internship Approval Needed: ' . $this->intlSubject
      . '[' . $this->internship->getBannerId() . '] ' . $this->internship->getFullName();
    $this->doc = 'email/GradSchoolNotification.tpl';
  }
}
