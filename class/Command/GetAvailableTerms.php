<?php
namespace Intern\Command;

use \Intern\Term as Term;
use \Intern\TermProviderFactory;

class GetAvailableTerms {

    public function execute()
    {
        $futureTerms = Term::getFutureTermsAssoc();

        $termProvider = TermProviderFactory::getProvider();

        $terms = array();
        foreach($futureTerms as $term => $description){
            $termInfo = $termProvider->getTerm($term);

            $semester = Term::getSemester($term);
            if($semester == Term::SPRING || $semester == Term::FALL){
                $part = $termInfo->getTermPartByCode('4');
            } else if($semester == Term::SUMMER1){
                $part = $termInfo->getTermPartByCode('SD');
            } else if($semester == Term::SUMMER2){
                $part = $termInfo->getTermPartByCode('SE');
            }

            if($part === null){
                throw new \Exception('Couldn\'t find a part of term for ' . $term);
            }

            $terms[$term] = array('description' => $description, 'startDate' => $part->part_start_date, 'endDate' => $part->part_end_date);
        }

        echo json_encode($terms);
        exit;
    }
}
