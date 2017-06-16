<?php
namespace Intern\Command;

class GetGraduateMajors {

    public function execute()
    {
        $majorsList = \Intern\MajorsProviderFactory::getProvider()->getMajors(\Intern\Term::timeToTerm(time()));
        $majorsList = $majorsList->getGraduateMajorsAssoc();

        $majorsList = array('-1' => 'Select Graduate Major') + $majorsList;

        echo json_encode($majorsList);
        exit;
    }
}
