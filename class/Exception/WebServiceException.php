<?php
namespace Intern\Exception;

class WebServiceException extends \Exception {
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
