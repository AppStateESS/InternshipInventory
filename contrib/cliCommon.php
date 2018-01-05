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

/**
 * Description
 * @author Jeff Tickle <jtickle at tux dot appstate dot edu>
 */

function check_args($argc, $argv, &$args, &$switches)
{
    if($argc < count(array_keys($args)) + 1) {
        echo "USAGE: php {$argv[0]}";

        foreach(array_keys($switches) as $switch) {
            echo " [$switch]";
        }

        foreach(array_keys($args) as $arg) {
            echo " <$arg>";
        }

        echo "\n";
        exit();
    }

    $args_keys = array_keys($args);
    foreach($argv as $arg) {
        if($arg == $argv[0]) continue;

        if(in_array($arg, array_keys($switches))) {
            $switches[$arg] = true;
            continue;
        }

        if(substr($arg,0,1) == '-') {
            echo "Ignoring unknown switch: $arg\n";
            continue;
        }

        $args[current($args_keys)] = $arg;
        next($args_keys);
    }
}
