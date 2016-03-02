<?php

/**
 * The users class represents the software object of a user that can be switched active/inactive or deleted and added.
 */
class Users
{
    private $userID;
    private $userName;
    private $login;
    private $jsID;
    private $mod_id;


    /**
     * The function __construct loads the the important data of the user from the database.
     * The bool dummy is used to have the ability of creating a user object that does not have any database entry.
     *
     * @param $userID
     * @param bool|false $dummy
     */
    public function __construct($userID, $dummy = false)
    {
        $this->mod_id = Module::getModuleID('Users');
        if ($dummy) {

        } else {
            $this->$userID = $userID;

            $instanceDatabase = Database::getinstance();

            $instanceDatabase->addBound($userID);
            $instanceDatabase->addParam('id');

            $result = $instanceDatabase->query("SELECT * FROM `user` WHERE id = :id LIMIT 1");

//            var_dump($result[0]['loginname']);

            $this->userName = $result[0]['name'];
            $this->userID = $result[0]['id'];
            $this->login = $result[0]['loginname'];
            $this->status = $result[0]['state'];
        }
    }

    /**
     * The function getHTML manages the generation of the html code based on the restrictions.
     *
     * @param $restrictions
     * @param $mod_id
     * @return array
     */
    public function getHTML($restrictions, $mod_id)
    {
        $jsID = DOM_id::getID();
        $buttonID = DOM_id::getID();
        $this->jsID = $jsID;
        InitJSObjects::initObject($jsID, 'User', "'$this->userID' , '$mod_id'");
        $html = [];

        $i = 0;

        if (isset($restrictions['sn']) AND $restrictions['sn'] === 'true') {
            $html[$i] = $this->login;
            $i++;
        }
        if (isset($restrictions['sl']) AND $restrictions['sl'] === 'true') {
            $html[$i] = $this->userName;
            $i++;
        }
        if (isset($restrictions['cs']) AND $restrictions['cs'] === 'true') {
            if (User::getUserLoginname() == $this->login) {
                $token = "";
                $html[$i] = $this->getOnOffSwitch($token, false);
            } else {
                $token = User::registerToken($mod_id, 1);
                $html[$i] = $this->getOnOffSwitch($token);
            }
            $i++;
        }
        if (isset($restrictions['du']) AND $restrictions['du'] === 'true') {
            if (User::getUserLoginname() == $this->login) {
                $html[$i] = "<button id='$buttonID' class='btn btn-danger own-table-btn' disabled='disabled'><span class='icon_minus-06'></span></>";
            } else {
                $token = User::registerToken($mod_id, 3);
//            $html[$i] = "";
                $html[$i] = "<button id='$buttonID' class='btn btn-danger own-table-btn'><span class='icon_minus-06'></span></>";
                EventHandler::newEvent("$buttonID", "function(){ $jsID.delUser('$token') }", 'click');
            }
            $i++;
        }

        return $html;
    }

    /**
     * The function genOnOffSwitch is used to generate the switch, to activate or inactivate a user.
     *
     * @param $token
     * @param bool|true $onOff
     * @return string
     */
    private function getOnOffSwitch($token, $onOff = true)
    {
        $switch = new SwitchOnOff();
        $this->onID = $idOn = DOM_id::getID();
        $this->offID = $idOff = DOM_id::getID();
        $switch->setObject(!$this->status, $idOn, $idOff);

        if (!$onOff) {
            $switch->setDisabled();
        }

        $html = $switch->getHTML();
        $jsID = $this->jsID;


        EventHandler::newEvent("$idOn", "function(){ $jsID.setActive( '$token' , '$idOn', '$idOff') }", "click");
        EventHandler::newEvent("$idOff", "function(){ $jsID.setInactive( '$token' , '$idOn', '$idOff') }", "click");

        return $html;
    }

    /**
     * The function addUser inserts a new user into the database.
     *
     * @param $userName
     * @param $login
     * @param $password
     * @return bool
     */
    public function addUser($userName, $login, $password)
    {
        if($userName == "" OR $login == "" OR $password == ""){
            Errorlist::publicAddError(1,$this->mod_id);
            return false;
        }

        $instanceDatabase = Database::getinstance();

        $result = $instanceDatabase->query("SELECT id + 1 AS id FROM `user` ORDER BY id desc LIMIT 1");

        $newID = $result[0]['id'];

        $salt = time();

//        echo $password;

        $hash = hash('sha256', $password . $salt);

        $instanceDatabase->addBound("$login");
        $instanceDatabase->addParam('login');
        $instanceDatabase->addBound("$hash");
        $instanceDatabase->addParam('hash');
        $instanceDatabase->addBound("$salt");
        $instanceDatabase->addParam('salt');
        $instanceDatabase->addBound("$userName");
        $instanceDatabase->addParam('name');
        $instanceDatabase->addBound($newID);
        $instanceDatabase->addParam('id');

        $result = $instanceDatabase->query("INSERT INTO `user`(`id`, `loginname`, `passphrase`, `salt`, `state`, `access_id`, `name`)
VALUES ( :id , :login , :hash , :salt , 0 , 2 , :name )");
        //TODO implement an already existing check.

    }

    /**
     * The function delUser deletes the user.
     */
    public function delUser()
    {
        $login = $this->login;
        $instanceDatabase = Database::getinstance();

        $instanceDatabase->addBound($login);
        $instanceDatabase->addParam('id');

        $result = $instanceDatabase->query("DELETE FROM `user` WHERE loginname = :id LIMIT 1");
    }

    /**
     * The function active is used to switch the user (active or inactive).
     *
     * @param $status
     */
    public function active($status)
    {
        $login = $this->login;
        $instanceDatabase = Database::getinstance();

        $instanceDatabase->addBound($status);
        $instanceDatabase->addParam('state');
        $instanceDatabase->addBound($login);
        $instanceDatabase->addParam('id');

        $result = $instanceDatabase->query("UPDATE `user` SET state  = :state WHERE loginname = :id LIMIT 1");
    }

}