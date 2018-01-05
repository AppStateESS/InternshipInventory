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
 * InternshipRestored -- A dummy class with
 * an empty constructor to allow restoring
 * Internship objects from DB. This is necessary
 * until we're running php 5.4.0 and can use
 * ReflectionClass::newInstanceWithoutConstructor.
 */
class InternshipRestored extends Internship {

    // Override constructor with empty parameter list
    public function __construct(){}
}
