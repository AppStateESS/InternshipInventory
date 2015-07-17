<?php

class AffiliationAgreementFactory {

    /**
     * Generates an AffiliationAgreement object by attempting to load the
     * AffiliationAgreement from the database with the given id.
     *
     * @param int $id
     * @returns AffiliationAgreement
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws InternshipNotFoundException
     */
    public static function getAffiliationById($id)
    {
        if(is_null($id) || !isset($id)){
            throw new InvalidArgumentException('AffiliationAgreement ID is required.');
        }

        if($id <= 0){
            throw new InvalidArgumentException('AffiliationAgreement ID must be greater than zero.');
        }

        PHPWS_Core::initModClass('intern', 'AffiliationAgreement.php');
        $db = new PHPWS_DB('intern_affiliation_agreement');
        $db->addWhere('id', $id);

        $result = $db->select('row');

        if(PHPWS_Error::logIfError($result)){
            throw new DatabaseException($result->toString());
        }

        if(count($result) == 0){
            return null;
        }

        $affilAgree = new AffiliationAgreementDB();
        $affilAgree->setId($result['id']);
        $affilAgree->setName($result['name']);
        $affilAgree->setDate($result['begin_date']);
        $affilAgree->setEndDate($result['end_date']);
        $affilAgree->setAutoRenew($result['auto_renew']);
        $affilAgree->setNotes($result['notes']);

        return $affilAgree;
    }

    /**
     * Saves an AffiliationAgreement into the database
     *
     * @param AffiliationAgreement
     * @returns AffiliationAgreement
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws InternshipNotFoundException
     */
    public static function save(AffiliationAgreement $agreement)
    {
        $db = new PHPWS_DB('intern_affiliation_agreement');

        $db->addValue('id', $agreement->getId());
        $db->addValue('name', $agreement->getName());
        $db->addValue('begin_date', $agreement->getBeginDate());
        $db->addValue('end_date', $agreement->getEndDate());
        $db->addValue('auto_renew', $agreement->getAutoRenew());
        $db->addValue('notes', $agreement->getNotes());


        $id = $agreement->getId();
        if(!isset($id) || is_null($id)) {
            $result = $db->insert();
            if(!PHPWS_Error::isError($result)){
                // If everything worked, insert() will return the new database id,
                // So, we need to set that on the object for later
                $agree->setId($result);
            }
        }else{
            $db->addWhere('id', $id);
            $result = $db->update();
        }

        if(PHPWS_Error::logIfError($result)){
            throw new Exception('DatabaseException: Failed to save nomnation.' . $result->toString());
        }
    }

}
