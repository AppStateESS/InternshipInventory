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

    public static function deniedCheck($id) {
        if(is_null($id) || !isset($id)) {
            throw new \InvalidArgumentException('Host ID is required.');
        }

        if($id <= 0) {
            throw new \InvalidArgumentException('Invalid Host ID.');
        }
        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT intern_sub_host.id,
            (SELECT stop_level FROM intern_sub_host
                JOIN intern_special_host ON sub_condition = intern_special_host.id
                WHERE intern_sub_host.id = :id) AS sub_condition,
            (SELECT stop_level FROM intern_sub_host
                JOIN intern_host ON  main_host_id = intern_host.id
                JOIN intern_special_host ON host_condition = intern_special_host.id WHERE intern_sub_host.id = :id) AS host_condition
            FROM intern_sub_host WHERE intern_sub_host.id = :id;");
        $stmt->execute(array('id' => $id));
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $stmt->fetch();

        if($results['host_condition'] == 'Stop' || $results['sub_condition'] == 'Stop'){
            return true;
        }
        return false;
    }

    public static function getMessage($id) {
        if(is_null($id) || !isset($id)) {
            throw new \InvalidArgumentException('Host ID is required.');
        }

        if($id <= 0) {
            throw new \InvalidArgumentException('Invalid Host ID.');
        }

        $db = Database::newDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT host_condition, user_message, email FROM intern_sub_host
            JOIN intern_host ON  main_host_id = intern_host.id
            JOIN intern_special_host ON host_condition = intern_special_host.id
            WHERE intern_sub_host.id = :id;");
        $stmt->execute(array('id' => $id));
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        if (!empty($result)){
                return $result;
        }

        $stmt = $pdo->prepare("SELECT sub_condition, user_message, email FROM intern_sub_host
            JOIN intern_special_host ON sub_condition = intern_special_host.id
            WHERE intern_sub_host.id = :id;");
        $stmt->execute(array('id' => $id));
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    /**
   * Get an associative array of every host in the database.
   * @return Array Associative array of hosts
   */
   public static function getHostAssoc() {
       $db = PdoFactory::getPdoInstance();

       $stmt = $db->prepare("SELECT id, host_name from intern_host WHERE host_approve_flag != 0 ORDER BY host_name ASC");
       $stmt->execute();
       $stmt->setFetchMode(\PDO::FETCH_ASSOC);

       $results = $stmt->fetchAll();

       $hosts = array();

       foreach ($results as $host) {
           $hosts[$host['id']] = $host['host_name'];
       }

       return $hosts;
   }

   /**
    * Get an associative array of every sub host
    * in the database.
    * @return Array Associative array of sub hosts
    */
    public static function getSubHostCond($m_host_id, $state, $country) {
        $db = PdoFactory::getPdoInstance();
        $hosts = array();

        if ($country == 'US') {
          $stmt = $db->prepare("SELECT ish.id, ish.sub_name FROM intern_sub_host AS ish
            LEFT JOIN intern_special_host AS isp ON ish.sub_condition=isp.id
            WHERE ish.state=:state AND ish.main_host_id=:m_host_id AND ish.sub_approve_flag != 0
            AND (ish.sub_condition IS null OR isp.stop_level<>'Stop') ORDER BY ish.sub_name ASC");
          $stmt->execute(array('state' => $state, 'm_host_id' => $m_host_id));
          $stmt->setFetchMode(\PDO::FETCH_ASSOC);

          $results = $stmt->fetchAll();
        } else {
          $stmt = $db->prepare("SELECT ish.id, ish.sub_name FROM intern_sub_host AS ish
            LEFT JOIN intern_special_host AS isp ON ish.sub_condition=isp.id
            WHERE ish.country=:country AND ish.main_host_id=:m_host_id AND ish.sub_approve_flag != 0
            AND (ish.sub_condition IS null OR isp.stop_level<>'Stop') ORDER BY ish.sub_name ASC");
          $stmt->execute(array('country' => $country, 'm_host_id' => $m_host_id));
          $stmt->setFetchMode(\PDO::FETCH_ASSOC);

          $results = $stmt->fetchAll();
        }

        foreach ($results as $host) {
            $hosts[$host['id']] = $host['sub_name'];
        }

        return $hosts;
    }
}
