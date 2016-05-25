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
class Major extends Model
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
}
