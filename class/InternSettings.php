<?php

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

    public function getRegistrarEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'registrarEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Registrar Email address.');
        }

        return $result;
    }

    public function getDistanceEdEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'distanceEdEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for Distance Ed Email address.');
        }

        return $result;
    }

    public function getInternationalRegEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'internationalRegEmail');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for International Registrar Email address.');
        }

        return $result;
    }

    /**
     * Returns list of email addresses to notify when a graduate internship is
     * ready for registration. NB: Can be a comma separated list.
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
     * Returns the email address of the International Approval office.
     *
     * @throws InvalidArgumentException
     * @return string
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
     * Returns the email domain (e.g.
     * '@appstate.edu') to use for appending to usernames.
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
     * Returns the email address of whoever is in charge of
     * unusual course number / insurance.
     *
     * @throws InvalidArgumentException
     * @return string
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
     * Returns true if the background check request button should be enabled. False by default or if setting doesn't exist.
     *
     * @return boolean True if the background check request button should be enabled, false otherwise.
     */
    public function getBackgroundCheckRequestEnabled()
    {
        $result = \PHPWS_Settings::get('intern', 'backgroundCheckRequestEnabled');

        if (!isset($result) || is_null($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Returns true if the drug check request button should be enabled. False by default or if the setting doesn't exist.
     *
     * @return boolean True if the drug check request button should be enabled, false otherwise.
     */
    public function getDrugCheckRequestEnabled()
    {
        $result = \PHPWS_Settings::get('intern', 'drugCheckRequestEnabled');

        if (!isset($result) || is_null($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Returns the name background/drug check user email.
     *
     * @throws InvalidArgumentException
     * @return string - Comma separated list of email addresses
     */
    public function getBackgroundCheckEmail()
    {
        $result = \PHPWS_Settings::get('intern', 'backgroundCheckEmail');

        if (!isset($result) || is_null($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Returns true if multiple campuses are allowed (i.e. main campus and distance ed.) Default false.
     *
     * @return boolean True if multiple campuses are allowed (i.e. main campus and distance ed), false otherwise.
     */
    public function getMultiCampusEnabled()
    {
        $result = \PHPWS_Settings::get('intern', 'multiCampusEnabled');

        if (!isset($result) || is_null($result)) {
            return false;
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

    public function getGenerateContractEnabled(){
        $result = \PHPWS_Settings::get('intern', 'generateContractEnabled');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for generate contract enabled.');
        }

        return $result;
    }

    public function getRequireIntlCertification()
    {
        $result = \PHPWS_Settings::get('intern', 'requireIntlCertification');

        if (!isset($result) || is_null($result)) {
            throw new \InvalidArgumentException('Missing configuration for requiring international certification.');
        }

        return $result;
    }
}
