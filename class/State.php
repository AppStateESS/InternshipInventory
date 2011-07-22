<?php

/**
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */
class State {

    public $abbr;
    public $fullname;
    public $active;

    public function __construct($abbr)
    {
        $db = new PHPWS_DB('intern_state');
        $db->addWhere('abbr', $abbr);
        return $db->loadObject($this);
    }

    public function save()
    {
        $db = new PHPWS_DB('intern_state');
        $db->addWhere('abbr', $this->abbr);
        return $db->saveObject($this);
    }
    
    public function setActive($active)
    {
        $this->active = (bool)$active;
    }

}

?>
