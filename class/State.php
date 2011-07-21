<?php

/**
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */
class State extends Editable {

    public $name;
    public $hidden;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_state');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        return array('State' => $this->name);
    }

    /**
     * Get an empty CSV to fill in fields.
     */
    public static function getEmptyCSV()
    {
        return array('State' => '');
    }

    /**
     * @Override Editable::getEditAction
     */
    public static function getEditAction()
    {
        return STATE_EDIT;
    }

    /**
     * @Override Editable::getEditPermission
     */
    public static function getEditPermission()
    {
        return 'edit_state';
    }

    /**
     * @Override Editable::getDeletePermission
     */
    public static function getDeletePermission()
    {
        return 'delete_state';
    }

    /**
     * @Override Editable::forceDelete
     */
    public function forceDelete()
    {
        exit('Cannot delete states yet');
        if (!Current_User::allow('intern', $this->getDeletePermission())) {
            return NQ::simple('intern', INTERN_ERROR, 'You do not have permission to delete states.');
        }

        PHPWS_Core::initModClass('intern', 'Student.php');
        if ($this->id == 0)
            return;
        $db = Student::getDb();
        $db->addWhere('ugrad_state', $this->id);
        $studs = $db->getObjects('Student');

        // Set each ugrad_state to NULL
        foreach ($studs as $stud) {
            $stud->ugrad_state = null;
            $stud->save();
        }

        // Finally, delete this.
        try {
            $this->delete();
            NQ::simple('intern', INTERN_SUCCESS, "<i>$this->name</i> deleted.");
        } catch (Exception $e) {
            NQ::simple('intern', INTERN_ERROR, $e->getMessage());
            return;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function isHidden()
    {
        return $this->hidden == 1;
    }

    /**
     * Return an associative array {id => State name } for all states in DB
     * that aren't hidden.
     * @param $except - Always show the state with this ID. Used for students
     *                  with a hidden state. We still want to see it in the select box. 
     */
    public static function getStatesAssoc($except=null)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR');

        if (!is_null($except)) {
            $db->addWhere('id', $except, '=', 'OR');
        }
        $states = $db->select('assoc');
        // Horrible, horrible hacks. Need to add a null selection.
        $states = array_reverse($states, true); // preserve keys.
        $states[-1] = 'Select State';
        return array_reverse($states, true);
    }

    /**
     * Add a state to DB if it does not already exist.
     */
    public static function add($name)
    {
        $name = trim($name);
        if ($name == '') {
            return NQ::simple('intern', INTERN_WARNING, 'No name given for new state. No state was added.');
        }
        /* Search DB for state with matching name. */
        $db = self::getDb();
        $db->addWhere('name', $name);
        if ($db->select('count') > 0) {
            NQ::simple('intern', INTERN_WARNING, "The state <i>$name</i> already exists.");
            return;
        }

        /* State does not exist...keep going */
        $state = new State();
        $state->name = $name;
        try {
            $state->save();
        } catch (Exception $e) {
            NQ::simple('intern', INTERN_ERROR, "Error adding state <i>$name</i>.<br/>" . $e->getMessage());
            return;
        }

        /* State was successfully added. */
        NQ::simple('intern', INTERN_SUCCESS, "<i>$name</i> added as a state.");
    }

}

?>
