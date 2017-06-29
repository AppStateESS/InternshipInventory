<?php
namespace Intern\Command;

/**
 *
 * @author Olivia Perugini
 */
class RequestBackgroundCheck {

    public function __construct()
    {

    }

    public function execute()
    {

        $i = \Intern\InternshipFactory::getInternshipById($_REQUEST['internship_id']);
        $i->background_check = 1;
        $i->agency = getAgency();

        $i->save();

        $email = new \Intern\Email\BackgroundCheckEmail(\Intern\InternSettings::getInstance(), $i, $agency, true, false);
        $email->send();

        //needed without DOM
        //return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $i->id);

    }
}
