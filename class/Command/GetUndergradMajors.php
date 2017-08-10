<?php
namespace Intern\Command;

use Intern\DataProvider\Major\MajorsProviderFactory;

class GetUndergradMajors {

    public function execute()
    {
        $majorsList = MajorsProviderFactory::getProvider()->getMajors(\Intern\Term::timeToTerm(time()));
        $majorsList = $majorsList->getUndergradMajorsAssoc();

        $majorsList = array('-1' => 'Select Undergraduate Major') + $majorsList;

        echo json_encode($majorsList);
        exit;
    }
}
