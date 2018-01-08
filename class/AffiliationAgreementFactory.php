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

namespace Intern;

class AffiliationAgreementFactory {

    /**
     * Generates an AffiliationAgreement object by attempting to load the
     * AffiliationAgreement from the database with the given id.
     *
     * @param int $id
     * @return AffiliationAgreement
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws InternshipNotFoundException
     */
    public static function getAffiliationById($id)
    {
        if(is_null($id) || !isset($id)){
            throw new \InvalidArgumentException('AffiliationAgreement ID is required.');
        }

        if($id <= 0){
            throw new \InvalidArgumentException('AffiliationAgreement ID must be greater than zero.');
        }

        $db = new \PHPWS_DB('intern_affiliation_agreement');
        $db->addWhere('id', $id);

        $result = $db->select('row');

        if(\PHPWS_Error::logIfError($result)){
            throw new DatabaseException($result->toString());
        }

        if(count($result) == 0){
            return null;
        }

        $affilAgree = new AffiliationAgreementDb();
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
        if(!isset($agreement) || is_null($agreement)){
            throw new \InvalidArgumentException('Missing agreement object');
        }

        $db = PdoFactory::getPdoInstance();

        $id = $agreement->getId();

        if(is_null($id)) {
            $values = array(
                        'saveName' => $agreement->getName(),
                        'saveBeginDate' => $agreement->getBeginDate(),
                        'saveEndDate' => $agreement->getEndDate(),
                        'saveAutoRenew' => (int)$agreement->getAutoRenew());

            $query = "INSERT INTO intern_affiliation_agreement
                    (id, name, begin_date, end_date, auto_renew)
                    VALUES (nextval('intern_affiliation_agreement_seq'),
                    :saveName, :saveBeginDate, :saveEndDate, :saveAutoRenew)";

        } else {
            $values = array('id' => $id,
                        'name' => $agreement->getName(),
                        'beginDate' => $agreement->getBeginDate(),
                        'endDate' => $agreement->getEndDate(),
                        'autoRenew' => (int)$agreement->getAutoRenew(),
                        'notes' => $agreement->getNotes(),
                        'terminated' => $agreement->getTerminated());

            $query = "UPDATE intern_affiliation_agreement
                        SET name = :name, begin_date = :beginDate,
                        end_date = :endDate, auto_renew = :autoRenew,
                        notes = :notes, terminated = :terminated
                        WHERE id = :id";
        }

        $stmt = $db->prepare($query);

        $stmt->execute($values);
    }

}
