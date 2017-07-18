<?php

namespace Intern;

use \PHPWS_Error;
use \PHPWS_DB;
use \Current_User;

/**
 * Model
 *
 * This is the basic model for actors for an internship.
 * Model handles loading/saving the object from the database.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */
abstract class Model {

    public $id;

    /** Get an array that's ready to turn into CSV. * */
    abstract function getCSV();

    /**
     * Constructor. Load the model with given ID.
     */
    public function __construct($id=0)
    {
        if ((int) $id > 0) {
            $this->id = (int) $id;

            $result = $this->load();

            if (!$result) {
                $this->id = 0;
            }
        } else {
            $this->id = 0;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Load the model from the database with matching $this->id.
     */
    public function load()
    {
        if (is_null($this->id) || !is_numeric($this->id))
            return false;

        $db = $this->getDb();
        $db->addWhere('id', $this->id);
        $result = $db->loadObject($this);

        if (PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->toString());
        }

        return $result;
    }

    /**
     * Save model to database
     * @return new ID of model.
     */
    public function save()
    {
        $db = $this->getDb();
        try {
            $result = $db->saveObject($this);
        } catch (\Exception $e) {
            // rethrow any exceptions
            throw $e;
        }

        if (PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->toString());
        }

        return $this->id;
    }

    /**
     * Delete model from database.
     */
    public function delete()
    {
        if (is_null($this->id) || !is_numeric($this->id))
            return false;

        $db = $this->getDb();
        $db->addWhere('id', $this->id);
        $result = $db->delete();

        if (PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->getMessage(), $result->getCode());
        }

        return true;
    }
}
