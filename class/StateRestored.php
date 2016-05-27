<?php

namespace Intern;

/**
 * AgencyRestored -- A dummy class with
 * an empty constructor to allow restoring
 * Internship objects from DB. This is necessary
 * until we're running php 5.4.0 and can use
 * ReflectionClass::newInstanceWithoutConstructor.
 */
class StateRestored extends State {

    // Override constructor with empty parameter list
    public function __construct(){}
}
