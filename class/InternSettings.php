<?php


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
        $result = PHPWS_Settings::get('intern', 'registrarEmail');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for Registrar Email address.');
        }
        
        return $result;
    }

    public function getDistanceEdEmail()
    {
        $result = PHPWS_Settings::get('intern', 'distanceEdEmail');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for Distance Ed Email address.');
        }
        
        return $result;
    }

    public function getInternationalRegEmail()
    {
        $result = PHPWS_Settings::get('intern', 'internationalRegEmail');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for International Registrar Email address.');
        }
        
        return $result;
    }

    public function getGraduateRegEmail()
    {
        $result = PHPWS_Settings::get('intern', 'graduateRegEmail');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for Graduate Registrar Email address.');
        }
        
        return $result;
    }

    /**
     * Returns the email address of the Gradudate School approver.
     * 
     * @throws InvalidArgumentException
     * @return string
     */
    public function getGradSchoolEmail()
    {
        $result = PHPWS_Settings::get('intern', 'gradSchoolEmail');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for Graduate School Email address.');
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
        $result = PHPWS_Settings::get('intern', 'internationalOfficeEmail');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for International Office address.');
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
        $result = PHPWS_Settings::get('intern', 'fromEmail');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for send-from Email address.');
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
        $result = PHPWS_Settings::get('intern', 'emailDomain');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for email domain address.');
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
        $result = PHPWS_Settings::get('intern', 'systemName');
        
        if (!isset($result) || is_null($result)) {
            throw new InvalidArgumentException('Missing configuration for email domain address.');
        }
        
        return $result;
    }
}

?>
