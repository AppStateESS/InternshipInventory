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
 * Singleton object for storing Internship Inventory Settings
 *
 * @author jbooker
 * @package hms
 */
class InternSettings {

    private static $instance;

    /**
     * Private constructor for singleton pattern
     */
    private function __construct()
    {
    }

    /**
     * Returns as instance of InternSettings
     *
     * @return InternSettings
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new InternSettings();
        }

        return self::$instance;
    }

    /**
     * Returns the email domain (e.g. '@appstate.edu') to use for appending to usernames.
     *
     * @throws InvalidArgumentException
     * @return string
     */
    public function getEmailDomain()
    {
        $result = \PHPWS_Settings::get('intern', 'emailDomain');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for email domain address.');
        }

        return $result;
    }

    /**
    * Returns the email address of the Gradudate School approver.
    *
    * @throws InvalidArgumentException
    * @return string Comma separated list of email addresses
    */
    public function getGradSchoolEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'gradSchoolEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Graduate School Email address.');
        }

        return $result;
    }

    /**
     * Returns list of email addresses to notify when a graduate internship is
     * ready for registration. Can be a comma separated list.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getGraduateRegEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'graduateRegEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Graduate Registrar Email address.');
        }

        return $result;
    }

    /**
     * Returns list of email addresses for distance education. Must be a fully qualified address.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getDistanceEdEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'distanceEdEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Distance Ed Email address.');
        }

        return $result;
    }

    /**
     * Email address to send notification emails from. Must be a fully qualified address.
     *
     * @throws InvalidArgumentException
     * @return string
     */
    public function getEmailFromAddress()
    {
        $result = \PHPWS_Settings::get('intern', 'fromEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for send-from Email address.');
        }

        return $result;
    }

    /**
     * Returns list of email addresses to notify for uncaught exceptions.
     * Can be a comma separated list.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getExceptionEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'uncaughtExceptionEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Uncaught Exception Email address.');
        }

        return $result;
    }

    /**
     * Returns list of email addresses to notify when an undergraduate internship is
     * ready for registration. Can be a comma separated list.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getRegistrarEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'registrarEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Registrar Email address.');
        }

        return $result;
    }

    /**
     * Returns the name background/drug check user email.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getBackgroundCheckEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'backgroundCheckEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for background/drug check address.');
        }

        return $result;
    }

    /**
     * Returns list of email addresses to notify when international internship is
     * ready for registration.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getInternationalRegEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'internationalRegEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for International Registrar Email address.');
        }

        return $result;
    }

    /**
     * Returns the email address of the International Approval office.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getInternationalOfficeEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'internationalOfficeEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for International Office address.');
        }

        return $result;
    }

    /**
     * Returns the email address of whoever is in charge of
     * unusual course number / insurance.
     *
     * @throws InvalidArgumentException
     * @return string Comma separated list of email addresses
     */
    public function getUnusualCourseEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'unusualCourseEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Unusual Course Notification address.');
        }

        return $result;
    }

    /**
     * Returns the friendly name of this system, used for the
     * "from" name in email notifications.
     *
     * @throws InvalidArgumentException
     * @return string
     */
    public function getSystemName()
    {
        $result = \PHPWS_Settings::get('intern', 'systemName');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for email domain address.');
        }

        return $result;
    }

    /**
     * Returns the name of the student data provider to use.
     *
     * @throws InvalidArgumentException
     * @return string - name of the student data provider to use
     */
    public function getStudentDataSource()
    {
        $result = \PHPWS_Settings::get('intern', 'studentDataSource');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for student data source.');
        }

        return $result;
    }
}
