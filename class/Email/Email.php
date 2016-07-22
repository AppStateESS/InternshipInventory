<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use Intern\InternSettings;
use Intern\Term;
use Intern\Subject;
use ErrorException;

/**
 * Allows for the simple sending of email messages. Follows the general flow:
 *
 * sendSpecialMessage() -> sendTemplateMessage() -> sendEmail() -> logEmail()
 * 	dynamic w/ hook							static							  static        static
 *
 * A message can be processed at any point in this flow, depending on the
 * desired function of the email.
 */
abstract class Email {

  protected $internship;
  protected $agency;
  protected $settings;
  protected $faculty;
  protected $note;
  protected $backgroundCheck;
  protected $drugCheck;
  protected $subjects;
  protected $to;
  protected $subject;
  protected $doc;
  protected $tpl;
  protected $cc;
  protected $intlSubject;

  /**
   * Template method for specialized email messages. Subclasses will
   * call this method and implement their own setUpSpecial() hook to meet
   * their specialized needs.
   *
   * @param  $i                 Internship obj provides email data for all classes
   * @param  $agency            Agency objected needed for most subclasses
   * @param  $note              Necessary for class RegistrationIssue
   * @param  $backgroundCheck   Necessary for class SendBackgroundCheckEmail
   * @param  $drugCheck         Necessary for class SendBackgroundCheckEmail
   */
  protected final function sendSpecialMessage(Internship $i,
    Agency $agency = null, $note = null, $backgroundCheck = false,
    $drugCheck = false) {

    //Set parameters
    $this->internship = $i;
    $this->agency = $agency;
    $this->note = $note;
    $this->backgroundCheck = $backgroundCheck;
    $this->drugCheck = $drugCheck;

    //Necessary global variables
    $this->subjects = Subject::getSubjects();
    $this->settings = InternSettings::getInstance();
    $this->faculty = $this->internship->getFaculty();

    //Basic tpl entries. Specific tpl entries set in hook
    $this->tpl = array();
    $this->tpl['NAME'] = $this->internship->getFullName();
    $this->tpl['BANNER'] = $this->internship->getBannerId();
    $this->tpl['USER'] = $this->internship->getEmailAddress();
    $this->tpl['PHONE'] = $this->internship->getPhoneNumber();
    $this->tpl['BIRTHDAY'] = $this->internship->getBirthDateFormatted();
    $this->tpl['TERM'] = Term::rawToRead($this->internship->getTerm(), false);

    //Call to hook. Sets $to, $subject, $doc, $cc, and additional $tpl based
    //on the specific type of email calling sendSpecialMessage()
    $this->setUpSpecial();

    //special email debug
    var_dump($this->internship);
    var_dump($this->agency);
    var_dump($this->settings);
    var_dump($this->faculty);
    var_dump($this->note);
    var_dump($this->backgroundCheck);
    var_dump($this->drugCheck);
    var_dump($this->to);
    var_dump($this->subject);
    var_dump($this->doc);
    var_dump($this->tpl);
    var_dump($this->cc);
    exit();
    //Special setup passed on to static procedures to continue email processing
    $this->sendTemplateMessage($this->to, $this->subject, $this->doc,
      $this->tpl, $this->cc);
  }

  /**
   * Hook for the template method sendSpecialMessage(). Allows Email subclasses
   * to provide additional information to sendTemplateMessage() for their
   * specialized purpose.
   */
  abstract protected function setUpSpecial();

  /**
   * Performs common sanity check for classes that require this. Should only
   * be called from child class setUpSpecial() hook to avoid null references.
   */
  protected final function sanityCheck() {
    /**** Subject Checking ***/
    $subject = $this->internship->getSubject()->getId();
    if($subject != 0){
        $this->tpl['SUBJECT'] = $this->subjects[$subject];
    }else{
        $this->tpl['SUBJECT'] = '(No course subject provided)';
    }

    /**** Course Section Checking ***/
    $section = $this->internship->getCourseSection();
    if(!empty($section)){
        $this->tpl['SECTION'] = $section;
    }else{
        $this->tpl['SECTION'] = '(Section not provided)';
    }

    /**** Course Title Checking ***/
    $courseTitle = $this->internship->getCourseTitle();
    if(!empty($courseTitle)){
        $this->tpl['COURSE_TITLE'] = $courseTitle;
    }else{
        $this->tpl['COURSE_TITLE'] = '(Course title not provided)';
    }

    /**** Credit Hour Checking ***/
    $creditHours = $this->internship->getCreditHours();
    if(isset($creditHours)){
        $this->tpl['CREDITS'] = $creditHours;
    }else{
        $this->tpl['CREDITS'] = '(Credit hours not provided)';
    }

    /**** Start Date Checking ***/
    $startDate = $this->internship->getStartDate(true);
    if(isset($startDate)){
        $this->tpl['START_DATE'] = $startDate;
    }else{
        $this->tpl['START_DATE'] = '(Start date not provided)';
    }

    /**** End Date Checking ***/
    $endDate = $this->internship->getEndDate(true);
    if(isset($endDate)){
        $this->tpl['END_DATE'] = $endDate;
    }else{
        $this->tpl['END_DATE'] = '(End date not provided)';
    }

    /**** Faculty Checking ***/
    //Id for all: Grad, RegE, RegC, RegI? Ask Jeremy. Originally just RegE
    if($this->faculty instanceof Faculty){
        $this->tpl['FACULTY'] = $this->faculty->getFullName() . ' ('
          . $this->facutly->getId() . ')';
    }else{
        $this->tpl['FACULTY'] = '(Faculty not provided)';
    }

    /**** International Checking ***/
    if($this->internship->isInternational()){
        $this->tpl['COUNTRY'] = $this->internship->getLocCountry();
        $this->tpl['INTERNATIONAL'] = 'Yes';
        $this->intlSubject = '[int\'l] ';
    }else{
        $this->tpl['STATE'] = $this->internship->getLocationState();
        $this->tpl['INTERNATIONAL'] = 'No';
        $this->intlSubject = '';
    }
  }

  /**
   * Uses PHPWS_Template to construct a template email, which is
   * then passed on the sendEmail()
   *
   * @param $to
   * @param $subject
   * @param $tpl
   * @param $tags
   * @param $cc
   */
  public static function sendTemplateMessage($to,
  $subject, $tpl, $tags, $cc = null){
    $settings = InternSettings::getInstance();

    $content = \PHPWS_Template::process($tags, 'intern', $tpl);

    self::sendEmail($to, $settings->getEmailFromAddress(), $subject, $content, $cc);
  }

  /**
   * Performs the email delivery process.
   *
   * @param  $to
   * @param  $from
   * @param  $subject
   * @param  $content
   * @param  $cc
   * @param  $bcc
   * @return True if successful.
   */
  public static function sendEmail($to, $from,
  $subject, $content, $cc = NULL, $bcc = NULL){
    $settings = InternSettings::getInstance();

    // Sanity checking
    if(!isset($to) || is_null($to)){
        throw new ErrorException('\"To\" not set.');
    }

    if(!isset($from) || is_null($from)){
        $from = $settings->getSystemName() . ' <' . $settings->getEmailFromAddress() .'>';
    }

    if(!isset($subject) || is_null($subject)){
        throw new ErrorException('\"Subject\" not set.');
    }

    if(!isset($content) || is_nulL($content)){
        throw new ErrorException('\"Content\" not set.');
    }

    // Create a Mail object and set it up
    \PHPWS_Core::initCoreClass('Mail.php');
    $message = new \PHPWS_Mail;

    $message->addSendTo($to);
    $message->setFrom($from);
    $message->setSubject($subject);
    $message->setMessageBody($content);

    if(isset($cc)){
        $message->addCarbonCopy($cc);
    }

    if(isset($bcc)){
        $message->addBlindCopy($bcc);
    }

    // Send the message
    if(EMAIL_TEST_FLAG){
        $result = true;
    }else{
        $result = $message->send();
    }

    if(\PHPWS_Error::logIfError($result)){
      throw new ErrorException('PHPWS_Error.');
    }

    self::logEmail($message);

    return true;
  }

  /**
   * Stores the email in file email.log
   *
   * @param  $message
   */
  public static function logEmail($message){
    // Log the message to a text file
    $fd = fopen(PHPWS_SOURCE_DIR . 'logs/email.log',"a");

    fprintf($fd, "=======================\n");

    foreach($message->send_to as $recipient){
        fprintf($fd, "To: %s\n", $recipient);
    }

    if(isset($message->carbon_copy)){
        foreach($message->carbon_copy as $recipient){
            fprintf($fd, "Cc: %s\n", $recipient);
        }
    }

    if(isset($message->blind_copy)){
        foreach($message->blind_copy as $recipient){
            fprintf($fd, "Bcc: %s\n", $recipient);
        }
    }

    fprintf($fd, "From: %s\n", $message->from_address);
    fprintf($fd, "Subject: %s\n", $message->subject_line);
    fprintf($fd, "Content: \n");
    fprintf($fd, "%s\n\n", $message->message_body);

    fclose($fd);
  }
}
