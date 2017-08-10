<?php

namespace Intern\WorkflowTransition;
use Intern\WorkflowTransition;
use Intern\Internship;
use Intern\Exception\MissingDataException;
use Intern\InternSettings;

class SigAuthApprove extends WorkflowTransition {
    const sourceState = 'SigAuthReadyState';
    const destState   = 'SigAuthApprovedState';
    const actionName  = 'Approved by Signature Authority';

    public function getAllowedPermissionList()
    {
        return array('sig_auth_approve');
    }

    public function allowed(Internship $i)
    {
        $settings = InternSettings::getInstance();

        // If international certification option is enabled, check status
        if($settings->getRequireIntlCertification()){
            // If international and not certified by OIED, then return false
            if($i->international == 1 && $i->oied_certified != 1){
                return false;
            }
        }

        // Otherwise, check permissions as usual
        $perms = $this->getAllowedPermissionList();
        foreach($perms as $p){
            if(\Current_User::allow('intern', $p)){
                return true;
            }
        }
    }

    public function checkRequiredFields(Internship $i)
    {
        $emergName = $i->getEmergencyContactName();
        if(!isset($emergName)){
            throw new MissingDataException("Please add an emergency contact.");
        }
    }
}
