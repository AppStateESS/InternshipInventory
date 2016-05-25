<?php

namespace Intern;

  /**
   * GradProgram
   *
   * Models a graduate program. New grad programs will need to be created
   * in the future. Other graduate may be deleted also, so here's a class for it.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
class GradProgram extends Model
{
    public $name;
    public $hidden;

    /**
     * @Override Model::getDb
     */
    public static function getDb()
    {
        $db = new \PHPWS_DB('intern_grad_prog');
        return $db;
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        return array('Graduate Program' => $this->name);
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
