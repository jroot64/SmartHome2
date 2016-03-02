<?php

/**
 * The class InitJSObjects is used to initiate all JS objects, that where used on
 * the webpage, in the same way. It also should optimize the loding of the webpage
 * because all the initiation stuff is done after the graphical elements are loaded.
 */
class InitJSObjects
{

    private static $instance;
    private $jsObjectCode;


    /**
     * The function __construct has no real things to do.
     */

    private function __construct()
    {

    }


    /**
     * The public static function InitObject in combination with the singleton
     * ability of the class gives the possibility to add an initiation of an JS
     * Object from all point of the code without having one instance given from
     * function to function.
     *
     * @param $id
     * @param $class
     * @param $parameter
     */
    public static function initObject($id, $class, $parameter)
    {
        $initJSObjectInstance = self::getInstance();

        $initJSObjectInstance->addJSObjectCode($id, $class, $parameter);
    }
    
    
    /**
     * The private static function getInstance gives the ability to call a public 
     * static function which works everytime ist was called in the same instance
     */

    private static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * Internal function to handle an call of the add function. It generates
     * the initiation javascript for the object and adds it to the jsObjectCode
     * variable that contains the preadded js initiation code.
     *
     * @param $id
     * @param $class
     * @param $parameter
     */

    private function addJSObjectCode($id, $class, $parameter)
    {

        $initObject = "var $id = new $class($parameter);";
        $this->jsObjectCode .= $initObject;

    }
    
    
    /**
     * The function is called at the end of the index.php in the main directory.
     * It returns the js code which initiates all js objects that where added 
     * during the creation of the webpage. 
     */

    public static function getJSinitScript()
    {
        $initJSObjectInstance = self::getInstance();

        return "<script>" . $initJSObjectInstance->jsObjectCode . "</script>";
    }
}