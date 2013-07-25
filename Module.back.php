<?php

namespace intern;

class Module extends \Module {
    
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        
    }
    
    public function run()
    {
        require $this->directory . 'inc/runtime.php';
    }
    
    public function get()
    {
        require $this->directory . 'index.php';
    }
    
    public function post()
    {
        require $this->directory . 'index.php';
    }
    
    public function getController(\Request $request)
    {
    	
    }
}
?>