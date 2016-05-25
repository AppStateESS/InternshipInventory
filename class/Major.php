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
}
