<?php

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
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    // for adding a term
    // need to enter: term_code, census date, description,
    // available date, start date, end date, semester type(1 - 4)
    public function post() {
        $code = $_REQUEST['code'];
        $census = $_REQUEST['census'];
        $descr = $_REQUEST['descr'];
        $available = $_REQUEST['available'];
        $start = $_REQUEST['start'];
        $end = $_REQUEST['end'];
        $type = $_REQUEST['type'];

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

        if ($end_date == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing an end date.");
            exit;
        }

        if ($type == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a semester type.");
            exit;
        }

        // Need to add any checks.

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "INSERT INTO intern_term (term, census_date_timestamp,
                description, available_on_timestamp, start_timestamp,
                end_timestamp, semester_type) VALUES
                (:code, :census, :descr, :available, :start, :end_date, :type)";

        $sql = $pdo->prepare($sql);

        $sth->execute(array('code'=>$code, 'census'=>$census, 'descr'=>$descr,
                      'available'=>$available, 'start'=>$start,
                      'end_date'=>$end_date, 'type'=>$type));
        // use intern_term_seq?
    }

    public function get() {

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $sql = "SELECT term, census_date_timestamp, description,
                available_on_timestamp, start_timestamp, end_timestamp,
                semester_type
                FROM intern_term";

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    //function for editing?
}
