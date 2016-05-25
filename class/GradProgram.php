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

    /**
     * Return an associative array {id => Grad. Prog. name } for all programs in DB
     * that aren't hidden.
     * @param $except - Always show the major with this ID. Used for students
     *                  with a hidden major. We still want to see it in the select box.
     */
    public static function getGradProgsAssoc($except=null)
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
        $progs = array();
        $progs[-1] = 'Select Graduate Major or Certificate Program';
        $progs += $db->select('col');
        return $progs;
    }
}
