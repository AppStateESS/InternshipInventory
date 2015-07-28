<?php

  /**
   * Term...
   */
PHPWS_Core::initModClass('intern', 'Model.php');
class Term extends Model
{
    public $term;

    public static function getDb()
    {
        return new PHPWS_DB('intern_term');
    }

    public function getCSV()
    {
        return array('Term' => Term::rawToRead($this->term, false));
    }

    /**
     * Get an associative array of every term
     * in the database. Looks like: { raw_term => readable_string }
     */
    public static function getTermsAssoc()
    {
        $db = self::getDb();
        $db->addOrder('term desc');
        $terms = $db->getObjects('Term');
        $readables = array();
        $readables[-1] = 'All';

        foreach($terms as $t){
            // Ex. array(20111 => "Spring 2011");
            $readables[$t->term] = self::rawToRead($t->term);
        }

        return $readables;
    }

        /**
     * Get an associative array of terms > current term
     * in the database. Looks like: { raw_term => readable_string }
     */
    public static function getFutureTermsAssoc()
    {
		$currentTerm = self::timeToTerm(time());
		$db = self::getDb();
		$db->addWhere('intern_term.term', $currentTerm, '>=');
		$db->addOrder('term desc');
        $terms = $db->getObjects('Term');
        $readables = array();
        $readables[-1] = 'All';

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
    public static function rawToRead($t, $super=false)
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

    /**
     * Figure out if it is time to add new terms to the database.
     * Get lastest term. If it is NOT at least 3 ahead of NOW
     * it's time to add new terms
     */
    public static function isTimeToUpdate()
    {
        /* Get latest from DB */
        $term = new Term();
        $db = self::getDb();
        $db->addOrder('term desc');
        $result = $db->select();

        /* Just log if it's an error. User can resume their work.*/
        if(PHPWS_Error::logIfError($result))
            return null;// Be quiet.
        /*
         * If there aren't at least three elements in the result return true.
         * This will cause terms to be inserted.
         */
        if(sizeof($result) < 3)
            return true;

        /*
         * If the CURRENT date/term is greater than the third to newest term/date
         * in database then we need to create a new one. This will keep the intern
         * module ahead by two terms. That may have been confusing but that's just
         * how it works.
         */
        $currentTerm = self::timeToTerm(time());
        $thirdLatest = $result[2];// Third element.

        /* Check current vs third to latest. */
        return $currentTerm >= $thirdLatest['term'];
    }

    /**
     * Update term in database.
     * The DB needs to be kept two terms ahead
     * of the current term.
     */
    public static function doTermUpdate()
    {
        /* Keep inserting next term until there are currentTerm+3 in DB */
        while(self::isTimeToUpdate()){
            /* Insert new term adjacent to latest one in DB. */
            $db = self::getDb();
            $db->addOrder('term desc');
            $result = $db->select('row');// Get first row (Max).

            /* Just log if it's an error. User can resume their work.*/
            if(PHPWS_Error::logIfError($result))
                return null;// Be quiet.

            if(sizeof($result) == 0){
                /* If there is nothing in database insert the current Term! */
                $term = new Term();
                $term->term = self::timeToTerm(time());
                $term->save();
            }else{
                $termStr = strval($result['term']);
                $year = substr($termStr, 0, 4);
                $semester = substr($termStr, 4, 1);

                /* Increment semester. This just flips back around to 1 if semester is 4. */
                $semester = (intval($semester)%4)+1;

                /* If new semester is '1' then it's a new year also! */
                if($semester == 1){
                    //Increment year.
                    $year = intval($year)+1;
                }

                /* Create new term and save it */
                $term = new Term();
                $term->term = $year.$semester;
                $term->save();
            }
        }
    }

    /**
     * Given the time $time figure out what
     * term it that time falls into.
     *
     * These ranges for terms are GUESSES.
     * TODO: Might need to add some config
     * view so admins can change them up.
     *
     * Ex. April 9th, 2011 is in 20101 term.
     * @param $time - unix time
     * @return Integer value of term. (Ex. 20101)
     */
    public static function timeToTerm($time)
    {
        $time = getdate($time);
        $term = $time['year'];
        $m = $time['mon'];

        /* Spring: Jan 1 -- April 30 */
        if($m >= 1 && $m <= 4){
            $term .= '1';
        }
        /* Summer 1: May 1 -- June 31 */
        else if($m >= 5 && $m <= 6){
            $term .= '2';
        }
        /* Summer 2: July 1 -- Aug 31 */
        else if($m >= 7 && $m <= 8){
            $term .= '3';
        }
        /* Fall:  Sept 1 -- Dec 31 */
        else if($m >= 9 || $m <= 12){
            $term .= '4';
        }

        return intval($term);
    }
}
