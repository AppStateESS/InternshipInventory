<?php

class ChangeHistoryView {

    private $internship;

    public function __construct(Internship $internship)
    {
        $this->internship = $internship;
    }

    public function show()
    {
        $tpl = array();

        $tpl['CHANGELOG_REPEAT'] = array();

        PHPWS_Core::initModClass('intern', 'ChangeHistoryFactory.php');
        $changes = ChangeHistoryFactory::getChangesForInternship($this->internship);

        if(is_null($changes)){
            return "";
        }

        foreach($changes as $change){
            $changeFields = array();

            $changeFields['RELATIVE_DATE'] = $change->getRelativeDate();
            $changeFields['EXACT_DATE'] = $change->getFormattedDate();
            $changeFields['USERNAME'] = $change->getUsername();

            if($change->getFromStateFriendlyname() != $change->getToStateFriendlyName()){
                $changeFields['FROM_STATE'] = $change->getFromStateFriendlyName();
                $changeFields['TO_STATE'] = $change->getToStateFriendlyName();
            }

            $note = $change->getNote();
            if(!is_null($note)){
                $changeFields['NOTE'] = $note;
            }

            $tpl['changelog_repeat'][] = $changeFields;
        }

        return PHPWS_Template::process($tpl, 'intern', 'changeHistory.tpl');
    }
}
