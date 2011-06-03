<?php

  /**
   * Major
   *
   * Models an undergraduate major. New majors will be created in future.
   * Other majors may be deleted also, so here's a class for it.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'Model.php');
PHPWS_Core::initModClass('intern', 'Editable.php');
define('MAJOR_EDIT', 'edit_major');
class Major extends Editable
{
    public $name;
    public $hidden;
    
    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_major');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV()
    {
        return array('Undergraduate Major' => $this->name);
    }

    /**
     * @Override Editable::getEditAction
     */
    public static function getEditAction()
    {
        return MAJOR_EDIT;
    }

    /**
     * @Override Editable::getEditPermission
     */
    public static function getEditPermission()
    {
        return 'edit_major';
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
     * Row tags for DBPager
     */
    public function getRowTags()
    {
        $tags = array();
        if($this->isHidden()){
            $tags['NAME'] = "<span id='$this->id' class='$this->id major-prog hidden-major-prog'>$this->name</span>";
        }else{
            $tags['NAME'] = "<span id='$this->id' class='$this->id major-prog'>$this->name</span>";
        }
        // TODO: Make all these JQuery.
        if(Current_User::allow('intern', 'edit_major')){
            $tags['EDIT'] = "<span id='edit-$this->id' class='$this->id edit-major-prog'>Edit</span> | ";
            if($this->isHidden()){
                $tags['HIDE'] = PHPWS_Text::moduleLink('Show', 'intern', array('action' => MAJOR_EDIT, 'hide' => false, 'id'=>$this->getId()));
            }else{
                $tags['HIDE'] = PHPWS_Text::moduleLink('Hide', 'intern', array('action' => MAJOR_EDIT, 'hide' => true, 'id'=>$this->getId()));
            }
        }
        if(Current_User::allow('intern', 'delete_major')){
            $div = null;
            if(isset($tags['HIDE']))
                $div = ' | ';
            $tags['DELETE'] = $div.PHPWS_Text::moduleLink('Delete','intern',array('action'=> MAJOR_EDIT,'del'=>TRUE,'id'=>$this->getID()));
        }
        return $tags;
    }

    /**
     * Return an associative array {id => Major name } for all majors in DB
     * that aren't hidden. Always show the major with id $except.
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
        $majors = $db->select('assoc');
        // Horrible, horrible hacks. Need to add a null selection.
        $majors = array_reverse($majors, true); // preserve keys.
        $majors[-1] = 'None';
        return array_reverse($majors, true);
    }
    
    /**
     * Add a major to DB if it does not already exist.
     */
    public static function add($name)
    {
        $name = trim($name);
        if($name == ''){
            return NQ::simple('intern', INTERN_WARNING, 'No name given for new major. No major was added.');
        }
        /* Search DB for major with matching name. */
        $db = self::getDb();
        $db->addWhere('name', $name);
        if($db->select('count') > 0){
            NQ::simple('intern', INTERN_WARNING, "The major <i>$name</i> already exists.");
            return;
        }

        /* Major does not exist...keep going */
        $major = new Major();
        $major->name = $name;
        try{
            $major->save();
        }catch(Exception $e){
            NQ::simple('intern', INTERN_ERROR, "Error adding major <i>$name</i>.<br/>".$e->getMessage());
            return;
        }

        /* Major was successfully added. */
        NQ::simple('intern', INTERN_SUCCESS, "<i>$name</i> added as undergraduate major.");
    }
}

?>