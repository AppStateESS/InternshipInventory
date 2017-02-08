<?php
namespace Intern\Email;

use \Intern\InternSettings;

// Setup autoloader for Composer to load SwiftMail via autoload
require_once PHPWS_SOURCE_DIR . 'mod/intern/vendor/autoload.php';

/**
 * Abstract class for representing an email to be sent. Provides a
 * central implementaion of message sending/delivery via SwiftMail
 * library. To use, implment a concrete child class, call the child
 * class constructor, then call the send() method.
 *
 * This class could later be abstracted further to use alternate delivery
 * providers (i.e. a transactional email API).
 *
 * @author jbooker
 * @package Intern\Email
 */
abstract class Email {

    // Address info, initialized to empty arrays in constructor
    protected $to;
    protected $cc;
    protected $bcc;

    // From name and address, defaulted to system name and address settings
    protected $fromName;
    protected $fromAddress;

    protected $subject; // Must be set by concrete implementations in buildMessage()

    protected $tpl; // Array of template tags, setup in buildMessage()


    protected $emailSettings; // Instance of InternSettings class, holds system settings


    /**
     * Constructor
     * Initializses to/cc/bbc arrays to empty. Sets 'from' information via InternSettings
     *
     * @param \Intern\InternSettings $settings Instance of an InternSettings class. Available via InternSettings::getInstance()
     */
    public function __construct(InternSettings $settings)
    {
        $this->tpl = array();
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();

        $this->emailSettings = $settings;

        // Set a default from address and name, based on system settings
        // Child classes can overwrite these values
        $this->fromName = $this->emailSettings->getSystemName();
        $this->fromAddress = $this->emailSettings->getEmailFromAddress();
    }

    protected abstract function buildMessage();
    protected abstract function getTemplateFileName();

    public function send()
    {
        // Build the message template and to/cc/from fields
        $this->buildMessage();

        // Get the body of the message by processing the template tag array into a template file
        $bodyContent = $this->buildMessageBody($this->getTemplateFileName());

        // Build a SwiftMessage object from member variables, settings, and body content
        $message = $this->buildSwiftMessage($this->emailSettings, $this->to, $this->fromAddress, $this->fromName, $this->subject, $bodyContent, $this->cc, $this->bcc);

        // Send the SwiftMail message
        $this->sendSwiftMessage($message);
    }


    protected function buildMessageBody($templateFileName)
    {
        $bodyContent = \PHPWS_Template::process($this->tpl, 'intern', $templateFileName);

        return $bodyContent;
    }

    /**
     * Performs the email delivery process.
     *
     * @param  $to
     * @param  $fromAddress
     * @param  $fromName
     * @param  $subject
     * @param  $content
     * @param  $cc
     * @param  $bcc
     * @return True if successful.
     */
    protected static function buildSwiftMessage(InternSettings $settings, $to, $fromAddress, $fromName, $subject, $content, $cc = NULL, $bcc = NULL){
        $fromAddress = $settings->getEmailFromAddress();
        $fromName = $settings->getSystemName();

        // Sanity checking
        if(!isset($to) || $to === null){
            throw new \InvalidArgumentException('\"To\" not set.');
        }

        if(!isset($fromAddress) || $fromAddress === null){
            throw new \InvalidArgumentException('\"From Address\" not set.');
        }

        if(!isset($fromName) || $fromName === null){
            throw new \InvalidArgumentException('\"From Name\" not set.');
        }

        if(!isset($subject) || $subject === null){
            throw new \InvalidArgumentException('\"Subject\" not set.');
        }

        if(!isset($content) || $content === null){
            throw new \InvalidArgumentException('\"Content\" not set.');
        }

        // Set up Swift Mailer message
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array($fromAddress => $fromName))
            ->setTo($to,$to)
            ->setBody($content);

        if(isset($cc)){
            $message->setCc($cc);
        }

        if(isset($bcc)){
            $message->setBcc($bcc);
        }

        return $message;
    }

    protected static function sendSwiftMessage(\Swift_Message $message)
    {
        //Set up Swift Mailer delivery
        $transport = \Swift_SmtpTransport::newInstance('localhost');
        $mailer = \Swift_Mailer::newInstance($transport);

        // Send the message
        if(EMAIL_TEST_FLAG){
            $result = true;
        }else{
            $result = $mailer->send($message);
        }

        self::logEmail($message);

        return true;
    }

    /**
    * Stores the email in file email.log
    *
    * @param  $message
    */
    public static function logEmail(\Swift_Message $message){
        // Log the message to a text file
        $fd = fopen(PHPWS_SOURCE_DIR . 'logs/email.log',"a");

        fprintf($fd, "=======================\n");

        fprintf($fd, "To: %s\n", implode('', array_keys($message->getTo())));

        if($message->getCc() != null){
            foreach($message->getCc() as $address => $name){
                fprintf($fd, "Cc: %s\n", $address);
            }
        }

        if($message->getBcc() != null){
            foreach($message->getBcc() as $address => $name){
                fprintf($fd, "Bcc: %s\n", $recipient);
            }
        }

        fprintf($fd, "From: %s\n", implode('',$message->getFrom()));
        fprintf($fd, "Subject: %s\n", $message->getSubject());
        fprintf($fd, "Content: \n");
        fprintf($fd, "%s\n\n", $message->getBody());

        fclose($fd);
    }










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

        $this->sendTemplateMessage($this->to, $this->subject, $this->doc,
        $this->tpl, $this->cc);
    }
}
