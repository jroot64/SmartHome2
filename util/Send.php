<?php

/**
 * The send class manages the commands that where executed at the commandline of the host system.
 */
class Send
{
    private static $instance;
    private $objects = [];
    private $command;
    private $index;

    private function __construct()
    {
        $this->index = 0;
    }


    /**
     * The function publicAddObject is used to add an object that should be added to the execution.
     *
     * @param $housecode
     * @param $devideCode
     */

    public static function publicAddObject($housecode, $devideCode)
    {
        $instance = self::getinstance();
        $instance->addObject($housecode, $devideCode);

        if (Settings::isMultiModeAllowed()) {
        } else {
            Send::execCommand();
        }
    }


    /**
     * The function getInstance adds the ability of a singleton to the class and makes it possible that from every
     * point of the code a command can be added to the execution.
     */

    private static function getinstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * The function addObject adds an actuator Object to the send command.
     *
     * @param $housecode
     * @param $devideCode
     */

    function addObject($housecode, $devideCode)
    {

        $index = $this->index;

        $this->objects[$index]['houseCode'] = $housecode;
        $this->objects[$index]['deviceCode'] = $devideCode;

        $index++;
//        var_dump($this->objects);
        $this->index = $index;
    }


    /**
     * The function execCommand is used to execute the command that is collected earlier.
     */

    public static function execCommand()
    {
        $instance = self::getinstance();

        if ($instance->index > 0 AND $instance->command != "") {
            $objects = $instance->objects;
            $pathToSendFile = Settings::getPathToSendFile();
            $objectString = "";
            $command = $instance->command;

            foreach ($objects as $object) {
                $objectString .= " " . $object['houseCode'] . " " . $object['deviceCode'];
            }

//            echo "sudo $pathToSendFile $objectString $command";

            shell_exec("sudo $pathToSendFile $objectString $command");

            $instance->deleteVariable();
        } else {
        }

    }


    /**
     * The function deleteVariable is used to clear all important variables.
     */

    private function deleteVariable()
    {
        $instance = self::getinstance();

        $instance->command = "";
        $instance->objects = [];
        $instance->index = 0;
    }


    /**
     * The setProtocol should be used in the future at the point where the managing of the different protocols is done.
     */

    public static function setProtocol()
    {
        //TODO implement a dynamic protocol choosing system
    }


    /**
     * The function setCommand is used to add a command to the execution like 1 for on and 0 for off.
     * @param $command
     */

    public static function setCommand($command)
    {
        $instance = self::getinstance();

        $instance->command = $command;
    }


}