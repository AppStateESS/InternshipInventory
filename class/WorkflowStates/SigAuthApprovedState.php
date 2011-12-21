<?php

class SigAuthApprovedState extends WorkflowState {
    const friendlyName = 'Signature Authority Approved / Pending Dean\'s Approval';
    
    public function getFriendlyName(){
        return self::friendlyName;
    }
    
    public function getName(){
        return 'SigAuthApprovedState';
    }
}

?>