<?php
namespace Intern\Command;

use Intern\DataProvider\Major\MajorsProviderFactory;
use Intern\TermFactory;
use Intern\AcademicMajor;

class GetGraduateMajors {

    public function execute()
    {
        $terms = TermFactory::getAvailableTerms();

        // A bit of a hack regarding the term. There isn't always a single "current" term, so we'll take whatever
        // the first active term is.
        $majorsList = MajorsProviderFactory::getProvider()->getMajors($terms[0]);
        $majorsList = $majorsList->getMajorsByLevel(AcademicMajor::LEVEL_GRADUATE);

        $majorsList = array(array('code'=>'-1', 'description' => 'Select Graduate Major')) + $majorsList;

        echo json_encode($majorsList);
        exit;
    }
}
