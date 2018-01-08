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
