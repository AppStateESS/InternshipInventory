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
        }

        if ($census == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a census date.");
        }

        if ($descr == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a term description.");
        }

        if ($available == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing an available date.");
        }

        if ($start == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a start date.");
        }

        if ($end == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing an end date.");
        }

        if ($type == '') {
            header('HTTP/1.1 500 Internal Server Error');
            echo("Missing a semester type(1-4).");
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();
        //$sql = "SELECT "
    }

    public function get() {

    }

    //function for editing?
}
