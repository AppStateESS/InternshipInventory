<?php

namespace Intern\Exception;

/**
 * StudentNotFoundException
 *
 * Exception class to throw when a Student cannot be found in a StudentDataProvider.
 *
 * @author jbooker
 * @package Intern
 */
class StudentNotFoundException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
