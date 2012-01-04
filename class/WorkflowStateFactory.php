<?php

PHPWS_Core::initModClass('intern', 'WorkflowState.php');

class WorkflowStateFactory {
    
    private static $dir = 'WorkflowStates';
    
    public static function getState($className)
    {
        if(!isset($className) || empty($className)){
            throw new InvalidArgumentException('Missing state name.');
        }
        
        $fileName = $className . '.php';
        
        try{
            PHPWS_Core::initModClass('intern', '/WorkflowStates/' . $fileName);
        }catch(Exception $e){
            throw new Exception('Invalid state name.');
        }
        
        return new $className;
    }
    
    public static function getAllStates()
    {
        $dir = PHPWS_SOURCE_DIR . 'mod/intern/class/' . self::$dir;
        
        // Get the directory listing and filter out anything that doesn't look right
        $files = scandir("{$dir}/");
        $transitions = array();
        foreach($files as $f){
            // Look for directories that don't start with '.'
            if(!is_dir($dir . '/' . $f) && substr($f, 0, 1) != '.'){
                // Include each one
                PHPWS_Core::initModClass('intern', self::$dir . '/' . $f);
                $className = preg_replace('/\.php/', '', $f);
        
                // Instanciate each one
                $transitions[] = new $className;
            }
        }
        
        return $transitions;
    }
    
    public static function getStatesAssoc()
    {
        $states = self::getAllStates();
        
        $assoc = array();
        
        foreach($states as $s){
            $assoc[$s->getName()] = $s->getFriendlyname();
        }
        
        return $assoc;
    }
}