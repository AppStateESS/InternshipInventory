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
    //Triggered by grad school approval request
    echo("CLASS: SendRegistrarEmail");
    $this->sendSpecialMessage($i, $a);
  }

  /*
   * Sets up special components of registrar email.
   */
  public function setUpSpecial() {
    $this->sanityCheck();

    $this->tpl['DEPT'] = $this->internship->getDepartment()->getName();
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
      $this->tpl['COREQ_SUBJECT'] = $this->subjects[$this->internship->getSubject()];
      $this->tpl['COREQ_COURSE_NUM'] = $coreq;
      $this->tpl['COREQ_COURSE_SECT'] = $this->internship->getCorequisiteSection();
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
      $this->cc = array($this->faculty->getUsername() . $this->settings->getEmailDomain());
    } else {
      $this->cc = array();
    }
    $this->subject = $this->tpl['TERM'] . ' ' . $this->intlSubject . '[' . $this->internship->getBannerId()
    . '] ' . $this->internship->getFullName();
    $this->doc = 'email/RegistrarEmail.tpl';
  }
}
