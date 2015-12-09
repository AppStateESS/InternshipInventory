<?php
namespace Intern\Exception;

class BannerPermissionException extends \Exception {
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
