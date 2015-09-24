<?php

namespace Intern;

class WorkflowStateFactory {

    private static $dir = 'WorkflowState';

    public static function getState($className)
    {
        if(!isset($className) || empty($className)){
            throw new \InvalidArgumentException('Missing state name.');
        }

        $className = '\Intern\WorkflowState\\' . $className;

        return new $className;
    }

    public static function getAllStates()
    {
        $dir = PHPWS_SOURCE_DIR . 'mod/intern/class/' . self::$dir;

        // Get the directory listing and filter out anything that doesn't look right
        $files = scandir("{$dir}/");
        $states = array();
        foreach($files as $f){
            // Look for directories that don't start with '.'
            if(!is_dir($dir . '/' . $f) && substr($f, 0, 1) != '.'){
                // Include each one
                \PHPWS_Core::initModClass('intern', self::$dir . '/' . $f);
                $className = preg_replace('/\.php/', '', $f);

                $className = 'Intern\WorkflowState\\' . $className;

                // Instantiate each one
                $states[] = new $className;
            }
        }

        return $states;
    }

    public static function getStatesAssoc()
    {
        $states = self::getAllStates();

        // Sort the states into a reasonable order
        uasort($states, array('self', 'sortStates'));

        $assoc = array();

        foreach($states as $s){
            $assoc[$s->getName()] = $s->getFriendlyname();
        }

        return $assoc;
    }

    /**
     * Call-back function for sorting states by their priority. Lower sort index => lower priority.
     * @param WorkflowState $a
     * @param WorkflowState $b
     */
    private static function sortStates(WorkflowState $a, WorkflowState $b)
    {
        if($a->getSortIndex() == $b->getSortIndex()){
            return 0;
        }
        return ($a->getSortIndex() < $b->getSortIndex()) ? -1 : 1;
    }
}
