<?php

/**
 * The Errorlist class is the central point where notifications (errors / information / warnings) are reported.
 */
class Errorlist
{
    private static $instance;
    private $errorInfo = [];
    private $errorWarning = [];
    private $errorError = [];


    /**
     * The function getInstance adds the ability of a singelton to the class and makes it possible that from every
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
     * The function gives access to the internal addError function.
     *
     * @param $eCode
     */

    public static function publicAddError($eCode, $mod_id)
    {
        $instance = self::getinstance();

        $instance->addError($eCode, $mod_id);
    }


    /**
     * The function addError creates the error object and adds this to the error array based on the level of the error
     * ( 1 error / 2 warning / 3 information ).
     *
     * @param $eCode
     */

    private function addError($eCode, $mod_id, $id = true)
    {
        if ($id === true) {
            $error = new Error($eCode, $mod_id);
//            echo "test";
        } else {
            $error = new Error("x", "x", $id);
        }
        $level = $error->getLevel();
//        echo $level;

        switch ($level) {
            case 1:
                $index = count($this->errorError);
                $this->errorError[$index] = $error;
                break;
            case 2:
                $index = count($this->errorWarning);
                $this->errorWarning[$index] = $error;
                break;
            case 3:
                $index = count($this->errorInfo);
                $this->errorInfo[$index] = $error;
                break;
        }

    }


    /**
     * The function getError returns the html code of all alerts sorted by the level ( first error, second warning, third information)
     */

    public static function getErrors()
    {
        $instance = self::getinstance();

        $errors = $instance->errorError;
        $html = "";

        foreach ($errors as $error) {
            $html .= $error->getHTML();
        }

        $errors = $instance->errorWarning;
        foreach ($errors as $error) {
            $html .= $error->getHTML();
        }

        $errors = $instance->errorInfo;
        foreach ($errors as $error) {
            $html .= $error->getHTML();
        }

//        return $html;
        if ($html != "") {
            $panel = new Panel();

            $panel->setTitle('Systemmeldungen');
            $panel->setSize('col-xs-12');
            $panel->addContent($html);

            $returnHTML = $panel->getHTML();
        } else {
            $returnHTML = "";
        }

        return $returnHTML;
    }

    public static function saveErrors($user_id)
    {
        $instance = self::getinstance();
        $db = Database::getinstance();
        $errors = $instance->errorError;

//        var_dump($errors);

        foreach ($errors as $error) {
            $id = $error->getID();


            $db->addBound($id);
            $db->addParam('id');
            $db->addBound($user_id);
            $db->addParam('user_id');

            $db->query('INSERT INTO savedErrors (`errorCode_id`, `user_id`) VALUE ( :id, :user_id)');

        }

        $errors = $instance->errorWarning;
        foreach ($errors as $error) {
            $id = $error->getID();

            $db->addBound($id);
            $db->addParam('id');
            $db->addBound($user_id);
            $db->addParam('user_id');

            $db->query('INSERT INTO savedErrors (`errorCode_id`, `user_id`) VALUE ( :id, :user_id)');
        }

        $errors = $instance->errorInfo;
        foreach ($errors as $error) {
            $id = $error->getID();

            $db->addBound($id);
            $db->addParam('id');
            $db->addBound($user_id);
            $db->addParam('user_id');

            $db->query('INSERT INTO savedErrors (`errorCode_id`, `user_id`) VALUE ( :id, :user_id)');
        }

    }

    public static function loadErrors($user_id)
    {
        $instance = self::getinstance();
        $db = Database::getinstance();

        $db->addBound($user_id);
        $db->addParam('user_id');

        $result = $db->query('SELECT * FROM savedErrors WHERE user_id = :user_id');

        if ($result != false) {
            foreach ($result as $error) {
                $id = $error['errorCode_id'];

                $instance->addError('x', 'x', $id);
            }

            $db->addBound($user_id);
            $db->addParam('user_id');

            $db->query('DELETE FROM savedErrors WHERE user_id = :user_id ');
        }
    }


}