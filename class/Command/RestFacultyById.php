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

use Intern\FacultyFactory;
use Intern\FacultyDB;
use Intern\DatabaseStorage;

/**
 * Controller class for manipulating a Faculty member's data through
 * proper REST
 *
 * @author jbooker
 * @package intern
 */
class RestFacultyById {

    public function __construct()
    {

    }

    public function execute()
    {
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->get();
                exit;
            case 'PUT':
                $this->put();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    private function get()
    {
        $id = $_GET['id'];

        if(!isset($id) || $id == '') {
            exit;
        }

        $faculty = FacultyFactory::getFacultyById($id);

        if(empty($faculty)) {
            header('HTTP/1.1 404 Not Found');
            exit;
        }

        echo json_encode($faculty);

        exit;
    }

    private function put()
    {
        //$postarray = json_decode(file_get_contents('php://input'), true);

        // \Canopy\Server is not available in production (yet). Use php://input stream instead for now.
        //$req = \Canopy\Server::getCurrentRequest();
        //$postarray = json_decode($req->getRawData(), true);
        $postarray = json_decode(file_get_contents('php://input'));

        $faculty = new FacultyDB();

        $faculty->setId($postarray->id);
        $faculty->setUsername($postarray->username);
        $faculty->setFirstName($postarray->first_name);
        $faculty->setLastName($postarray->last_name);

        $faculty->setPhone($postarray->phone);
        $faculty->setFax($postarray->fax);

        $faculty->setStreetAddress1($postarray->street_address1);
        $faculty->setStreetAddress2($postarray->street_address2);
        $faculty->setCity($postarray->city);
        $faculty->setState($postarray->state);
        $faculty->setZip($postarray->zip);

        // Save the faculty object
        try {
            DatabaseStorage::saveObject($faculty);
        }
        catch(\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            exit;
        }

        echo json_encode($faculty->extractVars());

        // Exit, since this is called by JSON
        exit;
    }

}
