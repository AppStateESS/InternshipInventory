<?php

namespace Intern\Command;

/**
 * @license http://opensource.org/licenses/lgpl-3.0.html
 * @author Chris Detsch
 */
class ReminderEmails
{

    public function __construct()
    {

    }

    public function execute()
    {
        $provider = \intern\TermProviderFactory::getProvider();
        $terms = array_keys(\intern\Term::getFutureTermsAssoc());
        foreach ($terms as $term) {
            $termInfo = $provider->getTerm($term);
            $censusDate = $termInfo->getCensusDate();
            if(strtotime('+4 weeks') > strtotime($censusDate))
            {
                $termsUnregistered = \intern\InternshipFactory::getUnregisteredInternshipsByTerm($term);
                foreach ($termsUnregistered as $i)
                {
                    $agency = $i->getAgency();
                    \intern\Email::sendReminderEmail($i, $agency, $censusDate);
                }
            }
        }
    }

}
