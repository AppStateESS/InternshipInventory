<?php
namespace Intern\Command;

use \Intern\Term as Term;
use \Intern\DataProvider\Term\TermInfoProviderFactory;
use Intern\TermFactory;

/**
 * JSON endpoint for getting begin/end date and census date information for a term.
 * Uses the TermInfoProviderFactory to load TermInfo objects.
 *
 * @author jbooker
 * @package Intern
 */
class GetAvailableTerms {

    public function execute()
    {
        $availableTerms = TermFactory::getAvailableTerms();

        $termList = array();

        foreach ($availableTerms as $term){
            $termList[$term->getTermCode()] = $term;
        }

        echo json_encode($termList);
        exit;
    }
}
