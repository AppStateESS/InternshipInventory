<?php

namespace Intern;

class ChangeHistoryFactory {

    public static function getChangesForInternship(Internship $internship)
    {
        $db = new \PHPWS_DB('intern_change_history');
        $db->addWhere('internship_id', $internship->getId());
        $db->addOrder('timestamp ASC');
        $results = $db->getObjects('\Intern\ChangeHistory');

        if(\PHPWS_Error::logIfError($results)){
            throw new \Exception($results->toString());
        }

        return $results;
    }
}

?>
