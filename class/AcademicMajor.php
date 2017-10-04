<?php

namespace Intern;

/**
 * Represents an academic major from an external source.
 * @author Jeremy Booker
 * @package Intern
 */
class AcademicMajor {

    // Fields must be public for json_encode()
    public $code;
    public $description;
    public $level;

    const LEVEL_UNDERGRAD   = 'U';
    const LEVEL_GRADUATE    = 'G';

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
