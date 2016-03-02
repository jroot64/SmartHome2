<?php

/**
 *
 */
//TODO add commend eventhandler class
/**
 * Class EventHandler
 */
class EventHandler
{

    private static $instance;
    private $jsEventCode;


    /**
     * The function __construct has no real things to do.
     */

    private function __construct()
    {

    }


    /**
     * The function newEvent expects the DOM id function and the event to initiate the
     * event handler.
     *
     * @param $id
     * @param $function
     * @param $event
     */

    public static function newEvent($id, $function, $event)
    {
        $eventInstance = self::getInstance();

        $eventInstance->addEvent($id, $function, $event);
    }


    /**
     * The function getInstance adds the ability of a singleton class that makes it possible
     * to call a function from every point of the code and to stay in the same instance.
     *
     * @return EventHandler
     */

    private static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * The private addEvent function builds an js code that adds an event listener.
     *
     * @param $id
     * @param $function
     * @param $event
     */

    private function addEvent($id, $function, $event)
    {
        $newEvent = "document.getElementById('$id').addEventListener( '$event' , $function );";
        $this->jsEventCode .= $newEvent;
    }


    /**
     * The public function getJSEventCode returns a string that contains the generated js code.
     *
     * @return string
     */

    public static function getJSEventCode()
    {
        $eventInstance = self::getInstance();

        return "<script>" . $eventInstance->jsEventCode . "</script>";
    }
}