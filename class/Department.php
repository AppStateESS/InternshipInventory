<?php

  /**
   * Model
   *
   * Represents an academic department at Appalachian State University.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Model.php');

class Department extends Model
{
    public $name;

    /**
     * @Override Model::getDb
     */
    public function getDb(){
        return new PHPWS_DB('intern_department');
    }
}

?>