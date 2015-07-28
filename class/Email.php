<?php

class Email {

    public static function sendTemplateMessage($to, $subject, $tpl, $tags, $cc = null)
    {
        $settings = InternSettings::getInstance();

        $content = PHPWS_Template::process($tags, 'intern', $tpl);

        self::sendEmail($to, $settings->getEmailFromAddress(), $subject, $content, $cc);
    }

    public static function sendEmail($to, $from, $subject, $content, $cc = NULL, $bcc = NULL)
    {
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
    public static function logEmail($message)
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
     * @param Internship $i
     * @param Agency $a
     */
    public static function sendRegistrarEmail(Internship $i, Agency $a)
    {
        PHPWS_Core::initModClass('intern', 'Subject.php');

        $settings = InternSettings::getInstance();

        $subjects = Subject::getSubjects();

        $faculty = $i->getFaculty();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;
        $tpl['USER'] = $i->email;
        $tpl['PHONE'] = $i->phone;

        $term = Term::rawToRead($i->term, false);

        $tpl['TERM'] = $term;
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
            $faculty = $i->getFaculty();
            $tpl['FACULTY'] = $faculty->getFullName() . ' (' . $faculty->getId() . ')';
        }else{
            $tpl['FACULTY'] = '(not provided)';
        }

        $department = $i->getDepartment();
        $tpl['DEPT'] = $department->getName();

        $campus = $i->getCampus();
        if ($campus == 'distance_ed') {
            $tpl['CAMPUS'] = 'Distance Ed';
        } else if ($campus == 'main_campus') {
            $tpl['CAMPUS'] = 'Main campus';
        } else {
            $tpl['CAMPUS'] = $campus;
        }

        /**** Corequisite Checking ****/
        $coreq = $i->getCorequisiteNum();
        if (!is_null($coreq) && $coreq != '') {
            $tpl['COREQ_SUBJECT'] = $subjects[$i->course_subj];
            $tpl['COREQ_COURSE_NUM'] = $coreq;
            $tpl['COREQ_COURSE_SECT'] = $i->getCorequisiteSection();
        }

        /**** International Checking ***/
        if ($i->international) {
            $tpl['COUNTRY'] = $i->loc_country;
            $tpl['INTERNATIONAL'] = 'Yes';
            $intlSubject = '[int\'l] ';
        } else {
            $tpl['STATE'] = $i->loc_state;
            $tpl['INTERNATIONAL'] = 'No';
            $intlSubject = '';
        }

        /**** Multi-part checking ***/
        if ($i->isMultipart() && $i->isSecondaryPart()) {
            $tpl['SECONDARY_PART'] = '';
        }

        /***
         * Figure out who the notification email goes to
        */
        // Send distance ed internship to speedse, per trac #110
        if ($i->isDistanceEd()) {
            $to = $settings->getDistanceEdEmail();

            // Send all international or graduate internships to 'hicksmp', per trac #102
        } else if ($i->isInternational() || $i->isGraduate()) {
            $to = $settings->getGraduateRegEmail();

            // Otherwise, send it to the general Registrar address
        } else {
            $to = $settings->getRegistrarEmail();
        }

        if(!isset($to) || $to == null) {
            throw new InvalidArgumentException('Missing configurating for email addresses (registrar)');
        }

        // CC the faculty members
        if ($faculty instanceof Faculty) {
            $cc = array($faculty->getUsername() . $settings->getEmailDomain());
        } else {
            $cc = array();
        }

        $subject = $term . ' ' . $intlSubject . '[' . $i->getBannerId() . '] ' . $i->getFullName();

        Email::sendTemplateMessage($to, $subject, 'email/RegistrarEmail.tpl', $tpl, $cc);
    }

    /**
     * Sends an email to the grad school office, letting them know there's someone to notify
     *
     * @param Internship $i
     * @param Agency $a
     */
    public static function sendGradSchoolNotification(Internship $i, Agency $a)
    {
        PHPWS_Core::initModClass('intern', 'Subject.php');

        $settings = InternSettings::getInstance();

        $subjects = Subject::getSubjects();

        $faculty = $i->getFaculty();

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

        $to = explode(',', $emails);

        $subject = 'Internship Approval Needed: ' . $intlSubject . '[' . $i->getBannerId() . '] ' . $i->getFullName();

        Email::sendTemplateMessage($to, $subject, 'email/GradSchoolNotification.tpl', $tpl);
    }

    public static function sendIntlInternshipCreateNotice(Internship $i)
    {
        $settings = InternSettings::getInstance();

        $tpl = array();

        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;
        $tpl['USER'] = $i->email;
        $tpl['PHONE'] = $i->phone;

        $tpl['TERM'] = Term::rawToRead($i->term);
        $tpl['COUNTRY'] = $i->loc_country;

        $dept = new Department($i->department_id);
        $tpl['DEPARTMENT'] = $dept->getName();
        $to = $settings->getInternationalOfficeEmail();

        $subject = "International Internship Created - {$i->first_name} {$i->last_name}";

        Email::sendTemplateMessage($to, $subject, 'email/IntlInternshipCreateNotice.tpl', $tpl);
    }

    public static function sendIntlInternshipCreateNoticeStudent(Internship $i)
    {
        $settings = InternSettings::getInstance();

        $tpl = array();

        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;
        $tpl['USER'] = $i->email;
        $tpl['PHONE'] = $i->phone;

        $tpl['TERM'] = Term::rawToRead($i->term);
        $tpl['COUNTRY'] = $i->loc_country;

        $dept = new Department($i->department_id);
        $tpl['DEPARTMENT'] = $dept->getName();
        $to = $i->email . '@appstate.edu';

        $subject = "International Internship Created - {$i->first_name} {$i->last_name}";

        Email::sendTemplateMessage($to, $subject, 'email/IntStudentInternshipOIEDNotice.tpl', $tpl);
    }

    public static function sendInternshipCancelNotice(Internship $i)
    {
        $settings = InternSettings::getInstance();

        $tpl = array();


        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;

        $tpl['TERM'] = Term::rawToRead($i->term);

        $dept = new Department($i->department_id);
        $tpl['DEPARTMENT'] = $dept->getName();

        $to = $i->email . '@appstate.edu';

        $faculty = $i->getFaculty();
        if ($faculty instanceof Faculty) {
            $cc = array($faculty->getUsername() . '@' . $settings->getEmailDomain(), $settings->getRegistrarEmail());
        } else {
            $cc = array();
        }

        $subject = 'Internship Cancelled ' . Term::rawToRead($i->getTerm()) . '[' . $i->getBannerId() . '] ' . $i->getFullName();

        Email::sendTemplateMessage($to, $subject, 'email/StudentCancellationNotice.tpl', $tpl, $cc);
    }

    public static function sendRegistrationConfirmationEmail(Internship $i, Agency $a)
    {
        $settings = InternSettings::getInstance();

        $tpl = array();

        PHPWS_Core::initModClass('intern', 'Subject.php');

        $subjects = Subject::getSubjects();

        $faculty = $i->getFaculty();

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

        if($faculty instanceof Faculty){
            $tpl['FACULTY'] = $faculty->getFullName();
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

        $to = $i->email . $settings->getEmailDomain();
        if ($faculty instanceof Faculty) {
            $cc = array($faculty->getUsername() . $settings->getEmailDomain());
        } else {
            $cc = array();
        }
        $subject = 'Internship Approved';

        email::sendTemplateMessage($to, $subject, 'email/RegistrationConfirmation.tpl', $tpl, $cc);
    }

    /**
     *  Sends the 'Registration Issue' notification email.
     *
     * @param Internship $i
     * @param Agency $agency
     * @param string $note
     */
    public static function sendRegistrationIssueEmail(Internship $i, Agency $agency, $note)
    {
        $tpl = array();

        PHPWS_Core::initModClass('intern', 'Subject.php');
        $subjects = Subject::getSubjects();

        $settings = InternSettings::getInstance();

        $faculty = $i->getFaculty();

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

        if($faculty instanceof Faculty){
            $tpl['FACULTY'] = $faculty->getFullName();
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

        $tpl['NOTE'] = $note;

        $to = $i->email . $settings->getEmailDomain();
        if ($faculty instanceof Faculty) {
            $cc = array($faculty->getUsername() . $settings->getEmailDomain());
        } else {
            $cc = array();
        }

        $subject = 'Internship Enrollment Issue';

        email::sendTemplateMessage($to, $subject, 'email/RegistrationIssue.tpl', $tpl, $cc);
    }
}
