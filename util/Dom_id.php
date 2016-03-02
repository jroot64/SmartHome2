<?php

/**
 * The DOM_id class is used to generate unique ids for the html and
 * javascript objects on the webfrontend.
 */
class DOM_id
{

    private static $instance;
    private $id;
    private $length;


    /**
     * The function reads the maxlength from the settings and saves it as local variable.
     */

    private function __construct()
    {
        require_once(__DIR__ . "/../Settings.php");

        $this->length = Settings::getMaxIDLength();
    }


    /**
     * The getID function returns the new generated id.
     */

    public static function getID()
    {
        $instance = self::getInstance();

        $id = $instance->genID();

        return 'id_' . $id;
    }


    /**
     * The function getInstance adds the ability of an singelton class to the content class.
     * This makes it possible to reach public functions from all points of the code.
     */

    private static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * The function generates the next alphabetic id based on the last id and the length.
     */

    private function genID()
    {
        $id = $this->id;
        $maxLength = $this->length;
        $noLength = false;

        if ($maxLength== 0){
            $noLength = true;
            $maxLength = 1;
        }

        $id = str_split($id);
        $stop = false;

        for ($i = 0; $i < $maxLength; $i++) {

            if (!isset($id[$i])) {
                $id[$i] = null;
            }
            $asciNumber = ord($id[$i]);

            if ($asciNumber == 0) {
                $asciNumber = 97;
                $stop = true;
            } elseif ($asciNumber < 122) {
                $asciNumber++;
                $stop = true;
            }

            $id[$i] = chr($asciNumber);
            if ($stop){
                break;
            }
            if($noLength){
                $maxLength++;
            }
        }
        $id = implode($id);
        $this->id = $id;
        return $id;
    }
}