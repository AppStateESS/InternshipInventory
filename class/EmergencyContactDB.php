<?php
namespace Intern;

class EmergencyContactDB extends EmergencyContact {
    public function __construct(){
        // override parent and don't call parent::__construct(), so we can have an empty constructor for loading from DB
    }
}
