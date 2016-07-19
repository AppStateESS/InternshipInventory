<?php

namespace Intern\Email;

abstract class Email {

  //Lowest common denominator setup for all special emails
  protected function sendSpecialMessage(Internship $i,
    Agency $agency = null, $note = null) {

    $settings = InternSettings::getInstance();

    $tpl = array();
    $tpl['NAME'] = $i->getFullName();
    $tpl['BANNER'] = $i->getBannerId();
    $tpl['USER'] = $i->getEmailAddress();
    $tpl['PHONE'] = $i->getPhoneNumber();
    $tpl['TERM'] = Term::rawToRead($i->getTerm(), false);

    $outputs = self::setUpSpecial();

    self::sendTemplateMessage($outputs['to'], $outputs['subject'], $outputs['doc'], $tpl);
  }

  //Hook method for specialized email setup
  protected absract function setUpSpecial();

  public static function sendTemplateMessage($to,
  $subject, $tpl, $tags, $cc = null){
    $settings = InternSettings::getInstance();

    $content = \PHPWS_Template::process($tags, 'intern', $tpl);

    self::sendEmail($to, $settings->getEmailFromAddress(), $subject, $content, $cc);
  }

  public static function sendEmail($to, $from,
  $subject, $content, $cc = NULL, $bcc = NULL){
    $settings = InternSettings::getInstance();

    // Sanity checking
    if(!isset($to) || is_null($to)){
        return false;
    }

    if(!isset($from) || is_null($from)){
        $from = $settings->getSystemName() . ' <' . $settings->getEmailFromAddress() .'>';
    }

    if(!isset($subject) || is_null($subject)){
        return false;
    }

    if(!isset($content) || is_nulL($content)){
        return false;
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
        return false;
    }

    self::logEmail($message);

    return true;
  }

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
