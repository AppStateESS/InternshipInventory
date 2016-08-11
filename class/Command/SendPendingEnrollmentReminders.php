<?php
namespace Intern\Command;

use Intern\WorkflowStateFactory;
use Intern\ChangeHistory;

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
        $provider = \intern\TermProviderFactory::getProvider();
        $terms = array_keys(\intern\Term::getFutureTermsAssoc());

        foreach ($terms as $term) {
            // Get the pending internships for this term
            $pendingInternships = \intern\InternshipFactory::getPendingInternshipsByTerm($term);

            // Get the census date for this term
            $termInfo = $provider->getTerm($term);
            $censusDate = $termInfo->getCensusDate();
            $censusTimestamp = strtotime($censusDate);

            // Calculate timestamps for 1 week and 4 weeks into the future
            $oneWeekOut = strtotime('+1 week');
            $fourWeeksOut = strtotime('+4 weeks');

            if($oneWeekOut > $censusTimestamp){
                // We're within one week of census
                $withinOneWeek = true;
            }else if ($fourWeeksOut > $censusTimestamp){
                // We're more than one week, but less than 4 weeks from census
                $withinOneWeek = false;
            }else{
                // If we're not within four weeks, then we can skip this term completely
                continue;
            }

            // Loop over each pending internship in this term
            foreach ($pendingInternships as $i) {

                // If there is a faculty member, email them.. There may not always be one.
                $faculty = $i->getFaculty();
                $currState = WorkflowStateFactory::getState($i->getStateName());
                if(!is_null($faculty)){
                    if($withinOneWeek){
                        \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $faculty->getUsername(), 'FacultyReminderEmail1Week.tpl');
                        $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Faculty 1-Week Census Date Reminder Sent');
                    }else{
                        \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $faculty->getUsername(), 'FacultyReminderEmail4Weeks.tpl');
                        $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Faculty Census Date Reminder Sent');
                    }

                    $ch->save();
                }

                // Email the student
                if($withinOneWeek){
                    \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $i->getEmailAddress(), 'StudentReminderEmail1Week.tpl');
                    $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Student 1-Week Census Date Reminder Sent');
                }else{
                    \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $i->getEmailAddress(), 'StudentReminderEmail4Weeks.tpl');
                    $ch = new ChangeHistory($i, null, time(), $currState, $currState, 'Student Census Date Reminder Sent');
                }
                $ch->save();
            }
        }
    }

}
