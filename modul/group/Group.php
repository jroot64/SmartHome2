<?php

/**
 * The group class represents the software object of a group that owns actuators and is able to switch them, to
 * delete and to add.
 */
class Group
{
    private $groupID;
    private $groupName;
    private $jsID;


    /**
     * The function __construct loads the important data of the group from the database.
     * The bool dummy is used to have the ability of creating an group object that does not have any database entry.
     *
     * @param $groupID
     * @param bool|false $dummy
     */
    public function __construct($groupID, $dummy = false)
    {
        if ($dummy) {

        } else {
            $this->groupID = $groupID;

            $instanceDatabase = Database::getinstance();

            $instanceDatabase->addBound($groupID);
            $instanceDatabase->addParam('id');

            $result = $instanceDatabase->query("SELECT * FROM `group` WHERE id = :id LIMIT 1");

            $this->groupName = $result[0]['name'];
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
        InitJSObjects::initObject($jsID, 'Group', "'$this->groupID' , '$user' , '$mod_id'");
        $html = [];

        $i = 0;

        if ((isset($restrictions['sn']) AND $restrictions['sn'] === 'true') OR (isset($restrictions['cn']) AND $restrictions['cn'] === 'true') ) {
            $change = false;
            $token = "";
            if (isset($restrictions['cn']) AND $restrictions['cn'] === 'true') {
                $token = User::registerToken($mod_id, 4);
                $change = true;
            }
            $html[$i] = $this->textField($this->groupName, $change, $token);
            $i++;
        }
        if (isset($restrictions['cs']) AND $restrictions['cs'] === 'true') {
            $token = User::registerToken($mod_id, 3);
            $html[$i] = $this->getOnOffSwitch($token);
            $i++;
        }
        if (isset($restrictions['dg']) AND $restrictions['dg'] === 'true') {
            $token = User::registerToken($mod_id, 2);
//            $html[$i] = "";
            $html[$i] = "<button id='$buttonID' class='btn btn-danger own-table-btn'><span class='icon_minus-06'></span></>";
            EventHandler::newEvent("$buttonID", "function(){ $jsID.delGroup('$token') }", 'click');
            $i++;
        }


        return $html;

    }

    private function textField($text, $restrictions, $token = "")
    {
        $jsID = $this->jsID;
        $textField = new EditTextField();

        $textField->disableChangeable();
        $textField->setValue($text);
//        $textField->functionToCommit("$jsID.changeName", 'token');
        $textFieldFieldID = $textField->getFieldID();
        $html = $textField->getHTML();

        if ($restrictions) {
            $textField->enableChangeable();
            $html = $textField->getHTML();
            EventHandler::newEvent("$textFieldFieldID", " function(){ $jsID.changeName('$token','$textFieldFieldID' ) } ", 'blur');
        }


        return $html;
    }

    /**
     * The function genOnOffSwitch is used to generate the switch to switch the actuators of the group on and off.
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


        EventHandler::newEvent("$idOn", "function(){ $jsID.setOn( '$token' , '$idOn', '$idOff') }", "click");
        EventHandler::newEvent("$idOff", "function(){ $jsID.setOff( '$token' , '$idOn', '$idOff') }", "click");

        return $html;
    }

    /**
     * The function addGroup inserts a new group into the database if there is no group with the same name.
     *
     * @param $groupName
     */
    function addGroup($groupName)
    {
        $instanceDatabase = Database::getinstance();
        $existsAlready = false;

        $instanceDatabase->addBound($groupName);
        $instanceDatabase->addParam('newName');

        $result = $instanceDatabase->query("SELECT * FROM `group` WHERE `name` = :newName");

        if ($result != false) {
            $existsAlready = true;
        } else {
            //TODO create a warning
        }

        if ($groupName != '' AND $existsAlready == false) {

            $instanceDatabase->addBound($groupName);
            $instanceDatabase->addParam('newName');

            $instanceDatabase->query("INSERT INTO `group` SET `name` = :newName");
        }

    }

    /**
     * The function delGroup deletes all the membership of all actuators to the group and after that it deletes the
     * group itself.
     */
    function delGroup()
    {
        $instanceDatabase = Database::getinstance();
        $groupID = $this->groupID;

        $instanceDatabase->addBound($groupID);
        $instanceDatabase->addParam('groupid');

        $instanceDatabase->query("DELETE FROM group_member WHERE group_id = :groupid");

        $instanceDatabase->addBound($groupID);
        $instanceDatabase->addParam('groupid');

        $instanceDatabase->query("DELETE FROM `group` WHERE id = :groupid");

    }

    /**
     * The function switchGroup is used to switch all members of the group to a specified state (on or off).
     *
     * @param $state
     * @param $user
     */
    function switchGroup($state, $user)
    {

        $instanceDatabase = Database::getinstance();
        $groupID = $this->groupID;

        $instanceDatabase->addBound($groupID);
        $instanceDatabase->addParam('groupid');

        $result = $instanceDatabase->query("SELECT * FROM `group_member` JOIN actuator ON actuator_id = actuator.id WHERE group_id = :groupid ");

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
        $groupID = $this->groupID;

        $instanceDatabase->addBound($groupID);
        $instanceDatabase->addParam('groupid');
        $instanceDatabase->addBound("$newName");
        $instanceDatabase->addParam('newName');

        $instanceDatabase->query("UPDATE `group` SET `name` = :newName WHERE id = :groupid ");
        var_dump($groupID);
    }
}