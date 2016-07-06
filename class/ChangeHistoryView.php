<?php

namespace Intern;

class ChangeHistoryView {

    private $internship;

    public function __construct(Internship $internship)
    {
        $this->internship = $internship;
    }

    public function show()
    {
        $tpl = array();

        $changes = ChangeHistoryFactory::getChangesForInternship($this->internship);

        if(is_null($changes)){
            return "";
        }

        // Needed for key value in react -> ChangeFields class
        // untill something better can be thought of.
        $id = 0;

        foreach($changes as $change){
            $changeFields = array();
            $changeFields['id'] = $id++;

            $changeFields['relative_date'] = $change->getRelativeDate();
            $changeFields['exact_date'] = $change->getFormattedDate();
            $changeFields['username'] = $change->getUsername();

            if($change->getFromStateFriendlyname() != $change->getToStateFriendlyName()){
                $changeFields['from_state'] = $change->getFromStateFriendlyName();
                $changeFields['to_state'] = $change->getToStateFriendlyName();
            }

            $note = $change->getNote();
            if(!is_null($note)){
                $changeFields['note'] = $note;
            } else {
                $changeFields['note'] = '';
            }

            $tpl[] = $changeFields;
        }

        return $tpl;
    }
}