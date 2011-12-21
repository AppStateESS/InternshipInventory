<?php

class DeanApprovedState extends WorkflowState{
    const friendlyName = 'Dean Approved / Pending Registration';
    
    public function getFriendlyName(){
        return self::friendlyName;
    }
}

?>