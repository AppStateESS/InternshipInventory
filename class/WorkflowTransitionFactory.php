<?php

PHPWS_Core::initModClass('intern', 'WorkflowTransition.php');

class WorkflowTransitionFactory {
    
    private static $dir = 'WorkflowTransitions';
    
    public static function getTransitionsFromState(WorkflowState $state, Internship $i)
    {
        $stateName = $state->getName();
        
        $transitions = self::getAllTransitions();
        $outgoingTrans = array();
        
        foreach($transitions as $t){
            // Set the actual source state
            $t->setSourceState($state);
            
            if($t->getSourceState() == $stateName || $t->getSourceState() == '*'){
                if($t->allowed($i)){
                    $outgoingTrans[] = $t;
                }
            }
        }
        
        uasort($outgoingTrans, array('self', 'sortTransitions'));
        
        return $outgoingTrans;
    }
    
    public static function getTransitionByName($name)
    {
        if(!isset($name)){
            throw new InvalidArgumentException('Missing transition name.');
        }
        
        PHPWS_Core::initModClass('intern', self::$dir . '/' . $name . '.php');
        return new $name;
    }
    
    public static function getAllTransitions()
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

    /**
     * Call-back function for sorting transitions by their priority. Lower sort index => lower priority. 
     * @param WorkflowTransition $a
     * @param WorkflowTransition $b
     */
    private static function sortTransitions(WorkflowTransition $a, WorkflowTransition $b)
    {
        if($a->getSortIndex() == $b->getSortIndex()){
            return 0;
        }
        return ($a->getSortIndex() < $b->getSortIndex()) ? -1 : 1;
    }
}

?>