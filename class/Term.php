<?php

  /**
   * Simplest class ever.
   */
PHPWS_Core::initModClass('intern', 'Model.php');
class Term extends Model
{
    public $term;

    public function getDb()
    {
        return new PHPWS_DB('intern_term');
    }

    // TODO: Order by...something?
    public static function getTermsAssoc()
    {
        $db = self::getDb();
        $db->addOrder('term');
        $terms = $db->getObjects('Term');
        $readables = array();

        foreach($terms as $t){
            // Ex. array(20111 => "Spring 2011");
            $readables[$t->term] = self::rawToRead($t->term);
        }

        return $readables;
    }

    /**
     * Converts the database entry of Term into human
     * readable form. (Ex: 20111 => 'Spring 2011')
     */
    public static function rawToRead($t)
    {
        $semester = $t[strlen($t)-1];// Get last char
        $year = substr($t, 0, strlen($t)-1);
        switch($semester){
            case '1':
                return "Spring $year";
            case '2':
                return "1<sup>st</sup> Summer $year";
            case '3':
                return "2<sup>nd</sup> Summer $year";
            case '4':
                return "Fall $year";
            default:
                // Whaattt??
                NQ::simple('intern', INTERN_WARNING, 'Term error: '.$t);
                return "$year";
        }
    }
}

?>