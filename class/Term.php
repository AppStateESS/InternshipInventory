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
 * Utility class for managing Term codes. Holds defines for each term
 * (fall, spring, summer1, summer2), and has helper methods for find the
 * previous/current/next terms. Terms are stored in the 'intern_term' table.
 *
 * NB: This class is distinct from the TermInfo class, which holds term start/end dates.
 * This class is just for managing the term codes (e.g. 201740) that we already have in
 * the local database.
 *
 * @see \Intern\TermInfo
 * @author jbooker
 * @package Intern
 */
class Term {

    public $term;
    public $description;
    public $available_on_timestamp;
    public $census_date_timestamp;
    public $start_timestamp;
    public $end_timestamp;
    public $semester_type; // The type of semester this is. E.g. Fall, Spring, Summer 1, Summer 2. See class constants below.
    public $undergrad_overload_hours;
    public $grad_overload_hours;

    // Semester constants. For general "time of year". NB: There can be multiple terms for a given semster.
    const SPRING    = 1;
    const SUMMER1   = 2;
    const SUMMER2   = 3;
    const FALL      = 4;


    public function __construct(string $term, string $description, int $availableOnTimestamp, int $censusDateTimestamp, int $startTimestamp, int $endTimestamp, int $undergradOverloadHours, int $gradOverloadHours)
    {
        $this->term                     = $term;
        $this->description              = $description;
        $this->available_on_timestamp   = $availableOnTimestamp;
        $this->census_date_timestamp    = $censusDateTimestamp;
        $this->start_timestamp          = $startTimestamp;
        $this->end_timestamp            = $endTimestamp;
        $this->undergrad_overload_hours = $undergradOverloadHours;
        $this->grad_overload_hours      = $gradOverloadHours;
    }

    public function getTermCode(): string {
        return $this->term;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getStartTimestamp(): int {
        return $this->start_timestamp;
    }

    public function getStartDateFormatted(): string
    {
        return date('n/j/Y', $this->start_timestamp);
    }

    public function getEndTimestamp(): int {
        return $this->end_timestamp;
    }

    public function getEndDateFormatted(): string
    {
        return date('n/j/Y', $this->end_timestamp);
    }

    public function getSemesterType(): int{
        return $this->semester_type;
    }

    public function getCensusDateTimestamp(): int {
        return $this->census_date_timestamp;
    }

    public function getUndergradOverloadHours(): int {
        return $this->undergrad_overload_hours;
    }

    public function getGradOverloadHours(): int {
        return $this->grad_overload_hours;
    }
}
