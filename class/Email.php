<?php

class Email {

    public function sendTemplateMessage($to, $subject, $tpl, $tags, $cc = null)
    {
        $content = PHPWS_Template::process($tags, 'intern', $tpl);

        self::sendEmail($to, NULL, $subject, $content, $cc);
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
            $result = true;
        }else{
            $result = $message->send();
        }

        if(PEAR::isError($result)){
            PHPWS_Error::log($result);
            return false;
        }

        self::logEmail($message);
        
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
    
    /**
     * Sends an email to the registrar notifying them to register
     * the student for the appropriate internship course.
     *  
     * @param Student $s
     * @param Internship $i
     * @param Agency $a
     */
    public static function sendRegistrarEmail($s, $i, $a)
    {
        $faculty = $i->getFacultySupervisor();
        
        $tpl = array();
        $tpl['NAME'] = "$s->first_name $s->middle_name $s->last_name";
        $tpl['BANNER'] = $s->banner;
        $tpl['USER'] = $s->email;
        $tpl['PHONE'] = $s->phone;
        
        $tpl['TERM'] = Term::rawToRead($i->term);
        $tpl['SUBJECT'] = $i->course_subj;
        $tpl['COURSE_NUM'] = $i->course_no;
        
        if(isset($i->course_sect)){
            $tpl['SECTION'] = $i->course_sect;
        }else{
            $tpl['SECTION'] = '(not provided)';
        }
        
        if(isset($i->course_title)){
            $tpl['COURSE_TITLE'] = $i->course_title;
        }
        
        if($i->international){
            $tpl['COUNTRY'] = $i->loc_country;
        }else{
            $tpl['STATE'] = $i->loc_state;
        }
        
        $tpl['APPROVED_BY'] = $i->approved_by;
        $tpl['APPROVED_ON'] = date('g:ia m/d/Y', $i->approved_on);
        
        $to = REGISTRAR_EMAIL_ADDRESS;
        $cc = array($s->email . '@appstate.edu', $faculty->email . '@appstate.edu');
        $subject = 'Internship Approved - Ready for Registration';
        
        Email::sendTemplateMessage($to, $subject, 'email/RegistrarEmail.tpl', $tpl, $cc);
    }

    public static function sendIntlInternshipCreateNotice($s, $i)
    {
        $tpl = array();
        
        $tpl['NAME'] = "$s->first_name $s->middle_name $s->last_name";
        $tpl['BANNER'] = $s->banner;
        $tpl['USER'] = $s->email;
        $tpl['PHONE'] = $s->phone;
        
        $tpl['TERM'] = Term::rawToRead($i->term);
        $tpl['COUNTRY'] = $i->loc_country;
        
        $dept = new Department($i->department_id);
        $tpl['DEPARTMENT'] = $dept->getName();
        
        $to = array('lewandoskik@appstate.edu', 'gomisjd@appstate.edu');
        $subject = "International Internship Created - {$s->first_name} {$s->last_name}";
        
        Email::sendTemplateMessage($to, $subject, 'email/IntlInternshipCreateNotice.tpl', $tpl);
    }
}

?>