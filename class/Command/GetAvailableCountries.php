<?php
namespace Intern\Command;

use \Intern\CountryFactory as CountryFactory;

class getAvailableCountries {

    public function execute()
    {
        $countries = CountryFactory::getCountries();
        asort($countries, SORT_STRING);
        unset($countries['US']);
        $countries = array('-1' => 'Select a Country') + $countries;
        echo json_encode($countries);
        exit;
    }
}
