<?php

namespace Intern;

class Email {

    public static function sendTemplateMessage($to, $subject, $tpl, $tags, $cc = null)
    {
        $settings = InternSettings::getInstance();

        $content = \PHPWS_Template::process($tags, 'intern', $tpl);

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
                fprintf($fd, "Bcc: %s\n", $recipient);
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
            throw new \InvalidArgumentException('Missing configurating for email addresses (registrar)');
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

    /**
     *  Sends the Background or Drug check notification email.
     *
     * @param Internship $i
     * @param Agency $agency
     */
    public static function sendBackgroundCheckEmail(Internship $i, Agency $agency, $backgroundCheck, $drugCheck)
    {
        $tpl = array();
        $background = '';
        $drugTest = '';

        $settings = InternSettings::getInstance();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;
        $tpl['BIRTHDAY'] = $i->getBirthDateFormatted();
        $tpl['EMAIL'] = $i->getEmailAddress() . $settings->getEmailDomain();
        $tpl['AGENCY'] = $agency->getName();

        if ($backgroundCheck)
            $background = 'Background';

        if ($drugCheck)
            $drugTest = 'Drug';

        if ($backgroundCheck && $drugCheck)
        {
            $subject = 'Internship Background/Drug Check Needed ' . $i->getFullName();
            $tpl['CHECK'] = $background . '/' . $drugTest;
        }else{
            $subject = 'Internship ' . $background . $drugTest . ' Check Needed ' . $i->getFullName();
            $tpl['CHECK'] = $background . $drugTest;
        }

        $to = $settings->getBackgroundCheckEmail();

        email::sendTemplateMessage($to, $subject, 'email/BackgroundDrugCheck.tpl', $tpl);
    }

    /**
     *  Sends the cancellation notification email to OIED.
     *
     * @param Internship $i
     * @param Agency $agency
     */
    public static function sendOIEDCancellationEmail(Internship $i, Agency $agency)
    {
        $tpl = array();

        $settings = InternSettings::getInstance();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;

        $tpl['TERM'] = Term::rawToRead($i->term, false);

        $countries = \Intern\CountryFactory::getCountries();

        $tpl['COUNTRY'] = $countries[$i->loc_country];

        $to = $settings->getInternationalOfficeEmail();
        $subject = 'International Internship Cancellation';

        email::sendTemplateMessage($to, $subject, 'email/OIEDCancellation.tpl', $tpl);
    }

    /**
     *  Sends the  reinstate notification email to OIED.
     *
     * @param Internship $i
     * @param Agency $agency
     */
    public static function sendOIEDReinstateEmail(Internship $i, Agency $agency)
    {
        $tpl = array();

        $settings = InternSettings::getInstance();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->banner;

        $tpl['TERM'] = Term::rawToRead($i->term, false);

        $countries = \Intern\CountryFactory::getCountries();

        $tpl['COUNTRY'] = $countries[$i->loc_country];

        $to = $settings->getInternationalOfficeEmail();
        $subject = 'International Internship Reinstated';

        email::sendTemplateMessage($to, $subject, 'email/OIEDReinstate.tpl', $tpl);
    }

    /**
     *  Sends the OIED certification email to the given faclty member
     *
     * @param Internship $i
     * @param Agency $agency
     */
    public static function sendOIEDCertifiedNotice(Internship $i, Agency $agency)
    {
        $tpl = array();

        $subjects = Subject::getSubjects();

        $settings = InternSettings::getInstance();

        $faculty = $i->getFaculty();

        $tpl = array();
        $tpl['NAME'] = $i->getFullName();
        $tpl['BANNER'] = $i->getBannerId();
        $tpl['TERM'] = Term::rawToRead($i->getTerm(), false);
        $tpl['FACULTY'] = $faculty->getFullName();
        $tpl['AGENCY'] = $agency->getName();


        $to = $faculty->getUsername() . $settings->getEmailDomain();

        $subject = 'OIED Certified Internship';

        email::sendTemplateMessage($to, $subject, 'email/OiedCertifiedNotice.tpl', $tpl);
    }

    /**
     *  Send the census date/enrollment reminder email to the specified group
     *
     * @param Internship $i
     * @param String $censusTimestamp Unix timestamp for census date
     * @param String $toUsername Username of the person to send the message to. Does not include the domain name.
     * @param String $templateFile Filename of the template to use. Assumes the file is in './templates/email/...'
     */
    public static function sendEnrollmentReminderEmail(Internship $i, $censusTimestamp, $toUsername, $templateFile)
    {
        $settings = InternSettings::getInstance();

        $faculty = $i->getFaculty();
        $agency = $i->getAgency();

        $tpl = array();
        $tpl['NAME']    = $i->getFullName();
        $tpl['BANNER']  = $i->getBannerId();
        $tpl['EMAIL']   = $i->getEmailAddress() . $settings->getEmailDomain();
        $tpl['TERM']    = Term::rawToRead($i->getTerm(), false);

        if($i->getSubject()->getId() != 0) {
            $tpl['SUBJECT']     = $i->getSubject()->getName();
        }

        if(!is_null($i->getCourseNumber())){
            $tpl['COURSE_NUM']      = $i->getCourseNumber();
        }

        if(!is_null($i->getCourseSection())){
            $tpl['SECTION']         = $i->getCourseSection();
        }

        if(!is_null($i->getCreditHours())){
            $tpl['CREDITS']         = $i->getCreditHours();
        }

        if($i->isInternational()){
            $tpl['COUNTRY']     = $i->getLocCountry();
        }else{
            $tpl['STATE']       = $i->getLocationState();
        }

        if($i->getStartDate() != 0){
            $tpl['START_DATE']  = $i->getStartDate();
        }

        if($i->getEndDate() != 0){
            $tpl['END_DATE']    = $i->getEndDate();
        }

        if(!is_null($faculty)){
            $tpl['FACULTY']     = $faculty->getFullName();
        }

        $tpl['AGENCY']      = $agency->getName();
        $tpl['CENSUS_DATE'] = date('l, F j, Y', $censusTimestamp);

        $subject = 'Internship Registration Pending';

        // Append domain name to username
        $to = $toUsername . $settings->getEmailDomain();

        // Prepend path to template file name
        $templateFile = 'email/' . $templateFile;

        email::sendTemplateMessage($to, $subject, $templateFile, $tpl);
    }


}
