<?php

/**
 * The room class represents the software object of a room that owns actuators and is able to switch them, to
 * delete and to add.
 */
class Room
{
    private $roomID;
    private $roomName;
    private $jsID;


    /**
     * The function __construct loads the important data of the room from the database.
     * The bool dummy is used to have the ability of creating an room object that does not have any database entry.
     *
     * @param $roomID
     * @param bool|false $dummy
     */
    public function __construct($roomID, $dummy = false)
    {
        if ($dummy) {

        } else {
            $this->roomID = $roomID;

            $instanceDatabase = Database::getinstance();

            $instanceDatabase->addBound($roomID);
            $instanceDatabase->addParam('id');

            $result = $instanceDatabase->query("SELECT * FROM room WHERE id = :id LIMIT 1");

            $this->roomName = $result[0]['name'];
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

        $user = User::getUserName();
        $buttonID = DOM_id::getID();
        $this->jsID = $jsID;
        InitJSObjects::initObject($jsID, 'Room', "'$this->roomID' , '$user' , '$mod_id'");
        $html = [];

        $i = 0;
//var_dump($restrictions);
        if ((isset($restrictions['sn']) AND $restrictions['sn'] == 'true') OR (isset($restrictions['cn']) AND $restrictions['cn'] === 'true')) {
//            $html[$i] = $this->roomName;
            $change = false;
            $token = "";
            if (isset($restrictions['cn']) AND $restrictions['cn'] === 'true') {
                $token = User::registerToken($mod_id, 4);
                $change = true;
            }

            $html[$i] = $this->textField($this->roomName, $change, $token);

            $i++;
        }
        if (isset($restrictions['cs']) AND $restrictions['cs'] == 'true') {
            $token = User::registerToken($mod_id, 3);
            $html[$i] = $this->getOnOffSwitch($token);
            $i++;
        }
        if (isset($restrictions['dr']) AND $restrictions['dr'] == 'true') {
            $token = User::registerToken($mod_id, 2);
//            $html[$i] = "";
            $html[$i] = "<button id='$buttonID' class='btn btn-danger own-table-btn'><span class='icon_minus-06'></span></>";
            EventHandler::newEvent("$buttonID", "function(){ $jsID.delRoom('$token') }", 'click');
            $i++;
        }

//        $html = '';
        return $html;

    }

    private function textField($text , $restrictions, $token = "")
    {
        $jsID = $this->jsID;
        $textField = new EditTextField();

        $textField->disableChangeable();
        $textField->setValue($text);
//        $textField->functionToCommit("$jsID.changeName", 'token');
//        $textFieldJSID = $textField->getJsID();
        $textFieldFieldID = $textField->getFieldID();
        $html = $textField->getHTML();

        if ($restrictions) {
            $textField->enableChangeable();
            $html = $textField->getHTML();
            EventHandler::newEvent("$textFieldFieldID", " function(){ $jsID.changeName('$token','$textFieldFieldID' ) } ", 'blur');
        }

//        EventHandler::newEvent("$textFieldFieldID", " function(){ $jsID.changeName('test','$textFieldFieldID' ) } ", 'blur');

        return $html;
    }

    /**
     * The function genOnOffSwitch is used to generate the switch, to switch the actuators of the room on and off.
     *
     * @param $token
     * @return string
     */
    function getOnOffSwitch($token)
    {
        $switch = new SwitchOnOff();
        $this->onID = $idOn = DOM_id::getID();
        $this->offID = $idOff = DOM_id::getID();
        $switch->setObject(3, $idOn, $idOff);

//        if ($this->state == 0) {
//            $switch->setDisabled();
//        }

        $html = $switch->getHTML();

        $jsID = $this->jsID;

        EventHandler::newEvent($idOn, "function(){ $jsID.setOn( '$token' , '$idOn', '$idOff') }", "click");
        EventHandler::newEvent($idOff, "function(){ $jsID.setOff( '$token' , '$idOn', '$idOff') }", "click");

        return $html;
    }

    /**
     * The function addRoom inserts a new room into the database if there is no room with the same name.
     *
     * @param $roomName
     */
    function addRoom($roomName)
    {
        echo $roomName;

        $instanceDatabase = Database::getinstance();
        $existsAlready = false;

        $instanceDatabase->addBound($roomName);
        $instanceDatabase->addParam('newName');

        $result = $instanceDatabase->query("SELECT * FROM `room` WHERE `name` = :newName");

        var_dump($result);

        if ($result != false) {
            $existsAlready = true;
        }

        if ($roomName != '' AND $existsAlready == false) {

            $instanceDatabase->addBound($roomName);
            $instanceDatabase->addParam('newName');

            $instanceDatabase->query("INSERT INTO `room` SET `name` = :newName");
        }

    }

    /**
     * The function delRoom deletes all the membership of all actuators of the Room and after that it deletes the
     * room itself.
     */
    function delRoom()
    {

        $instanceDatabase = Database::getinstance();
        $roomID = $this->roomID;

        $instanceDatabase->addBound($roomID);
        $instanceDatabase->addParam('roomid');

        $instanceDatabase->query("UPDATE actuator SET room_id = 1 WHERE room_id = :roomid");

        $instanceDatabase->addBound($roomID);
        $instanceDatabase->addParam('roomid');

        $instanceDatabase->query("DELETE FROM `room` WHERE id = :roomid");

    }

    /**
     * The function switchRoom is used to switch all member of the room to a specified state (on or off).
     *
     * @param $state
     * @param $user
     */
    function switchRoom($state, $user)
    {

        $instanceDatabase = Database::getinstance();
        $roomID = $this->roomID;

        $instanceDatabase->addBound($roomID);
        $instanceDatabase->addParam('roomid');

        $result = $instanceDatabase->query("SELECT * FROM actuator WHERE room_id = :roomid ");

        foreach ($result as $data) {
            $actuator = new Actuator($data['devicecode'], $data['housecode']);

            $actuator->switchActuator($state, $user);
        }

    }

    /**
     * The function performs the sql command to change the name of the room
     *
     * @param $newName
     */
    function changeName($newName){
        $instanceDatabase = Database::getinstance();
        $roomID = $this->roomID;

        $instanceDatabase->addBound($roomID);
        $instanceDatabase->addParam('roomid');
        $instanceDatabase->addBound($newName);
        $instanceDatabase->addParam('newName');

        $result = $instanceDatabase->query("UPDATE room SET `name` = :newName WHERE id = :roomid ");
    }
}