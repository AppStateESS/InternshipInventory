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
use \phpws2\Database;

class TermRest {

    public function execute() {

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->post();
                exit;
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            case 'PUT':
                $this->put();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    // For adding a term.
    // Need to enter: term_code, census date, description,
    // available date, start date, end date, semester type,
    // undergrad overload hours, grad overload hours.
    public function post() {
        $code = $_REQUEST['code'];
        $census = $_REQUEST['census'];
        $descr = $_REQUEST['descr'];
        $available = $_REQUEST['available'];
        $start = $_REQUEST['start'];
        $end = $_REQUEST['end'];
        $type = $_REQUEST['type'];
        $ugradOver = $_REQUEST['ugradOver'];
        $gradOver = $_REQUEST['gradOver'];


        if ($code == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a term code.");
            exit;
        }
        if ($census == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a census date.");
            exit;
        }
        if ($descr == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a term description.");
            exit;
        }
        if ($available == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing an available date.");
            exit;
        }
        if ($start == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a start date.");
            exit;
        }
        if ($end == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing an end date.");
            exit;
        }
        if ($type == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a semester type.");
            exit;
        }
        if ($ugradOver == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing undergraduate overload hours.");
            exit;
        }
        if ($gradOver == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing graduate overload hours.");
            exit;
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "INSERT INTO intern_term (term, census_date_timestamp,
                description, available_on_timestamp, start_timestamp,
                end_timestamp, semester_type, undergrad_overload_hours,
                grad_overload_hours)
                VALUES (:code, :census, :descr, :available, :start, :end_date,
                :type, :ugradOver, :gradOver)";

        $sth = $pdo->prepare($sql);

        $sth->execute(array('code'=>$code, 'census'=>$census, 'descr'=>$descr,
                      'available'=>$available, 'start'=>$start,
                      'end_date'=>$end, 'type'=>$type, 'ugradOver'=>$ugradOver,
                      'gradOver'=>$gradOver));
    }

    public function get() {

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "SELECT term, description, available_on_timestamp,
                census_date_timestamp, start_timestamp, end_timestamp,
                semester_type, undergrad_overload_hours, grad_overload_hours
                FROM intern_term ORDER BY term DESC";

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function put() {
        $newSemtype = $_REQUEST['newSemtype'];
        $newDesc = $_REQUEST['newDesc'];
        $newCensus = $_REQUEST['newCensus'];
        $newAvail = $_REQUEST['newAvail'];
        $newStart = $_REQUEST['newStart'];
        $newEnd = $_REQUEST['newEnd'];
        $newUgradOver = $_REQUEST['newUgradOver'];
        $newGradOver = $_REQUEST['newGradOver'];
        $tCode = $_REQUEST['tCode'];

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "UPDATE intern_term
                SET semester_type=:newSemtype,
                description=:newDesc, census_date_timestamp=:newCensus,
                available_on_timestamp=:newAvail, start_timestamp=:newStart,
                end_timestamp=:newEnd, undergrad_overload_hours=:newUgradOver,
                grad_overload_hours=:newGradOver
                WHERE term=:tcode";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('newSemtype'=>$newSemtype, 'newDesc'=>$newDesc,
                      'newCensus'=>$newCensus, 'newAvail'=>$newAvail, 'newStart'=>$newStart,
                      'newEnd'=>$newEnd, 'newUgradOver'=>$newUgradOver, 'newGradOver'=>$newGradOver,
                      'tcode'=>$tCode));


    }
}
