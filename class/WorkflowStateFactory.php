<?php

PHPWS_Core::initModClass('intern', 'WorkflowState.php');

class WorkflowStateFactory {
    
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
}