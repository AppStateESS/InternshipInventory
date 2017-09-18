<?php
namespace Intern;

class TermFactory
{

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
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Term');

        $results = $stmt->fetchAll();

        $terms = array();

        foreach ($results as $term) {
            $terms[$term->getTermCode()] = $term->getDescription();
        }

        return $terms;
    }

    /**
     * Get an associative array of terms > current term
     * in the database. Looks like: { raw_term => readable_string }
     */
    public static function getFutureTermsAssoc(string $baseTerm): array
    {
        $db = PdoFactory::getPdoInstance();

        $stmt = $db->prepare('SELECT * from intern_term where term > :currentTerm ORDER BY term asc');
        $stmt->execute(array('currentTerm'=>$baseTerm));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Term');

        $results = $stmt->fetchAll();

        $terms = array();

        foreach ($results as $term) {
            $terms[$term->getTermCode()] = $term->getDescription();
        }

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
