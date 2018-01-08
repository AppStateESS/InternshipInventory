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

/**
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/gpl-3.0.html
 */
class State {

    public $abbr;
    public $full_name;
    public $active;

    public function __construct($abbr)
    {
        $db = new \PHPWS_DB('intern_state');
        $db->addWhere('abbr', $abbr);
        $db->loadObject($this);
    }

    public function save()
    {
        $db = new \PHPWS_DB('intern_state');
        $db->addWhere('abbr', $this->abbr);
        return $db->saveObject($this);
    }

    public function setActive($active)
    {
        $this->active = (bool)$active;
    }

    public static function getAllowedStates()
    {
        $db = new \PHPWS_DB('intern_state');
        $db->addWhere('active', 1);
        $db->addColumn('abbr');
        $db->addColumn('full_name');
        $db->setIndexBy('abbr');
        $db->addOrder('full_name ASC');
        $states = $db->select('col');
        if (empty($states)) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'The list of allowed US states for internship locations has not been configured. Please use the administrative options to <a href="index.php?module=intern&action=edit_states">add allowed states.</a>');
            \NQ::close();
            PHPWS_Core::goBack();
        }

        return $states;
    }

    public static function getStates()
    {
        $db = \phpws2\Database::newDB();
        $pdo = $db->getPDO();

        $sql = "SELECT *
                FROM intern_state
                ORDER BY full_name ASC";

        $sth = $pdo->prepare($sql);

        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_CLASS, 'Intern\StateRestored');

        if (empty($result)) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::ERROR, 'The list of allowed US states for internship locations has not been configured. Please use the administrative options to <a href="index.php?module=intern&action=edit_states">add allowed states.</a>');
            \NQ::close();
            PHPWS_Core::goBack();
        }

        $resultState = array();
        foreach($result as $state)
        {
            $resultState[$state->abbr] = $state;
        }

        return $resultState;
    }

    /* http://www.bytemycode.com/snippets/snippet/454/ */
    public static $UNITED_STATES = array(-1 => 'Select State',
            'AL' => "Alabama",
            'AK' => "Alaska",
            'AZ' => "Arizona",
            'AR' => "Arkansas",
            'CA' => "California",
            'CO' => "Colorado",
            'CT' => "Connecticut",
            'DE' => "Delaware",
            'DC' => "District Of Columbia",
            'FL' => "Florida",
            'GA' => "Georgia",
            'HI' => "Hawaii",
            'ID' => "Idaho",
            'IL' => "Illinois",
            'IN' => "Indiana",
            'IA' => "Iowa",
            'KS' => "Kansas",
            'KY' => "Kentucky",
            'LA' => "Louisiana",
            'ME' => "Maine",
            'MD' => "Maryland",
            'MA' => "Massachusetts",
            'MI' => "Michigan",
            'MN' => "Minnesota",
            'MS' => "Mississippi",
            'MO' => "Missouri",
            'MT' => "Montana",
            'NE' => "Nebraska",
            'NV' => "Nevada",
            'NH' => "New Hampshire",
            'NJ' => "New Jersey",
            'NM' => "New Mexico",
            'NY' => "New York",
            'NC' => "North Carolina",
            'ND' => "North Dakota",
            'OH' => "Ohio",
            'OK' => "Oklahoma",
            'OR' => "Oregon",
            'PA' => "Pennsylvania",
            'RI' => "Rhode Island",
            'SC' => "South Carolina",
            'SD' => "South Dakota",
            'TN' => "Tennessee",
            'TX' => "Texas",
            'UT' => "Utah",
            'VT' => "Vermont",
            'VA' => "Virginia",
            'WA' => "Washington",
            'WV' => "West Virginia",
            'WI' => "Wisconsin",
            'WY' => "Wyoming");

}
