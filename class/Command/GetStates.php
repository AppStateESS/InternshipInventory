<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

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
