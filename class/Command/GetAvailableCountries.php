<?php
namespace Intern\Command;

use \Intern\CountryFactory as CountryFactory;

class getAvailableCountries {

    public function execute()
    {
        echo json_encode(CountryFactory::getCountries());
        exit;
    }
}
