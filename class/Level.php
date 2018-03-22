<?php

namespace Intern;

/**
* Level
*
* Represents a level for student.
*
* @author Cydney Caldwell
*/

class Level {
    public $code;
    public $description;
    public $level;

    const UNDERGRAD = 'ugrad';
    const GRADUATE  = 'grad';

    /**
    * Returns this objects database code
    * @return varchar
    */
    public function getCode()
    {
        return $this->code;
    }
    /**
    * Sets this objects database code.
    * @param varchar $code
    */
    public function setCode($code)
    {
        $this->code = $code;
    }
    /**
    * Returns this objects database description
    * @return varchar
    */
    public function getDesc()
    {
        return $this->description;
    }
    /**
    * Sets this objects database description.
    * @param varchar $description
    */
    public function setDesc($description)
    {
        $this->description = $description;
    }
    /**
    * Returns this objects database level
    * @return varchar
    */
    public function getLevel()
    {
        return $this->level;
    }
    /**
    * Sets this objects database level.
    * @param varchar $level
    */
    public function setLevel($level)
    {
        $this->level = $level;
    }
}
