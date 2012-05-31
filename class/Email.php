<?php

class Email {

    public function sendTemplateMessage($to, $subject, $tpl, $tags, $cc = null)
    {
        $content = PHPWS_Template::process($tags, 'intern', $tpl);

        self::sendEmail($to, EMAIL_FROM_ADDRESS, $subject, $content, $cc);
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
    public static function sendRegistrarEmail(Internship $i, Agency $a)
    {
        PHPWS_Core::initModClass('intern', 'Subject.php');

        $subjects = Subject::getSubjects();

        $faculty = $i->getFacultySupervisor();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;
        $tpl['USER'] = $i->email;
        $tpl['PHONE'] = $i->phone;

        $tpl['TERM'] = Term::rawToRead($i->term, false);
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

        if(isset($i->faculty_supervisor_id)){
            $advisor = $i->getFacultySupervisor();
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

        /***
         * Figure out who the notification email goes to
         */
        // Send distance ed internship to speedse, per trac #110
        if($i->isDistanceEd()){
            $to = 'speedse@appstate.edu';
            
        // Send all international or graduate internships to 'hicksmp', per trac #102 
        }else if($i->isInternational() || $i->isGraduate()){
            $to = 'hicksmp@appstate.edu';
        
        // Otherwise, send it to the general Registrar address
        }else{
            $to = REGISTRAR_EMAIL_ADDRESS;
        }
        
        // CC the faculty members
        $cc = array($faculty->email . '@appstate.edu');
        
        $subject = 'Internship Approved: ' . $intlSubject . '[' . $i->getBannerId() . '] ' . $i->getFullName();

        Email::sendTemplateMessage($to, $subject, 'email/RegistrarEmail.tpl', $tpl, $cc);
    }

    public static function sendIntlInternshipCreateNotice(Internship $i)
    {
        $tpl = array();

        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;
        $tpl['USER'] = $i->email;
        $tpl['PHONE'] = $i->phone;

        $tpl['TERM'] = Term::rawToRead($i->term);
        $tpl['COUNTRY'] = $i->loc_country;

        $dept = new Department($i->department_id);
        $tpl['DEPARTMENT'] = $dept->getName();

        $to = array('lewandoskik@appstate.edu', 'gomisjd@appstate.edu');
        $subject = "International Internship Created - {$i->first_name} {$i->last_name}";

        Email::sendTemplateMessage($to, $subject, 'email/IntlInternshipCreateNotice.tpl', $tpl);
    }

    public static function sendRegistrationConfirmationEmail(Internship $i, Agency $a)
    {
        $tpl = array();

        PHPWS_Core::initModClass('intern', 'Subject.php');

        $subjects = Subject::getSubjects();

        $faculty = $i->getFacultySupervisor();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;
        $tpl['USER'] = $i->email;
        $tpl['PHONE'] = $i->phone;

        $tpl['TERM'] = Term::rawToRead($i->term, false);
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

        if(isset($i->faculty_supervisor_id)){
            $advisor = $i->getFacultySupervisor();
            $tpl['FACULTY'] = $advisor->getFullName();
        }else{
            $tpl['FACULTY'] = '(not provided)';
        }

        $department = $i->getDepartment();
        $tpl['DEPT'] = $department->getName();

        if($i->international){
            $tpl['COUNTRY'] = $i->loc_country;
            $tpl['INTERNATIONAL'] = 'Yes';
            $intlSubject = '[int\'l] ';
        }else{
            $tpl['STATE'] = $i->loc_state;
            $tpl['INTERNATIONAL'] = 'No';
            $intlSubject = '';
        }

        $to = $i->email . '@appstate.edu';
        $cc = array($faculty->email . '@appstate.edu');
        $subject = 'Your Internship is Approved, Enrollment Complete';

        email::sendTemplateMessage($to, $subject, 'email/RegistrationConfirmation.tpl', $tpl);
    }
}

?>
