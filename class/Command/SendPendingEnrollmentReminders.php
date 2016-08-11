<?php
namespace Intern\Command;

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

            // Get the dates for this term
            $termInfo = $provider->getTerm($term);
            $censusDate = $termInfo->getCensusDate();

            $censusTimestamp = strtotime($censusDate);

            // If we're within one week of census date, then send the 1-week warning
            if(strtotime('+1 week') > $censusTimestamp){
                foreach ($pendingInternships as $i) {

                    // If there is a faculty member, email them.. There may not always be one.
                    $faculty = $i->getFaculty();
                    if(!is_null($faculty)){
                        \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $faculty->getUsername(), 'FacultyReminderEmail1Week.tpl');
                    }

                    // Email the student
                    \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $i->getEmailAddress(), 'StudentReminderEmail1Week.tpl');
                }

            // Otherwise, if now+4weeks is after the census date
            } else if (strtotime('+4 weeks') > $censusTimestamp) {

                foreach ($pendingInternships as $i) {

                    // If there is a faculty member, email them.. There may not always be one.
                    $faculty = $i->getFaculty();
                    if(!is_null($faculty)){
                        \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $faculty->getUsername(), 'FacultyReminderEmail4Weeks.tpl');
                    }

                    // Email the student
                    \intern\Email::sendEnrollmentReminderEmail($i, $censusTimestamp, $i->getEmailAddress(), 'StudentReminderEmail4Weeks.tpl');
                }
            }
        }
    }

}
