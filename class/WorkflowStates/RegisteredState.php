<?php

class RegisteredState extends WorkflowState {
    const friendlyName = 'Registered';
    
    public function getFriendlyName(){
        return self::friendlyName;
    }
}

?>