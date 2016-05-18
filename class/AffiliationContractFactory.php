<?php
namespace Intern;

class AffiliationContractFactory {

    /**
     * Saves an AffiliationAgreement into the database
     *
     * @param AffiliationAgreement
     * @returns AffiliationAgreement
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws InternshipNotFoundException
     */
    public static function save(AffiliationContract $agreement)
    {
        if(!isset($agreement) || is_null($agreement)){
            throw new \InvalidArgumentException('Missing agreement object');
        }

        $db = PdoFactory::getPdoInstance();

        $values = array('agreementId' => $agreement->getAgreementId(), 'documentId' => $agreement->getDocumentId());

        $query = "INSERT INTO intern_agreement_documents
                    (agreement_id, document_id)
                    VALUES (:agreementId,:documentId)";

        $stmt = $db->prepare($query);

        $stmt->execute($values);
    }

    /**
     * Delete row from database that matches this object's $id.
     * Also, delete the associated document in filecabinet.
     */
    public static function deleteByDocId($docId)
    {
        if(!isset($docId) || is_null($docId)){
          throw new \InvalidArgumentException('Missing document Id');
        }

        //Delete from intern_agreement_documents
        $db = PdoFactory::getPdoInstance();

        $values = array('documentId' => $docId);
        $query = "DELETE FROM intern_agreement_documents
                  WHERE document_id = :documentId";

        $stmt = $db->prepare($query);

        $stmt->execute($values);

        //Once its deleted from intern_agreement_documents move to
        //deleting from the phpws document database.

        $values = array('documentId' => $docId);

        $query = "DELETE FROM documents
                  WHERE id = :documentId";


        $stmt = $db->prepare($query);

        $stmt->execute($values);
    }

}
