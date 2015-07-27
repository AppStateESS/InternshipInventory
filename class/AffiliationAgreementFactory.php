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
        $affilAgree->setBeginDate($result['begin_date']);
        $affilAgree->setEndDate($result['end_date']);
        $affilAgree->setAutoRenew($result['auto_renew']);
        $affilAgree->setNotes($result['notes']);
        $affilAgree->setTerminated($result['terminated']);

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
      if(!isset($agreement) || is_null($agreement))
      {
        throw new InvalidArgumentException('Missing agreement object');
      }

      $db = PdoFactory::getPdoInstance();

      $id = $agreement->getId();





      if(!is_null($id))
      {
        $values = array('saveId' => $id,
                        'saveName' => $agreement->getName(),
                        'saveBeginDate' => $agreement->getBeginDate(),
                        'saveEndDate' => $agreement->getEndDate(),
                        'saveAutoRenew' => (int)$agreement->getAutoRenew(),
                        'saveNotes' => $agreement->getNotes(),
                        'saveTerminated' => $agreement->getTerminated());
        $query = "UPDATE intern_affiliation_agreement
                  SET name = :saveName, begin_date = :saveBeginDate,
                      end_date = :saveEndDate, auto_renew = :saveAutoRenew,
                      notes = :saveNotes, terminated = :saveTerminated
                  WHERE id = :saveId";

      }
      else
      {
        $values = array(
                        'saveName' => $agreement->getName(),
                        'saveBeginDate' => $agreement->getBeginDate(),
                        'saveEndDate' => $agreement->getEndDate(),
                        'saveAutoRenew' => (int)$agreement->getAutoRenew());
        $query = "INSERT INTO intern_affiliation_agreement
                  (id, name, begin_date, end_date, auto_renew)
                  VALUES (nextval('intern_affiliation_agreement_seq'),
                  :saveName, :saveBeginDate, :saveEndDate, :saveAutoRenew)";
      }



      $stmt = $db->prepare($query);


      $stmt->execute($values);

    }

}
