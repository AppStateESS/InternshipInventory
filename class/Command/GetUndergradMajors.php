<?php
namespace Intern\Command;

class GetUndergradMajors {

    public function execute()
    {
        $majorsList = \Intern\MajorsProviderFactory::getProvider()->getMajors(\Intern\Term::timeToTerm(time()));
        $majorsList = $majorsList->getUndergradMajorsAssoc();

        $majorsList = array('-1' => 'Select Undergraduate Major') + $majorsList;

        echo json_encode($majorsList);
        exit;
    }
}
