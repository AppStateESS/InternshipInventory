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

        echo json_encode($availableTerms);
        exit;

        //$termProvider = TermInfoProviderFactory::getProvider();

        $terms = array();
        foreach($futureTerms as $term => $description){

            try {
                // Fetch info from web service for this particular term
                $termInfo = $termProvider->getTermInfo($term);
            } catch (\Intern\Exception\BannerPermissionException $e){
                // Catch permission errors
                $error = array('error'=>'You do not have Banner student data permissions. Please click the \'Get Help\' button in the top navigation bar to open a support request.');
                echo json_encode($error);
                exit;
            }


            $part = $termInfo->getLongestTermPart();

            if($part === null){
                // The parts of term may not exist yet, so use the overall term dates instead
                $startDate = $termInfo->getTermStartDate();
                $endDate = $termInfo->getTermEndDate();
            } else {
                // Use the specific term-part dates that were provided for the longest part of term
                $startDate = $part->part_start_date;
                $endDate = $part->part_end_date;
            }

            $terms[$term] = array('description' => $description, 'startDate' => $startDate, 'endDate' => $endDate);
        }

        echo json_encode($terms);
        exit;
    }
}
