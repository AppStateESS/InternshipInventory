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

    public function getCSV()
    {
        return array('Term' => Term::rawToRead($this->term, false));
    }

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
     * If $super is true then <sup> tags will be used in 1st and 2nd.
     * readable form. (Ex: 20111 => 'Spring 2011')
     */
    public static function rawToRead($t, $super=true)
    {
        $semester = $t[strlen($t)-1];// Get last char
        $year = substr($t, 0, strlen($t)-1);
        switch($semester){
            case '1':
                return "Spring $year";
            case '2':
                if($super)
                    return "1<sup>st</sup> Summer $year";
                else 
                    return "1st Summer $year";
            case '3':
                if($super)
                    return "2<sup>nd</sup> Summer $year";
                else
                    return "2nd Summer $year";
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