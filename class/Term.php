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
        $terms = $db->getObjects('Term');
        $readables = array();

        foreach($terms as $t){
            // Ex. array(20111 => "Spring 2011");
            $readables[$t->term] = self::rawToRead($t);
        }

        return $readables;
    }

    /**
     * Converts the database entry of Term into human
     * readable form. (Ex: 20111 => 'Spring 2011')
     */
    private static function rawToRead(Term $t)
    {
        $t = "$t->term";
        $semester = $t[strlen($t)-1];// Get last char
        $year = substr($t, 0, strlen($t)-1);
        switch($semester){
            case '1':
                return "Spring $year";
                break;
            case '2':
                return "Summer $year";
                break;
            case '3':
                return "Fall $year";
                break;
            default:
                // Whaattt??
                NQ::simple('intern', INTERN_WARNING, 'Term error: '.$t);
                return "$year";
        }
    }
}

?>