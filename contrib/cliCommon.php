<?php

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
?>
