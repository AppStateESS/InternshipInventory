<?php
namespace Intern\Command;
/**
 *
 * @author Olivia Perugini
 */
class RequestDrugScreening {

    public function __construct()
    {

    }

    public function execute()
    {

        $i = \Intern\InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        $i->drug_check = 1;
        $agency = $i->getAgency();

        $i->save();

        $email = new \Intern\Email\BackgroundCheckEmail(\Intern\InternSettings::getInstance(), $i, $agency, false, true);
        $email->send();

        //echo json_encode('Success');
        exit;
    }
}
