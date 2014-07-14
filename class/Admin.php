<?php

namespace Intern;

  /**
   * Admin
   *
   * Encapsulates the manaing of granular access by department.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

class Admin extends Model
{
    public $username;
    public $department_id;

    // For DBPager join
    public $department_name; // Department name, when joined to intern_department table
    
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

    public static function currentAllowed($dept)
    {
        \PHPWS_Core::initModClass('users', 'Current_User.php');
        return self::allowed(Current_User::getUsername(), $dept);
    }

    public static function allowed($username, $dept)
    {
        if($dept instanceof Department){
            // User passed Department Obj.
            $dept = $dept->id;
        }

        $db = self::getDb();
        $db->addWhere('username', $username);
        $db->addWhere('department_id', $dept);
        $db->addColumn('id', $count=true);
        $count = $db->select();
        // If 1+ row exists in table then they're allowed.
        if(sizeof($count) >= 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Row tags for DBPager.
     */
    public function rowTags()
    {
        //$d = new Department($this->department_id);
        
        /*
        $link = PHPWS_Text::secureLink('Delete', 'intern', array('action' => 'edit_admins',
                                                                 'del' => true,
                                                                 'username' => $this->username,
                                                                 'department_id' => $this->department_id));
        */
        
        //test($this,1);
        
        return array('USERNAME' => $this->username,
                     'DEPARTMENT' => $this->department_name,
                     'DELETE' => '');
    }
    
    /**
     * Grant user access to search and manage Department.
     */
    public static function add($username, $departmentId)
    {
        if(empty($username)){
            return NQ::simple('intern', INTERN_WARNING, 'No username entered.');
        }
        if($departmentId == -1){
            return NQ::simple('intern', INTERN_WARNING, 'No department selected.');
        }
        // First check that the username passed in is a registered user.
        $db = new PHPWS_DB('users');
        $db->addWhere('username', $username);
        $db->addColumn('id', $count=true);

        if(sizeof($db->select()) == 0){
            // No user exists with that name.
            return NQ::simple('intern', INTERN_ERROR, "No user exists with the name <i>$username</i>. Please choose a valid username.");
        }

        // Deity users automatically see every department. No need to add them to table.
        $db->reset();
        $db->addWhere('username', $username);
        $db->addWhere('deity', true);
        $db->addColumn('id', $count=true);
        if(sizeof($db->select()) >= 1){
            // Is a deity.
            return NQ::simple('intern', INTERN_WARNING, "<i>$username</i> can view all internships in all departments.");
        }
        
        $d = new Department($departmentId);

        // Check if user already has permission.
        if(self::allowed($username, $departmentId)){
            // User permission has already been added.
            return NQ::simple('intern', INTERN_WARNING, "<i>$username</i> can already view internships in <i>$d->name</i>.");
        }

        $ia = new Admin();
        $ia->username = $username;
        $ia->department_id = $departmentId;
        $ia->save();
        NQ::simple('intern', INTERN_SUCCESS, "<i>$username</i> can now view internships for <i>$d->name</i>.");
    }

    /**
     * Remove user's access to Department.
     */
    public static function del($username, $departmentId)
    {
        $d = new Department($departmentId);

        $db = self::getDb();
        $db->addWhere('username', $username);
        $db->addWhere('department_id', $departmentId);
        $db->delete();
        NQ::simple('intern', INTERN_SUCCESS, "<i>$username</i> no longer view internships for <i>$d->name</i>.");
    }

    public static function getAdminPager()
    {
        \PHPWS_Core::initCoreClass('DBPager.php');
        $pager = new DBPager('intern_admin', 'Admin');

        $pager->setModule('intern');
        $pager->setTemplate('admin_pager.tpl');
        $pager->setEmptyMessage('No admins found.');
        $pager->addRowTags('rowTags');
        
        $pager->joinResult('department_id', 'intern_department', 'id', 'name', 'department_name');
        //$pager->db->setTestMode();

        // Set a default order
        if(!isset($_REQUEST['orderby'])){
            $pager->setOrder('department_name');
        }
        
        /***** Row Background Color Toggles ******/
        $pager->addToggle('tablerow-bg-color1');
        $pager->addToggle('tablerow-bg-color2');
        
        // Search
        $pager->setSearch('username');
        
        return $pager->get();
    }

    public static function searchUsers($string)
    {
        $db = new PHPWS_DB('users');
        $db->addWhere('username', "%$string%", 'ILIKE');
        $db->addColumn('username');

        return $db->select('col');
    }
}

?>
