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

namespace Intern\Command;


/**
 * Controller class for getting host search suggestion data in JSON format.
 *
 * @author Cydney Caldwell
 * @package intern
 */
class GetHostSuggestions {

    const tokenLimit        = 2;
    const fuzzyTolerance    = 3;
    const resultLimit       = 10;

    public function __construct(){}

    public function execute(){
        $searchString = $_REQUEST['searchString'];

        // If there was no search string, return an empty array to avoid front-end errors
        if($searchString === ''){
            echo json_encode(array());
            exit;
        }

        // Try a host name lookup and see if we can get an exact match
        $host = $this->hostNameSearch($searchString);
        if($host !== false) {
            echo $this->encodeHosts(array($host));
            exit;
        }

        // Otherwise, try a fuzzy search on all host
        $hosts = $this->fullNameSearch($searchString);
        echo $this->encodeHosts($hosts);
        exit;
    }

    /**
     * Searches for suggestions based on host name. If an exact match is found, then return
     * a host object. Otherwise return false.
     */
    private function hostNameSearch($string){
        $db = \phpws2\Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_host WHERE name = :name");
        $stmt->execute(array('name' => $string));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($result === false) {
            return false;
        }

        $hosts = array();

        return $hosts;
    }

    private function fullNameSearch($string){
        $sql = $this->getFuzzyTextSql($string);
        $results = \PHPWS_DB::getAll($sql);

        $hosts = array();

        return $hosts;
    }

    private function getFuzzyTextSql($searchString){
        // Initialize arrays for constructing db query
        $columnList  = array();
        $orderByList = array();
        $whereGroups = array();

        // Tokenize the passed in string
        $tokenCount = 0;
        $tokens = array();
        $token = strtok($searchString, "\n\t, "); // tokenize on newline, tab, comma, space

        while($token !== false && $tokenCount < self::tokenLimit){
            $tokenCount++;
            $tokens[] = trim(strtolower($token)); // NB: must be lowercase!
            // tokenize on newline, tab, comma, space
            // NB: Don't pass in the string to strtok after the first call above
            $token = strtok("\n\t, ");
        }

        for($i = 0; $i < $tokenCount; $i++){
            // Add column for least value of (lev-distance between token and first name, lev-distance between token and last name)
            $columnList[] = "LEAST(levenshtein('{$tokens[$i]}', name)) as t{$i}_lev";
            // Add WHERE clauses for those fields
            $whereGroups['lev_where'][] = "fuzzy.t{$i}_lev < 3";
            // Add to ORDER BY list
            $orderByList[] = "fuzzy.t{$i}_lev";
        }

        $subQuery = "SELECT *, " . implode(", ", $columnList) . " FROM intern_host";
        $sql = "SELECT name FROM ($subQuery) as fuzzy WHERE (" . implode(' OR ', $whereGroups['lev_where']) . ") OR name ILIKE '%{$tokens[0]}%' ORDER BY " . implode(', ', $orderByList) . " LIMIT " . self::resultLimit;

        return $sql;
    }

    /**
     * Takes an array of host objects and encodes them into a
     * json_encoded string.
     */
    private function encodeHosts(Array $hosts) {
        $hostArray = array();

        // If an empty array was given, just return an empty JSON array
        if(sizeof($hosts) === 0){
            return json_encode($hostArray);
        }

        foreach($hosts as $host) {

            $hostArray[] = array(
                'name' => $host->getHostName(),
                'status' => $host->getStatus()
            );
        }

        return json_encode($hostArray);
    }
}
