<?php
class SigAuthReadyState extends WorkflowState {
    const friendlyName = 'Ready for Signature Authority Approval';
    
    public function getFriendlyName(){
        return self::friendlyName;
    }
}

?>