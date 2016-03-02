<?php

/**
 * The user class handles the requirements concerning the user like handling the
 * login process and generating the tokens.
 */
class User
{

    private static $instance;
    private $loginname;
    private $name;
    private $token;
    private $region;
    private $accessLevel;
    private $id;

    private function __construct()
    {

    }


    /**
     * For checking the login status, if the user is logged in it will return true
     * otherwise it will return false.
     *
     * @param $session
     * @return bool
     */

    public static function isLogedIn($session)
    {
        if (isset($session['loginname'])) {
                return true;
        }
        return false;
    }


    /**
     * The function that handles the login or user initiation process whether the user
     * is logged in, just entered the sing in credentials or initially loads the
     * web page to login.
     *
     * @param $session
     * @param $post
     * @return bool|null|User
     */

    public static function getLogedIn($session, $post)
    {
        $userInstance = self::getInstance();
        //TODO implement bruteforce protection
        if (isset($session['loginname']) AND isset($session['token'])) {
            $userInstance->userSetup($session['loginname']);
            $userInstance->prepareToken();
            return true;
        } elseif (isset($post['loginname']) AND isset($post['passphrase'])) {

            $login = $userInstance->login($post);
            if ($login != false) {
//                echo "test";
                $userInstance->prepareToken();
                return true;
            }
        } else {
            include(__DIR__ . "/../modul/login.html");
//            return true;
        }
        return true;
    }


    /**
     * The private static function getInstance gives the ability to call a public
     * static function which works everytime ist was called in the same instance.
     */

    private static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * The function declares the class variables name and loginname. It also
     * calls the creation of the token.
     * @param $loginname
     */

    private function userSetup($loginname)
    {
        $db = Database::getinstance();
        $db->addBound($loginname);
        $db->addParam('login');

        $result = $db->query("SELECT * FROM user WHERE loginname = :login LIMIT 1");

        $this->id = $result[0]['id'];
        $this->name = $result[0]['name'];
        $this->loginname = $loginname;
        $this->accessLevel = $result[0]['access_id'];


        //initialising the user token
        $this->genToken($loginname);
    }


    /**
     * The function generates the token based on the system time salted with a
     * random value between 0 and 1000000000000 and writes it to the database.
     * @param $loginname
     */

    private function genToken($loginname)
    {
        //TODO implement the database entry and function specified tokens
        $token = hash('sha256', time() . rand(0, 1000000000000));
        $this->token = $token;
        $db = Database::getinstance();
        $db->addBound($token);
        $db->addParam('token');
        $db->addBound($loginname);
        $db->addParam('login');


        $db->query("UPDATE user SET token = :token WHERE loginname = :login ");

        $_SESSION['token'] = $token;

    }


    /**
     * The function prepareToken is used to cleanup all open tokens from the last time the side was
     * called by the user.
     */

    private function prepareToken()
    {
        $instance = self::getInstance();
        $db = Database::getinstance();

        $db->addBound($instance->loginname);
        $db->addParam('loginname');

        $db->query("DELETE FROM token WHERE user_id = (SELECT id FROM `user` WHERE loginname = :loginname)");
    }

    /**
     * This function is needed to have access to the token in every point of the
     * code.
     */

//    public static function getToken()
//    {
//        $userInstance = self::getInstance();
//        return $userInstance->token;
//    }

    /**
     * The function is logging the user in, that means that the passphrase from
     * the login form is compared with the hash value of the database. If the value
     * matches the login name was written in the session variable to identify
     * that the user is logged in
     * @param $post
     * @return bool|int
     */

    private function login($post)
    {

        $loginname = $post['loginname'];
        $passphrase = $post['passphrase'];
        $db = Database::getinstance();
        $db->addBound($loginname);
        $db->addParam('login');


        $result = $db->query("SELECT * FROM user WHERE loginname = :login LIMIT 1");

        if(!$result){

        }else{
            if ($result[0]['state'] == 0) {
                include(__DIR__ . "/../modul/login.html");
                Errorlist::publicAddError(8,1);
                return false;
                //TODO gen error code: inactive user
            } elseif ($result[0]['state'] == 1 AND $result[0]['passphrase'] == hash('sha256', hash('sha256', $passphrase) . $result[0]['salt'])) {
                $_SESSION['loginname'] = $loginname;
                $userInstance = self::getInstance();
                $userInstance->userSetup($loginname);

//            $_POST['loginname'] = null;

                return true;
            }
        }
        include(__DIR__ . "/../modul/login.html");
        Errorlist::publicAddError(7,1);

        return false;

    }

    /**
     * The function generates a token that got returned to a function creating an action that can be called
     * @param $module
     * @param $action
     * @return string
     */

    public static function registerToken($module, $action)
    {
        $instance = self::getInstance();
        $db = Database::getinstance();
        $token = $instance->genActionToken();

        $db->addBound($module);
        $db->addParam('mod_id');
        $db->addBound($action);
        $db->addParam('action_id');
        $db->addBound($instance->loginname);
        $db->addParam('loginname');

        $result = $db->query("SELECT token FROM token WHERE user_id = (SELECT id FROM `user` WHERE loginname = :loginname) AND action_id= (SELECT id FROM actions WHERE mod_id = :mod_id AND action_id = :action_id ) AND active = 1");
        if (!$result) {

            $db->addBound($module);
            $db->addParam('mod_id');
            $db->addBound($action);
            $db->addParam('action_id');
            $db->addBound($instance->loginname);
            $db->addParam('loginname');
            $db->addBound(time());
            $db->addParam('unixtime');
            $db->addBound($token);
            $db->addParam('token');

            $result = $db->query("INSERT INTO `token`(`action_id`, `user_id`, `timestamp`, `token`) VALUES ((SELECT id FROM actions WHERE mod_id = :mod_id AND action_id = :action_id ),(SELECT id FROM `user` WHERE loginname = :loginname),:unixtime,:token)");
//            var_dump($result);
        } else {
            $token = $result[0]['token'];
        }
//        echo $token;
        return $token;
    }

    /**
     * The function generates a token based on the system time salted with a
     * random value between 0 and 1000000000000.
     */

    private function genActionToken()
    {
        //TODO implement the database entry and function specified tokens
        $token = hash('sha256', time() . rand(0, 1000000000000));

        return $token;

    }


    /**
     * This function is needed to have access to the username in every point of the
     * code.
     */

    public static function getUserName()
    {
        $userInstance = self::getInstance();
        return $userInstance->name;
    }


    /**
     * This function is needed to have access to the loginname in every point of the
     * code.
     *
     * @return
     */

    public static function getUserLoginname()
    {
        $userInstance = self::getInstance();
        return $userInstance->loginname;
    }

    /**
     * This function is needed to have access to the access level in every point of the
     * code.
     *
     * @return
     */
    public static function getAccessLevel()
    {
        $userInstance = self::getInstance();
        return $userInstance->accessLevel;
    }

    /**
     * This function is needed to have access to the user id in every point of the
     * code.
     *
     * @return string
     */
    public static function getID()
    {
        $userInstance = self::getInstance();
        return $userInstance->id;
    }

//
//    /**
//     *
//     */
//
//    public static function writeToCoockie()
//    {
//
//        $userInstance = self::getInstance();
//
//        setcookie('smarthome-name', $userInstance->loginname, time() + (86400 * 30), "/");
//        setcookie('smarthome-token', $userInstance->token, time() + (86400 * 30), "/");
//
//    }

//    public static function logout()
//    {
//        var_dump($_SESSION);
//        session_destroy();
//        echo "test";
//    }


}