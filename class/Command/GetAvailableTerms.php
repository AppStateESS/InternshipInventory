<?php
namespace Intern\Command;

use \Intern\Term as Term;
use \Intern\TermProviderFactory;

/**
 * JSON endpoint for getting begin/end date and census date information for a term.
 * Uses the TermProviderFactory to load TermInfo objects.
 *
 * @author jbooker
 * @package Intern
 */
class GetAvailableTerms {

    public function execute()
    {
        $futureTerms = Term::getFutureTermsAssoc();

        $termProvider = TermProviderFactory::getProvider();

        $terms = array();
        foreach($futureTerms as $term => $description){
            $termInfo = $termProvider->getTerm($term);

            $part = $termInfo->getLongestTermPart();

            if($part === null){
                throw new \Exception('Couldn\'t find a part of term for ' . $term);
            }

            $terms[$term] = array('description' => $description, 'startDate' => $part->part_start_date, 'endDate' => $part->part_end_date);
        }

        echo json_encode($terms);
        exit;
    }
}
