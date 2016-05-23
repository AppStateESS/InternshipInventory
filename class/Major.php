<?php

namespace Intern;

  /**
   * Major
   *
   * Models an undergraduate major. New majors will be created in future.
   * Other majors may be deleted also, so here's a class for it.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
class Major extends Editable
{
    public $name;
    public $hidden;

    /**
     * @Override Model::getDb
     */
    public static function getDb()
    {
        return new \PHPWS_DB('intern_major');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        return array('Undergraduate Major' => $this->name);
    }

    /**
     * Get an empty CSV to fill in fields.
     */
    public static function getEmptyCSV(){
        return array('Undergraduate Major' => '');
    }

    /**
     * @Override Editable::getEditAction
     */
    public static function getEditAction()
    {
        return 'edit_major';
    }

    /**
     * @Override Editable::getEditPermission
     */
    public static function getEditPermission()
    {
        return 'edit_major';
    }

    /**
     * @Override Editable::getDeletePermission
     */
    public static function getDeletePermission()
    {
        return 'delete_major';
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
     * Return an associative array {id => Major name } for all majors in DB
     * that aren't hidden.
     * @param $except - Always show the major with this ID. Used for students
     *                  with a hidden major. We still want to see it in the select box.
     */
    public static function getMajorsAssoc($except=null)
    {
        $db = self::getDb();
        $db->addOrder('name');
        $db->addColumn('id');
        $db->addColumn('name');
        $db->addWhere('hidden', 0, '=', 'OR');

        if(!is_null($except)){
            $db->addWhere('id', $except, '=', 'OR');
        }

        $db->setIndexBy('id');

        $majors = array();
        $majors[-1] = 'Select Undergraduate Major or Certificate Program';
        $majors += $db->select('col');

        return $majors;
    }

    /**
     * Add a major to DB if it does not already exist.
     */
    public static function add($name)
    {
        $name = trim($name);
        if($name == ''){
            return \NQ::simple('intern', \Intern\NotifyUI::WARNING, 'No name given for new major. No major was added.');
        }
        /* Search DB for major with matching name. */
        $db = self::getDb();
        $db->addWhere('name', $name);
        if($db->select('count') > 0){
            \NQ::simple('intern', \Intern\NotifyUI::WARNING, "The major <i>$name</i> already exists.");
            return;
        }

        /* Major does not exist...keep going */
        $major = new Major();
        $major->name = $name;
        $major->hidden = 0;

        try{
            $major->save();
        }catch(Exception $e){
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Error adding major <i>$name</i>.<br/>".$e->getMessage());
            return;
        }

        /* Major was successfully added. */
        \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "<i>$name</i> added as undergraduate major.");
    }
}
