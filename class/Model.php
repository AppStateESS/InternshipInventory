<?php

  /**
   * Model
   *
   * This is the basic model for actors for an internship.
   * Model handles loading/saving the object from the database.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

abstract class Model 
{
    public $id;

    abstract function getDb();

    /**
     * Constructor. Load the model with given ID.
     */
    public function __construct($id=0)
    {
        if(!is_null($id) && is_numeric($id)){
            $this->id = $id;
            
            $result = $this->load();

            if(!$result){
                $this->id = 0;
            }
        } else {
            $this->id = 0;
        }
    }

    public function getId(){
        return $this->id;
    }

    /**
     * Load the model from the database with matching $this->id.
     */ 
    public function load()
    {
        if(is_null($this->id) || !is_numeric($this->id))
            return false;

        $db = $this->getDb();
        $db->addWhere('id', $this->id);
        $result = $db->loadObject($this);

        if(PHPWS_Error::logIfError($result)){
            throw new Exception($result->toString());
        }
        
        return $result;
    }

    /**
     * Save model to database 
     * @return - new ID of model.
     */
    public function save()
    {
        $db = $this->getDb();
        $result = $db->saveObject($this);

        if(PHPWS_Error::logIfError($result)){
            if($result->getCode() == DB_ERROR_CONSTRAINT){
                throw new Exception('Already exists in database.');
            }else{
                throw new Exception($result->toString());
            }
        }

        return $result;
    }

    /**
     * Delete model from database.
     */
    public function delete()
    {
        if(is_null($this->id) || !is_numeric($this->id))
            return false;

        $db = $this->getDb();
        $db->addWhere('id', $this->id);
        $result = $db->delete();
        
        if(PHPWS_Error::logIfError($result)){
            throw new Exception($result->toString());
        }

        return true;
    }
}

?>