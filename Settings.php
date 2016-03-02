<?php

/**
 * The settings class is used to store global settings like the database host username ...
 */
class Settings
{

    /*
     * at this point all the values of the settings are saved that needs to 
     * be set to run the server.
     *
     * There is a get function for every value. That the values can be marked
     * as private to protect them against manipulation.
     */

    //Install credentials
    private static $username = 'test';
    private static $password = 'test';

    //Database settings
    private static $DBhost = 'localhost';
    private static $DBuser = 'root';
    private static $DBname = 'smart';
    private static $DBpassword = 'pi';

    //DOM id settings
    private static $maxIDLength = 100;

    //mobile usage of the site
    private static $mobilSite = true;

    //settings for the access to the program that changes the switches
    //if you are using the default raspberry-remote use default settings
    private static $multiModeAllowed = true;
    private static $pathToSendFile = "/home/pi/smartHome/send/raspberry-remote-master/send";

    //token settings
    //write the number of minutes a token is valid
    private static $validTokenTime = 5;

    /**
     * Returns the time a token is valid in minutes.
     *
     * @return int
     */
    public static function getValidTokenTime()
    {
        return self::$validTokenTime;
    }

    /**
     * Returns a bool whether the system is able to handle multiple actuators for switching on and off.
     *
     * @return boolean
     */
    public static function isMultiModeAllowed()
    {
        return self::$multiModeAllowed;
    }


    /**
     * Returns the path where the program sending the wireless command to the switches is located.
     *
     * @return string
     */
    public static function getPathToSendFile()
    {
        return self::$pathToSendFile;
    }


    /**
     * Returns the path to the db host like localhost.
     *
     * @return string
     */
    public static function getDBHost()
    {
        return self::$DBhost;
    }


    /**
     * Returns the name of the database user.
     *
     * @return string
     */
    public static function getDBuser()
    {
        return self::$DBuser;
    }

    /**
     * Returns the name of the database.
     *
     * @return string
     */
    public static function getDBname()
    {
        return self::$DBname;
    }

    /**
     * Returns the password for the database user.
     *
     * @return string
     */
    public static function getDBpassword()
    {
        return self::$DBpassword;
    }


    /**
     * Returns the maximal length of the dom id�s.
     *
     * @return int
     */
    public static function getMaxIDLength()
    {
        return self::$maxIDLength;
    }

    /**
     * Returns a bool whether a login as a mobile site is allowed
     * (transferring the username and the password as a get variable).
     *
     * @return bool
     */
    public static function isMobilSiteAllowed()
    {
        return self::$mobilSite;
    }

    /**
     * Returns the password for the installation.
     *
     * @return string
     */
    public static function getPassword()
    {
        return self::$password;
    }

    /**
     * Returns the username for the installation.
     *
     * @return string
     */
    public static function getUsername()
    {
        return self::$username;
    }

}