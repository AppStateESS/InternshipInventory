<?php

class NewState extends WorkflowState {

    const friendlyName = 'New';

    public function getActions()
    {
        return array();
    }
    
    public function getFriendlyName(){
        return self::friendlyName;
    }
    
    public function getName(){
        return 'NewState';
    }
}

?>