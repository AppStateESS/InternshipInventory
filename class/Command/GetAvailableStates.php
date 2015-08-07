<?php
namespace Intern\Command;

use \Intern\State as State;

class getAvailableStates {

    public function execute()
    {
        $states = State::getAllowedStates();

        // If NC is in the array of states, move it to the top
        if(array_key_exists('NC', $states)) {
            // Save the old value
            $nc = $states['NC'];

            // Remove it from the array
            unset($states['NC']);

            // Add it back at the top
            $states = array('NC' => $nc) + $states;
        }

        $states = array('-1'=>'Select a State') + $states;

        echo json_encode($states);
        exit;
    }
}
