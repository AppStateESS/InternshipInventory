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

use \PDO;

/**
 * Singleton Factory class for creating PDO objects based on
 * PHPWS database configuration. Somewhat of a wrapper
 * class for our current situation.
 *
 * @author jbooker
 * @package homestead
 */
class PdoFactory {

    static $factory;

    private $pdo;

    /**
     * Returns a PdoFactory instance
     * @return PdoFactory $pdo A PdoInstance object
     */
    public static function getInstance()
    {
        if (!isset(self::$factory)) {
            self::$factory = new PdoFactory();
        }

        return self::$factory;
    }

    /**
     * Returns a PDO object which is connected to the current database
     * @return $pdo A PDO instance, connected to the current DB
     */
    public static function getPdoInstance()
    {
        $pdoFactory = self::getInstance();

        return $pdoFactory->getPdo();
    }


    private function __construct()
    {
        if (!defined('PHPWS_DSN')) {
            throw new Exception('Database connection DSN is not set.');
        }
        $dsnArray = \phpws2\Database::parseDSN(PHPWS_DSN);

        $dsn = $this->createDsn($dsnArray['dbtype'], $dsnArray['dbhost'], $dsnArray['dbname']);

        $this->pdo = new PDO($dsn, $dsnArray['dbuser'], $dsnArray['dbpass'], array(PDO::ATTR_PERSISTENT => true));

        // Make sure PDO will throw exceptions on error
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    private function createDsn($dbType, $host, $dbName)
    {
        return "$dbType:" . ($host != '' ? "host=$host" : '') . ";dbname=$dbName";
    }
}
