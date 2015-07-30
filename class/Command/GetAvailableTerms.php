<?php
namespace Intern\Command;

use \Intern\Term as Term;

class GetAvailableTerms {

    public function execute()
    {
        echo json_encode(Term::getFutureTermsAssoc());
        exit;
    }
}
