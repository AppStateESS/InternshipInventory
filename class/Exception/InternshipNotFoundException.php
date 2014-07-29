<?php

namespace Intern\Exception;

/**
 * InternshipNotFoundException
 *
 * Exception class to throw when an internship cannot be located in the database.
 *
 * @author jbooker
 * @package Intern
 */
class InternshipNotFoundException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}

?>