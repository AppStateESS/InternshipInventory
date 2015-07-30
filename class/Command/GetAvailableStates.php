<?php
namespace Intern\Command;

use \Intern\State as State;

class getAvailableStates {

    public function execute()
    {
        echo json_encode(State::getAllowedStates());
        exit;
    }
}
