<?php
namespace Intern;

class TermFactory
{

    /**
     * Get a term object using its term code.
     *
     * @param string $termCode
     * @return Mixed<Term|bool> Returns the requested Term object, or fasle if it doesn't exist in the intern_term table.
     */
    public static function getTermByTermCode(string $termCode) {
        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_term where term = :termCode');
        $stmt->execute(array('termCode' => $termCode));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Intern\TermRestored');

        return $stmt->fetch();
    }

    /**
    * Get an associative array of every term
    * in the database. Looks like: { 201840 => 'Fall 2018' }
    * @return Array Associative array of term codes and their descriptions
    */
    public static function getTermsAssoc(): array
    {
        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_term');
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Intern\TermRestored');

        $results = $stmt->fetchAll();

        $terms = array();

        foreach ($results as $term) {
            $terms[$term->getTermCode()] = $term->getDescription();
        }

        return $terms;
    }

    public static function getNextTerm(Term $term) {

        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * FROM intern_term WHERE start_timestamp > :currentTermStart ORDER BY start_timestamp ASC LIMIT 1');
        $stmt->execute(array('currentTermStart' => $term->getStartTimestamp()));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Intern\TermRestored');

        $result = $stmt->fetch();

        if($result === false){
            return null;
        }

        return $result;
    }

    /**
     * Get an associative array of terms > current term
     * in the database. Looks like: { raw_term => readable_string }
     */
    public static function getFutureTermsAssoc(): array
    {
        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_term where census_date_timestamp > :currentTimestamp ORDER BY term asc');
        $stmt->execute(array('currentTimestamp'=>time()));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Intern\TermRestored');

        $terms = $stmt->fetchAll();

        return $terms;
    }

    public static function getAvailableTerms(): array
    {

        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_term
                                where
                                    extract(epoch from now())::int >= available_on_timestamp AND
                                    extract(epoch from now())::int < census_date_timestamp');
        $stmt->execute();

        $reflection = new \ReflectionClass('Intern\Term');
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Intern\TermRestored');

        $results = $stmt->fetchAll();

        return $results;
    }

    /**
     * Determine if a term exists in the database.
     * Useful for deciding if a future term is "ready" yet
     *
     * @param $targetTerm Term to decide if exists or not
     * @return bool True if the given term eixsts in the database, false otherwise
     */
    public static function termExists(string $targetTermCode): bool
    {
        $terms = self::getTermsAssoc();

        return in_array($targetTerm, array_keys($terms));
    }
}
