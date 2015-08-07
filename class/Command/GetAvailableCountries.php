<?php
namespace Intern\Command;

use \Intern\CountryFactory as CountryFactory;

class getAvailableCountries {

    public function execute()
    {
        $countries = CountryFactory::getCountries();
        $countries = array('-1' => 'Select a Country') + $countries;
        echo json_encode($countries);
        exit;
    }
}
