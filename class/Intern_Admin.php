<?php

  /**
   * Intern_Admin
   *
   * Encapsulates the manaing of granular access by department.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
PHPWS_Core::initModClass('intern', 'Model.php');

class Intern_Admin extends Model
{
    public $username;
    public $department_id;

    /**
     * @Override Model::getDb
     */
    public function getDb()
    {
        return new PHPWS_DB('intern_admin');
    }

    /**
     * @Override Model::getCSV
     */
    public function getCSV(){}

    public static function allowed($username, $dept)
    {
        if($dept instanceof Department){
            // User passed Department Obj.
            $dept = $dept->id;
        }

        $db = self::getDb();
        $db->addWhere('username', $username);
        $db->addWhere('department_id', $dept);
        $objs = $db->getObjects('Department');
        // If 1+ row exists in table then they're allowed.
        if(sizeof($objs) >= 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Grant user access to search and manage Department.
     */
    public static function add($username, $departmentId)
    {
        $ia = new Intern_Admin();
        $ia->username = $username;
        $ia->department_id = $departmentId;
        $ia->save();
    }


    /**
     * Remove user's access to Department.
     */
    public static function del($username, $departmentId)
    {
        $db = self::getDb();
        $db->addWhere('username', $username);
        $db->addWhere('department_id', $departmentId);
        $db->delete();
    }

    public static function getAdminPager()
    {
        PHPWS_Core::initCoreClass('DBPager.php');
        $pager = new DBPager('intern_admin');

        $pager->setModule('intern');
        $pager->setTemplate('admin_pager.tpl');
        $pager->setEmptyMessage('No admins found.');
        
        $pager->db->addJoin('LEFT OUTER', 'intern_admin', 'intern_department', 'department_id', 'id');
        $pager->db->addColumn('intern_department.name');
        $pager->db->addColumn('intern_admin.username');

        return $pager->get();
    }
}

?>