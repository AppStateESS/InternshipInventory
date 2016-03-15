<?php

class AutoLoader {

    public static function loadClass($className) {
        // Strip off the module name, since we're already in that namespace
        $className = preg_replace('/Intern\\\/', '', $className);

        // Convert any remaining backslases to forward slashes
        $className = preg_replace('/\\\/', '/', $className);

        // Add 'class/' to the beginning, and .php to the end
        $className = 'class/' . $className . '.php';
        if(is_file($className)){
            require_once($className);
        }
     }

}

spl_autoload_register(array('AutoLoader', 'loadClass'));
