<?php
namespace Intern\Command;

use \Intern\State as State;

class getStates {

    public function execute()
    {
        $states = State::getStates();

        // If NC is in the array of states, move it to the top
        if(array_key_exists('NC', $states)) {
            // Save the old value
            $nc = $states['NC'];

            // Remove it from the array
            unset($states['NC']);

            // Add it back at the top
            $states = array('NC' => $nc) + $states;
        }

        $obj = new \stdClass();
        $obj->full_name = 'Select a State';
        $obj->active = 1;

        $states = array('-1'=>$obj) + $states;

        echo json_encode($states);
        exit;
    }
}
