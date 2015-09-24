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
    private $level;

    public function __construct($code, $description, $level)
    {
        $this->code = $code;
        $this->description = $description;
        $this->level = $level;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLevel()
    {
        return $this->level;
    }
}
