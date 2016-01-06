<?php

namespace Intern\Command;

/**
 * DeleteInternship Class
 *
 * Controller class for creating an new internship.
 *
 * @author Eric Cambel
 * @package hms
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
        $db = \Database::newDB();
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
