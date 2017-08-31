<?php
namespace Intern\Command;

use Intern\WorkflowStateFactory;
use Intern\ChangeHistory;
use Intern\TermProviderFactory;
use Intern\Term;
use Intern\InternshipFactory;
use Intern\Email;

/**
 * @author Chris Detsch
 */
class SendPendingEnrollmentReminders
{

    public function __construct()
    {

    }

    public function execute()
    {
        // Get the list of future terms
        $provider = TermProviderFactory::getProvider();
        $terms = array_keys(Term::getFutureTermsAssoc());

        // Get email settings
        $emailSettings = \Intern\InternSettings::getInstance();

        foreach ($terms as $term) {
            // Get the pending internships for this term
            $pendingInternships = InternshipFactory::getPendingInternshipsByTerm($term);

            // Get the census date for this term
            $termInfo = $provider->getTerm($term);
            $censusDate = $termInfo->getCensusDate();
            $censusTimestamp = strtotime($censusDate);

            // Double check that we have a valid census timestamp. Try to avoid sending emails with the date set to December 31, 1969
            if($censusTimestamp === 0 || $censusTimestamp === '' || $censusTimestamp === null || !isset($censusTimestamp) || empty($censusTimestamp)){
                throw new \InvalidArgumentException("Census timestamp is 0, null, empty, or not set for $term.");
            }

            // Calculate timestamps for 1 week and 4 weeks into the future
            $oneWeekOut = strtotime('+1 week');
            $fourWeeksOut = strtotime('+4 weeks');

            if($oneWeekOut > $censusTimestamp){
                // We're within one week of census
                $withinOneWeek = true;
                echo "Within one week of $term\n\n";
            }else if ($fourWeeksOut > $censusTimestamp){
                // We're more than one week, but less than 4 weeks from census
                $withinOneWeek = false;
                echo "Within four weeks of $term\n\n";
            }else{
                // If we're not within four weeks, then we can skip this term completely
                echo "Not within range of $term\n\n";
                continue;
            }

            // Loop over each pending internship in this term
            foreach ($pendingInternships as $i) {

                // If there is a faculty member, email them.. There may not always be one.
                $faculty = $i->getFaculty();
                $currState = WorkflowStateFactory::getState($i->getStateName());
                if(!is_null($faculty)){
                    if($withinOneWeek){
                        $email = new \Intern\Email\EnrollmentReminderEmail($emailSettings, $i, $censusTimestamp, $faculty->getUsername(), 'FacultyReminderEmail1Week.tpl');
                        $email->send();

                        $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Faculty 1-Week Census Date Reminder Sent');
                    }else{
                        $email = new \Intern\Email\EnrollmentReminderEmail($emailSettings, $i, $censusTimestamp, $faculty->getUsername(), 'FacultyReminderEmail4Weeks.tpl');
                        $email->send();

                        $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Faculty Census Date Reminder Sent');
                    }

                    $ch->save();
                }

                // Email the student
                if($withinOneWeek){
                    $email = new \Intern\Email\EnrollmentReminderEmail($emailSettings, $i, $censusTimestamp, $i->getEmailAddress(), 'StudentReminderEmail1Week.tpl');
                    $email->send();

                    $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Student 1-Week Census Date Reminder Sent');
                }else{
                    $email = new \Intern\Email\EnrollmentReminderEmail($emailSettings, $i, $censusTimestamp, $i->getEmailAddress(), 'StudentReminderEmail4Weeks.tpl');
                    $email->send();

                    $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Student Census Date Reminder Sent');
                }
                $ch->save();
            }
        }
    }

    public static function cliExec(){
        require_once(PHPWS_SOURCE_DIR . 'inc/intern_defines.php');

        \PHPWS_Core::initModClass('users', 'Users.php');
        \PHPWS_Core::initModClass('users', 'Current_User.php');

        $userId = \PHPWS_DB::getOne("SELECT id FROM users WHERE username = 'jb67803'");

        $user = new \PHPWS_User($userId);

        // Auth for production
        $user->auth_script = 'shibbolethnocreate.php';
        $user->auth_name = 'shibbolethnocreate';

        // Auth for local testing. Uncomment for local testing.
        //$user->auth_script = 'local.php';
        //$user->auth_name = 'local';
        
        //$user->login();
        $user->setLogged(true);

        \Current_User::loadAuthorization($user);
        //\Current_User::init($user->id);
        $_SESSION['User'] = $user;

        $obj = new SendPendingEnrollmentReminders();
        $obj->execute();
    }
}
