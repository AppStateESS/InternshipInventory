<?php
/**
 * This file is part of Internship Inventory.
 *
 * Internship Inventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Internship Inventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License version 3
 * along with Internship Inventory.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2011-2018 Appalachian State University
 */

namespace Intern\Command;
use \phpws2\Database;

/**
 * DeleteInternship Class
 *
 * Controller class for creating an new internship.
 *
 * @author Eric Cambel
 * @package intern
 */
class DeleteInternship {

    public function __construct()
    {

    }

    public function execute()
    {
        // Check permissions
        if(!\Current_User::isDeity()){
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'You do not have permission to delete internships.');
            \NQ::close();
            \PHPWS_Core::home();
        }
        $id = $_REQUEST['internship_id'];
        $internId = array('id'=> $id);

        // Delete all documents relating to internship
        $db = Database::newDB();
        $pdo = $db->getPDO();
        $pdo->beginTransaction();

        try {
            // Delete all documents relating to the internship
            $query = 'DELETE FROM intern_document WHERE internship_id = :id';
            $sth = $pdo->prepare($query);
            $sth->execute($internId);

            // Delete all emergency contacts relating to the internship
            $query = 'DELETE FROM intern_emergency_contact WHERE internship_id = :id';
            $sth = $pdo->prepare($query);
            $sth->execute($internId);

            // Delete all history relating to the internship
            $query = 'DELETE FROM intern_change_history WHERE internship_id = :id';
            $sth = $pdo->prepare($query);
            $sth->execute($internId);


            // Delete the internship
            $query = 'DELETE FROM intern_internship WHERE id = :id';
            $sth = $pdo->prepare($query);
            $sth->execute($internId);

            $pdo->commit();

            // Show a success notice and redirect to the search page.
            \NQ::simple('intern', \Intern\UI\NotifyUI::SUCCESS, "Successfully deleted the internship.");
            \NQ::close();

            return \PHPWS_Core::reroute('index.php?module=intern&action=search');

        } catch(\PDOException $e){
            $pdo->rollBack();

            // Show an error notice and redirect to the edit page.
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, "Could not delete the internship.");
            \NQ::close();

            return \PHPWS_Core::reroute('index.php?module=intern&action=ShowInternship&internship_id=' . $id);
        }
    }
}
