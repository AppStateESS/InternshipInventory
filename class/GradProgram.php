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
class GradProgram extends Editable
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

    /**
     * Get an empty CSV to fill in fields.
     */
    public static function getEmptyCSV(){
        return array('Graduate Program' => '');
    }

    /**
     * @Override Editable::getEditAction
     */
    public static function getEditAction()
    {
        return edit_grad;
    }

    /**
     * @Override Editable::getEditPermission
     */
    public static function getEditPermission()
    {
        return 'edit_grad_prog';
    }

    /**
     * @Override Editable::getDeletePermission
     */
    public static function getDeletePermission()
    {
        return 'delete_grad_prog';
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

        $progs[-1] = 'Select Graduate Major or Certificate Program';
        $progs += $db->select('col');

        return $progs;
    }
    
    /**
     * Add a program to DB if it does not already exist.
     */
    public static function add($name)
    {
        $name = trim($name);
        if($name == ''){
            return \NQ::simple('intern', INTERN_ERROR, 'No name given for new graduate program. No graduate program added.');
        }

        /* Search DB for program with matching name. */
        $db = self::getDb();
        $db->addWhere('name', $name);
        if($db->select('count') > 0){
            \NQ::simple('intern', INTERN_WARNING, "The graduate program <i>$name</i> already exists.");
            return;
        }

        /* Program does not exist...keep going */
        $prog = new GradProgram();
        $prog->name = $name;
        try{
            $prog->save();
        }catch(Exception $e){
            \NQ::simple('intern', INTERN_ERROR, "Error adding graduate program <i>$name</i>.<br/>".$e->getMessage());
            return;
        }

        /* Program was successfully added. */
        \NQ::simple('intern', INTERN_SUCCESS, "<i>$name</i> added as graduate program.");
    }
}

?>
