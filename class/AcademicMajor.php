<?php

namespace Intern;

/**
 * Represents an academic major from an external source.
 * @author Jeremy Booker
 * @package Intern
 */
class AcademicMajor {

    private $code;
    private $description;

    public function __construct($code, $description)
    {
        $this->code = $code;
        $this->description = $description;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
