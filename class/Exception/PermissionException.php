<?php
namespace Intern\Exception;

class PermissionException extends \Exception {
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
