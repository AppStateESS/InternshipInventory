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

namespace Intern;
use \phpws2\Database;

class SubHostFactory {

    public static function getSubById($id) {
        if(is_null($id) || !isset($id)) {
            throw new \InvalidArgumentException('Host ID is required.');
        }

        if($id <= 0) {
            throw new \InvalidArgumentException('Invalid Host ID.');
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_sub_host WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Intern\SubHostRestored');

        return $stmt->fetch();
    }

    public static function getMainHostById($id) {
        if(is_null($id) || !isset($id)) {
            throw new \InvalidArgumentException('Host ID is required.');
        }

        if($id <= 0) {
            throw new \InvalidArgumentException('Invalid Host ID.');
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM intern_host WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        return $stmt->fetch();
    }
}
