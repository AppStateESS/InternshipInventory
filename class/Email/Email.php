<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern\Email;

use \Intern\InternSettings;

// Setup autoloader for Composer to load SwiftMail via autoload
require_once PHPWS_SOURCE_DIR . 'mod/intern/vendor/autoload.php';

use \Symfony\Component\Mailer\Transport;
use \Symfony\Component\Mailer\Mailer;
use \Symfony\Component\Mime\Address;

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
        $message = $this->buildSwiftMessage($this->to, $this->fromAddress, $this->fromName, $this->subject, $bodyContent, $this->cc, $this->bcc);

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
     * @param  Array $to
     * @param  string $fromAddress
     * @param  string $fromName
     * @param  string $subject
     * @param  string $content
     * @param  Array $cc
     * @param  Array $bcc
     * @return Symfony\Component\Mime\Email if successful.
     */
    protected static function buildSwiftMessage($to, $fromAddress, $fromName, $subject, $content, $cc = NULL, $bcc = NULL){

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
        $message = (new \Symfony\Component\Mime\Email())
            ->subject($subject)
            ->from( new Address($fromAddress, $fromName))
            ->to(...$to)
            ->text($content);

        if(isset($cc)){
            $message->cc(...$cc);
        }

        if(isset($bcc)){
            $message->bcc(...$bcc);
        }

        return $message;
    }

    protected static function sendSwiftMessage(\Symfony\Component\Mime\Email $message)
    {
        //Set up Swift Mailer delivery
        $transport = Transport::fromDsn('sendmail://default');
        $mailer = new Mailer($transport);

        // If we're not in test mode, actually send the message
        if(!EMAIL_TEST_FLAG){
            $mailer->send($message); // send() returns the number of successful recipients. Can be 0, which indicates failure
        }

        self::logEmail($message);

        return true;
    }

    /**
    * Stores the email in file email.log
    *
    * @param  $message
    */
    public static function logEmail(\Symfony\Component\Mime\Email $message){
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
                fprintf($fd, "Bcc: %s\n", $address);
            }
        }

        fprintf($fd, "From: %s\n", $message->getFrom()[0]->getAddress(), $message->getFrom()[0]->getName());
        fprintf($fd, "Subject: %s\n", $message->getSubject());
        fprintf($fd, "Content: \n");
        fprintf($fd, "%s\n\n", $message->getTextBody());

        fclose($fd);
    }
}
