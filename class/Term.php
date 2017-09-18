<?php

namespace Intern;

/**
 * Utility class for managing Term codes. Holds defines for each term
 * (fall, spring, summer1, summer2), and has helper methods for find the
 * previous/current/next terms. Terms are stored in the 'intern_term' table.
 *
 * NB: This class is distinct from the TermInfo class, which holds term start/end dates.
 * This class is just for managing the term codes (e.g. 201740) that we already have in
 * the local database.
 *
 * @see \Intern\TermInfo
 * @author jbooker
 * @package Intern
 */
class Term {

    public $term;
    public $description;
    public $available_on_timestamp;
    public $census_date_timestamp;
    public $start_timestamp;
    public $end_timestamp;
    public $semester_type; // The type of semester this is. E.g. Fall, Spring, Summer 1, Summer 2. See class constants below.


    // Semester constants. For general "time of year". NB: There can be multiple terms for a given semster.
    const SPRING    = 1;
    const SUMMER1   = 2;
    const SUMMER2   = 3;
    const FALL      = 4;


    public function __construct(string $term, string $description, int $availableOnTimestamp, int $censusDateTimestamp, int $startTimestamp, int $endTimestamp)
    {
        $this->term                     = $term;
        $this->description              = $description;
        $this->available_on_timesamp    = $availableOnTimestamp;
        $this->census_date_timestamp    = $censusDateTimestamp;
        $this->start_timestamp          = $startTimestamp;
        $this->end_timestamp            = $endTimestamp;
    }

    public function getTermCode(): string {
        return $this->term;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getStartTimestamp(): int {
        return $this->start_timestamp;
    }

    public function getStartDateFormatted(): string
    {
        return date('n/h/Y', $this->start_timestamp);
    }

    public function getEndTimestamp(): int {
        return $this->end_timestamp;
    }

    public function getEndDateFormatted(): string
    {
        return date('n/h/Y', $this->end_timestamp);
    }

    public function getSemesterType(): int{
        return $this->semester_type;
    }

    // public function getCSV()
    // {
    //     return array('Term' => Term::rawToRead($this->term, false));
    // }

    /**
     * Converts the database entry of Term into human
     * If $super is true then <sup> tags will be used in 1st and 2nd.
     * readable form. (Ex: 20111 => 'Spring 2011')
     */
    // public static function rawToRead($t, $super=false)
    // {
    //     $semester   = substr($t, strlen($t) - 1, 1);
    //     $year       = substr($t, 0, strlen($t)-1);
    //
    //     switch($semester){
    //         case '1':
    //             return "Spring $year";
    //         case '2':
    //             if($super)
    //                 return "1<sup>st</sup> Summer $year";
    //             else
    //                 return "1st Summer $year";
    //         case '3':
    //             if($super)
    //                 return "2<sup>nd</sup> Summer $year";
    //             else
    //                 return "2nd Summer $year";
    //         case '4':
    //             return "Fall $year";
    //         default:
    //             // Whaattt??
    //             \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'Term error: '.$t);
    //             return "$year";
    //     }
    // }

    /**
     * Figure out if it is time to add new terms to the database.
     * Get lastest term. If it is NOT at least 3 ahead of NOW
     * it's time to add new terms
     */
    // public static function isTimeToUpdate()
    // {
    //     /* Get latest from DB */
    //     $term = new Term();
    //     $db = self::getDb();
    //     $db->addOrder('term desc');
    //     $result = $db->select();
    //
    //     /* Just log if it's an error. User can resume their work.*/
    //     if(\PHPWS_Error::logIfError($result))
    //         return null;// Be quiet.
    //     /*
    //      * If there aren't at least three elements in the result return true.
    //      * This will cause terms to be inserted.
    //      */
    //     if(sizeof($result) < 3)
    //         return true;
    //
    //     /*
    //      * If the CURRENT date/term is greater than the third to newest term/date
    //      * in database then we need to create a new one. This will keep the intern
    //      * module ahead by two terms. That may have been confusing but that's just
    //      * how it works.
    //      */
    //     $currentTerm = self::timeToTerm(time());
    //     $thirdLatest = $result[2];// Third element.
    //
    //     /* Check current vs third to latest. */
    //     return $currentTerm >= $thirdLatest['term'];
    // }

    /**
     * Update term in database.
     * The DB needs to be kept two terms ahead
     * of the current term.
     */
    // public static function doTermUpdate()
    // {
    //     /* Keep inserting next term until there are currentTerm+3 in DB */
    //     while(self::isTimeToUpdate()){
    //         /* Insert new term adjacent to latest one in DB. */
    //         $db = self::getDb();
    //         $db->addOrder('term desc');
    //         $result = $db->select('row');// Get first row (Max).
    //
    //         /* Just log if it's an error. User can resume their work.*/
    //         if(\PHPWS_Error::logIfError($result))
    //             return null;// Be quiet.
    //
    //         if(sizeof($result) == 0){
    //             /* If there is nothing in database insert the current Term! */
    //             $term = new Term();
    //             $term->term = self::timeToTerm(time());
    //             $term->save();
    //         }else{
    //             $termStr = strval($result['term']);
    //             $year = substr($termStr, 0, 4);
    //             $semester = substr($termStr, 4, 1);
    //
    //             /* Increment semester. This just flips back around to 1 if semester is 4. */
    //             $semester = (intval($semester)%4)+1;
    //
    //             /* If new semester is '1' then it's a new year also! */
    //             if($semester == 1){
    //                 //Increment year.
    //                 $year = intval($year)+1;
    //             }
    //
    //             /* Create new term and save it */
    //             $term = new Term();
    //             $term->term = $year.$semester;
    //             $term->save();
    //         }
    //     }
    // }


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
    // public static function timeToTerm($time)
    // {
    //     //TODO These are hard-coded dates for 2017 semesters. Fix this before 2018.
    //     /* Fall 2015:  - Jan 25 */
    //     if($time < 1484370000) {
    //         $term = 20164;
    //     }
    //     /* Spring: Jan 30 - June 2 */
    //     else if($time >= 1485752400 && $time < 1496376000){
    //         $term = 20171;
    //     }
    //     /* Summer 1: June 2 - July 11 */
    //     else if($time >= 1496376000 && $time < 1499745600){
    //         $term = 20172;
    //     }
    //     /* Summer 2: July 11 -  Sep 6 */
    //     else if($time >= 1499745600 && $time < 1504670400){
    //         $term = 20173;
    //     }
    //     /* Fall:  Sep 6 -- Jan  30, 2018*/
    //     else if($time >= 1504670400 || $time < 1517288400){
    //         $term = 20174;
    //     /* Spring 2018: Jan 31 - */
    //     } else if ($time > 1517288400) {
    //         $term = 20181;
    //     }
    //
    //     return $term;
    // }

    // public static function getSemester($term)
    // {
    //     return substr($term, 4, 1);
    // }

    // public static function getNextTerm($term)
    // {
    //     // Grab the year
    //     $year = substr($term, 0, 4);
    //
    //     // Grab the term
    //     $sem = substr($term, 4, 1);
    //
    //     if($sem == self::FALL) {
    //         return ($year + 1) . "1";
    //     } else {
    //         return "$year" . ($sem + 1);
    //     }
    // }
}
