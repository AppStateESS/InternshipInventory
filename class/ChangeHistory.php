<?php

namespace Intern;

class ChangeHistory extends Model{

    public $internship_id;
    public $username;
    public $timestamp;
    public $from_state;
    public $to_state;
    public $note;

    /**
     * NB: All of the params are optional for compatibility with db->getObjects() .. Database is done wrong. Don't have time to fix it.
     * @param Internship $i
     * @param PHPWS_User $phpwsUser
     * @param int $timestamp
     * @param WorkflowState $fromState
     * @param WorkflowState $toState
     */
    public function __construct(Internship $i = null, PHPWS_User $phpwsUser = null, $timestamp = null, WorkflowState $fromState = null, WorkflowState $toState = null, $note = null)
    {
        if(!is_null($i)){
            $this->id = 0;
            $this->internship_id = $i->getId();
            $this->username = $phpwsUser->getUsername();
            $this->timestamp = $timestamp;
            $this->from_state = $fromState->getName();
            $this->to_state = $toState->getName();
            $this->note = $note;
        }
    }

    public function getDB(){
        return new PHPWS_DB('intern_change_history');
    }

    public function getCSV()
    {
        return array();
    }
    
    public function getRelativeDate($now = NULL)
    {
        $time = $this->timestamp;
        $curr = !is_null($now) ? $now : time();
        $shift = $curr - $time;
        
        if ($shift < 45){
            $diff = $shift;
            $term = "second";
        }elseif ($shift < 2700){
            $diff = round($shift / 60);
            $term = "minute";
        }elseif ($shift < 64800){
            $diff = round($shift / 60 / 60);
            $term = "hour";
        }else{
            $diff = round($shift / 60 / 60 / 24);
            $term = "day";
        }
        
        if ($diff > 1){
            $term .= "s";
        }
        
        return "$diff $term";
    }
    
    public function getFormattedDate(){
        return date("M j, Y h:i", $this->timestamp);
    }
    
    public function getFromStateFriendlyName()
    {
        $fromState = WorkflowStateFactory::getState($this->from_state);
        return $fromState->getFriendlyName();
    }
    
    public function getToStateFriendlyName()
    {
        $toState = WorkflowStateFactory::getState($this->to_state);
        return $toState->getFriendlyName();
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getNote()
    {
        return $this->note;
    }
}

?>
