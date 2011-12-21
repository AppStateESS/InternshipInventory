<?php
class CancelledState extends WorkflowState {
    const friendlyName = 'Cancelled';
    
    public function getFriendlyName(){
        return self::friendlyName;
    }
    
    public function getName(){
        return 'CancelledState';
    }
}
?>