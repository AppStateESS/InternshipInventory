<?php

class Email {

    public function sendTemplateMessage($to, $subject, $tpl, $tags)
    {
        $content = PHPWS_Template::process($tags, 'intern', $tpl);

        self::sendEmail($to, NULL, $subject, $content);
    }

    public function sendEmail($to, $from, $subject, $content, $cc = NULL, $bcc = NULL)
    {
        // Sanity checking
        if(!isset($to) || is_null($to)){
            return false;
        }

        if(!isset($from) || is_null($from)){
            $from = SYSTEM_NAME . ' <' . FROM_ADDRESS .'>';
        }

        if(!isset($subject) || is_null($subject)){
            return false;
        }

        if(!isset($content) || is_nulL($content)){
            return false;
        }

        // Create a Mail object and set it up
        PHPWS_Core::initCoreClass('Mail.php');
        $message = new PHPWS_Mail;

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
            self::logEmail($message);
            $result = true;
        }else{
            $result = $message->send();
        }

        if(PEAR::isError($result)){
            PHPWS_Error::log($result);
            return false;
        }

        return true;
    }
    
    /**
    * Logs a PHPWS_Mail object to a text file
    */
    public function logEmail($message)
    {
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
                fprintf($fd, "Bcc: %s\n", $bcc);
            }
        }
    
        fprintf($fd, "From: %s\n", $message->from_address);
        fprintf($fd, "Subject: %s\n", $message->subject_line);
        fprintf($fd, "Content: \n");
        fprintf($fd, "%s\n\n", $message->message_body);
    
        fclose($fd);
    }

}

?>