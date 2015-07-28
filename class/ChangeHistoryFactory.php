<?php

class ChangeHistoryFactory {

    public static function getChangesForInternship(Internship $internship)
    {
        PHPWS_Core::initModClass('intern', 'ChangeHistory.php');

        $db = new PHPWS_DB('intern_change_history');
        $db->addWhere('internship_id', $internship->getId());
        $db->addOrder('timestamp ASC');
        $results = $db->getObjects('ChangeHistory');

        if(PHPWS_Error::logIfError($results)){
            throw new Exception($results->toString());
        }

        return $results;
    }
}
