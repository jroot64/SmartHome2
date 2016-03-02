<?php

/**
 * The class represents the softwareobject of an actuator, that has different
 * abilitys and attributes. The class is also able to create HTML code
 * with informations about itself (attributes) and the possibility to
 * use the functions like switching the object on and off.
 */
class Actuator
{

    private $deviceCode = "";
    private $houseCode = "";
    private $state;
    private $status;
    private $idOn;
    private $idOff;
    private $mod_id;
    private $isValide;

    /**
     * The $actuatorID variable represents the actuator object at the web page
     * in the js code
     */
    private $actuatorID;

    /**
     * @param $id
     * @param $housecode
     */
    function __construct($id, $housecode)
    {
        $this->actuatorID = DOM_id::getID();
        $this->deviceCode = $id;
        $this->houseCode = $housecode;
        $this->status();
        $this->mod_id = Module::getModuleID('Actuator');
//        echo $id;
        $this->isValide = true;

        $instanceDatabase = Database::getinstance();

        $instanceDatabase->addBound($id);
        $instanceDatabase->addParam('id');
        $instanceDatabase->addBound($housecode);
        $instanceDatabase->addParam('housecode');

        $result = $instanceDatabase->query("SELECT * FROM actuator WHERE devicecode = :id AND housecode = :housecode");
        if($result == false){
            $this->isValide = false;
        }else{
            if (isset($result[0]['status'])) {
                $this->state = $result[0]['status'];
            } else {
                $this->state = 0;
            }
        }

    }

    /**
     * The function reads the log of the actuator and saves the actual status
     * based on the logs into a class variable
     */

    function status()
    {
        $db = Database::getinstance();

        $status = false;

        $id = $this->deviceCode;
        $housecode = $this->houseCode;

        $db->addBound($id);
        $db->addParam(':aktor_id');
        $db->addBound($housecode);
        $db->addParam(':housecode');

        $result = $db->query("SELECT `condition` FROM log_actuator WHERE `actuator_id` = (SELECT id FROM actuator WHERE devicecode = :aktor_id AND housecode = :housecode) ORDER BY id desc LIMIT 1 ");

        if (isset($result[0]['condition']) AND $result[0]['condition'] == 1) {
            $status = true;
        }
        $this->status = $status;
    }

    /**
     * Creating the modules html code based on the restrictions given as
     * the parameter.
     *
     * @param $restrictions
     * @param $moduleID
     * @return array
     */

    public function getHTML($restrictions, $moduleID)
    {
        $this->mod_id = $moduleID;
        $html = [];

        $i = 0;

        if (isset($restrictions['sd']) AND $restrictions['sd'] === 'true') {
            $html[$i] = $this->deviceCode;
            $i++;
        }
        if (isset($restrictions['sh']) AND $restrictions['sh'] === 'true') {
            $html[$i] = $this->houseCode;
            $i++;
        }
        if (isset($restrictions['cs']) AND $restrictions['cs'] === 'true') {
            $token = User::registerToken($moduleID, 1);
            $html[$i] = $this->getOnOffSwitch($token);
            $i++;
        }
        if (isset($restrictions['cr']) AND $restrictions['cr'] === 'true') {
            $token = User::registerToken($moduleID, 5);
            $html[$i] = $this->getRoomDropdown($token);
            $i++;
        }
        if (isset($restrictions['cg']) AND $restrictions['cg'] === 'true') {
            $addToken = User::registerToken($moduleID, 3);
            $delToken = User::registerToken($moduleID, 4);
            $html[$i] = $this->getGroup($addToken, $delToken);
            $i++;
        }
        if (isset($restrictions['ca']) AND $restrictions['ca'] === 'true') {
            $token = User::registerToken($moduleID, 2);
            $html[$i] = $this->getActivInactivSwitch($token);
            $i++;
        }
        if (isset($restrictions['da']) AND $restrictions['da'] === 'true') {
            $token = User::registerToken($moduleID, 7);
            $html[$i] = $this->getDelButton($token);
            $i++;
        }

        return $html;

    }

    public function initActuator($rowID = false){
        $user = User::getUserName();
        $id = $this->actuatorID;
        InitJSObjects::initObject($id, 'Actuator', "$this->houseCode ,$this->deviceCode , '$user' , '$this->mod_id', '$rowID' ");

    }

    /**
     * Create the html code for the on/off switch an registers the event based
     * on the dom button ids.
     *
     * @param $token
     * @return string
     *
     */

    function getOnOffSwitch($token)
    {
        $switch = new SwitchOnOff();
        $this->idOn = $idOn = DOM_id::getID();
        $this->idOff = $idOff = DOM_id::getID();
        $switch->setObject(!$this->status, $idOn, $idOff);

        if ($this->state == 0) {
            $switch->setDisabled();
        }

        $html = $switch->getHTML();

        $id = $this->actuatorID;

        EventHandler::newEvent($idOn, "function(){ $id.setOn( '$token' , '$idOn', '$idOff') }", "click");
        EventHandler::newEvent($idOff, "function(){ $id.setOff( '$token' , '$idOn', '$idOff') }", "click");

        return $html;
    }

    /**
     * The function getRoomDropdown creates the dropdownmenu to change the room
     * of the current actuator.
     *
     * @param $token
     * @return string
     */

    function getRoomDropdown($token)
    {
        $disabled = "";
        $dropdown = new Dropdown();
        $db = Database::getinstance();
        $dropdownID = DOM_id::getID();
        $buttonID = DOM_id::getID();
        $actuatorID = $this->actuatorID;

        $rooms = $db->query("SELECT * FROM room");

        $db->addBound($this->deviceCode);
        $db->addParam('aktor_id');
        $db->addBound($this->houseCode);
        $db->addParam('housecode');

        $atuatorRoom = $db->query("SELECT room.id, name FROM room JOIN actuator on room.id = actuator.room_id WHERE devicecode = :aktor_id AND housecode = :housecode");


        $tmp = $atuatorRoom;

        $atuatorRoomNew['html'] = $tmp[0]['name'];
        $atuatorRoomNew['value'] = $tmp[0]['id'];

        $dropdown->setObject($dropdownID);
        $dropdown->addOption($atuatorRoomNew);

        foreach ($rooms as $room) {
//            echo $room['id']
            if ($room['id'] != $atuatorRoomNew['value']) {
                $tmp = $room;
                $room['html'] = $tmp['name'];
                $room['value'] = $tmp['id'];

                $dropdown->addOption($room);
            }


        }


        $html = $dropdown->getHTML();

//        $html = "       <div class='input-group'>
//                            $html
//                            <a id='$buttonID' class='input-group-addon btn btn-info' $disabled><span class='icon_check'></span></a>
//                        </div>";
        EventHandler::newEvent($dropdownID, "function (){ $actuatorID.changeRoom('$token' , '$dropdownID')}", 'change');
        return $html;
    }

    /**
     * Creates the html code for the group settigs concerning the actuator.
     * These settings show ability to add an actuator to a group and remove an
     * actuator from a group.
     *
     * @param $addToken
     * @param $delToken
     * @return string
     */

    function getGroup($addToken, $delToken)
    {
        $disabled = "";
        $actuatorID = $this->actuatorID;
        $member = $this->getGroupInner();
        $notMember = $this->getGroupOutter($member);
        $selectID = DOM_id::getID();

        $dropdown = new Dropdown();

        foreach ($notMember as $data) {
            $dropdown->addOption($data);
        }

        if (!$dropdown->isOptionNull()) {
            $disabled = 'disabled="disabled"';
        }

        $dropdown->setObject($selectID);
        $dropdownHTML = $dropdown->getHTML();

        $table = new Table();
        $table->setTableColumns(2);
        foreach ($member as $data) {

            $button = DOM_id::getID();
            $tableRowID = $data['value'];
            $table->addTableContent($data['html']);

            $htmlButton = "<button id='$button' class='btn btn-danger own-table-btn'><span class='icon_minus-06'></span></>";

            $table->addTableContent($htmlButton);

            EventHandler::newEvent($button, "function(){ $actuatorID.delGroup( '" . $delToken . "' ,'$tableRowID') }", 'click');
        }

        $tableHTML = $table->getHTML();
        $buttonid = DOM_id::getID();

        $html = "   <div class='input-group'>
                        $dropdownHTML
                        <a id='$buttonid' class='input-group-addon btn btn-success' $disabled><span class='icon_plus'></span></a>
                    </div>
                    $tableHTML
        ";
//        echo $buttonid;
        EventHandler::newEvent($buttonid, "function(){ $actuatorID.addGroup( '" . $addToken . "' ,'$selectID') }", "click");

        return $html;
    }


    /**
     * The function generates an array of groups the actuator is a member of.
     *
     * The arrays first element is the index of the group and
     * the second element contains the name of the group
     *
     * @return array
     */

    function getGroupInner()
    {
        $db = Database::getinstance();
        $groups = [];

        $db->addBound($this->deviceCode);
        $db->addParam('id');
        $db->addBound($this->houseCode);
        $db->addParam('housecode');

//        $result = $db->query("SELECT * FROM group_member JOIN group on group_member.group_id = group.id WHERE actuator_id = (SELECT id FROM actuator WHERE devicecode = :id AND housecode = :housecode)");
        $result = $db->query("SELECT group.id, name FROM `group` JOIN group_member on group.id = group_member.group_id WHERE actuator_id = (SELECT id FROM actuator WHERE devicecode = :id AND housecode = :housecode)");

//        var_dump($result);
        if($result != false) {
            foreach ($result as $group) {
                $new = count($groups);
                $groups[$new]['value'] = $group['id'];
                $groups[$new]['html'] = $group['name'];
            }
        }

        return $groups;

    }


    /**
     * The function generates an array of groups the actuator is not a member of.
     *
     * The arrays first element is the index of the group and
     * the second element contains the name of the group
     *
     * @param $inner
     * @return array
     */

    function getGroupOutter($inner)
    {
        $db = Database::getinstance();
        $groups = [];

        $db->addBound($this->deviceCode);
        $db->addParam(':id');
        $db->addBound($this->houseCode);
        $db->addParam(':housecode');

        $result = $db->query("SELECT id, name FROM `group`");


        foreach ($result as $group) {
            $notMember = true;
            foreach ($inner as $memberGroups) {
                if ($memberGroups['value'] == $group['id']) {
                    $notMember = false;
                }
            }
            if ($notMember) {

                $new = count($groups);
                $groups[$new]['value'] = $group['id'];
                $groups[$new]['html'] = $group['name'];
            }
        }

        return $groups;

    }

    /**
     * Creates the html code for the active/inactive switch and registers the event based
     * on the dom button ids.
     *
     * @param $token
     * @return string
     */

    function getActivInactivSwitch($token)
    {
        $switch = new SwitchOnOff();
        $idActiv = DOM_id::getID();
        $idInactiv = DOM_id::getID();
        $state = false;

        if ($this->state == 0) {
            $state = true;
        }
        $switch->setObject($state, $idActiv, $idInactiv);
        $html = $switch->getHTML();

        $id = $this->actuatorID;

        EventHandler::newEvent($idActiv, "function(){ $id.setAktiv( '$token' , '$idActiv', '$idInactiv' , '$this->idOn' , '$this->idOff') }", "click");
        EventHandler::newEvent($idInactiv, "function(){ $id.setInaktiv( '$token', '$idActiv', '$idInactiv' , '$this->idOn' , '$this->idOff') }", "click");

        return $html;

    }

    /**
     * Creates the html code for the delete button and registers the event based
     * on the dom button id.
     *
     * @param $token
     * @return string
     */

    function getDelButton($token)
    {
        $actuatorID = $this->actuatorID;
        $buttonID = DOM_id::getID();

        $html = "<button class='btn btn-danger' id='$buttonID'><i class='icon_minus-06'></i></button>";

        EventHandler::newEvent("$buttonID", "function (){ $actuatorID.delActuator( '$token' ) }", 'click');
        return $html;
    }

    /**
     * The switchActuator function triggers the switch on/off based on class variables
     * and the state that is given as a parameter.
     *
     * It also inserts a log entry into the database with the current timestamp.
     *
     * @param $state
     * @param $user
     */

    function  switchActuator($state, $user)
    {
        if ($this->state == 1 AND $this->isValide) {
        //TODO write user to the database.
        $db = Database::getinstance();

        $db->addBound($this->deviceCode);
        $db->addParam('aktor_id');
        $db->addBound($this->houseCode);
        $db->addParam('housecode');
//        $db->addBound($user);
//        $db->addParam('user');
        $db->addBound($state);
        $db->addParam('status');

//        echo $string = "INSERT INTO `log_actuator`( `timestamp`, `condition`, `actuator_id`) VALUES ( NOW() , :status , (SELECT id FROM actuator WHERE devicecode = :aktor_id AND housecode = :housecode ) )";
        $string = "INSERT INTO log_actuator SET time = NOW() , `condition` = :status , actuator_id = (SELECT id FROM actuator WHERE devicecode = :aktor_id AND housecode = :housecode)";
//        echo "/home/rasrem/send -b $this->housecode $this->deviceCode $state";
            Send::publicAddObject($this->houseCode, $this->deviceCode);
            Send::setCommand($state);
//            shell_exec("sudo send -b $this->houseCode $this->deviceCode $state");
            $db->query($string);
        }
//        else{
//
//        }
        if (!Settings::isMultiModeAllowed()  AND $this->isValide) {
            Send::execCommand();
        }
    }


    /**
     * The function aktiv enables or disabled the ability of an actuator to get
     * toggle on and off. It remains in the current condition
     */

//TODO find a name that fit's better to the function
    /**
     *
     *
     * @param $state
     */
    function aktiv($state)
    {
        $db = Database::getinstance();

        $db->addBound($this->deviceCode);
        $db->addParam('aktor_id');
        $db->addBound($this->houseCode);
        $db->addParam('housecode');
        $db->addBound($state);
        $db->addParam('state');

        $string = "UPDATE actuator SET status = :state WHERE devicecode = :aktor_id AND housecode = :housecode";

        $db->query($string);
    }


    /**
     * The function changeRoom updates the entry at the actuator table in the
     * database and writes the new room in the db.
     *
     * @param $room
     */

    function changeRoom($room)
    {
        $db = Database::getinstance();

        $db->addBound($this->deviceCode);
        $db->addParam('aktor_id');
        $db->addBound($this->houseCode);
        $db->addParam('housecode');
        $db->addBound($room);
        $db->addParam('room');

        $string = "UPDATE actuator SET room_id = :room WHERE devicecode = :aktor_id AND housecode = :housecode";

        $db->query($string);
    }


    /**
     * The function addGroup inserts an entry into the database which links the
     * actuator to the given group.
     *
     * @param $group_id
     */

    function addGroup($group_id)
    {
        $db = Database::getinstance();

        $db->addBound($this->deviceCode);
        $db->addParam('aktor_id');
        $db->addBound($this->houseCode);
        $db->addParam('housecode');
        $db->addBound($group_id);
        $db->addParam('group_id');

        $string = "INSERT INTO group_member SET group_id = :group_id , actuator_id = (SELECT id FROM actuator WHERE devicecode = :aktor_id AND housecode = :housecode)";

        $db->query($string);
    }


    /**
     * The function delGroup deletes the entry from the database which links the
     * actuator to the given group.
     *
     * @param $group_id
     */

    function delGroup($group_id)
    {
        $db = Database::getinstance();

        $db->addBound($this->deviceCode);
        $db->addParam('aktor_id');
        $db->addBound($this->houseCode);
        $db->addParam('housecode');
        $db->addBound($group_id);
        $db->addParam('group_id');

        $string = "DELETE FROM group_member WHERE group_id = :group_id AND actuator_id = (SELECT id FROM actuator WHERE devicecode = :aktor_id AND housecode = :housecode) LIMIT 1";
//        $string = "UPDATE group_member SET inuse = 0 WHERE group_id = :group_id , actuator_id = (SELECT id FROM actuator WHERE devicecode = :aktor_id AND housecode = :housecode) ";

        $db->query($string);
    }


    /**
     * Inserts the new actuator into the actuator table at the database.
     */

    function addNewActuator()
    {
        //TODO check if an actuator already exists

        if($this->deviceCode == "" OR $this->houseCode == ""){
            Errorlist::publicAddError(1,$this->mod_id);
            return false;
        }

        $houseCode = str_split($this->houseCode,5);

        $db = Database::getinstance();

        $db->addBound($this->deviceCode);
        $db->addParam('deviceCode');
        $db->addBound($houseCode[0]);
        $db->addParam('houseCode');

        $result = $db->query("SELECT id FROM actuator WHERE devicecode = :deviceCode AND housecode = :houseCode");

        if (!isset($result[0]['id'])) {

            $db->addBound($this->deviceCode);
            $db->addParam('deviceCode');
            $db->addBound($houseCode[0]);
            $db->addParam('houseCode');

            $db->query("INSERT INTO actuator SET devicecode = :deviceCode , housecode = :houseCode , status = '0'");
            return true;
        } else {
            Errorlist::publicAddError(2,$this->mod_id);
            return false;
            //TODO create an exception for the case that an actuator already exists
        }

    }


    /**
     * Deletes the current actuator from the actuator table at the database.
     */

    function delActuator()
    {

        echo $this->deviceCode;
        echo $this->houseCode;

        $db = Database::getinstance();


        $db->addBound($this->deviceCode);
        $db->addParam('deviceCode');
        $db->addBound($this->houseCode);
        $db->addParam('houseCode');
        $db->query("DELETE FROM log_actuator WHERE actuator_id = (SELECT id FROM actuator WHERE devicecode = :deviceCode AND housecode = :houseCode)");

        $db->addBound($this->deviceCode);
        $db->addParam('deviceCode');
        $db->addBound($this->houseCode);
        $db->addParam('houseCode');
        $db->query("DELETE FROM group_member WHERE actuator_id = (SELECT id FROM actuator WHERE devicecode = :deviceCode AND housecode = :houseCode)");

        $db->addBound($this->deviceCode);
        $db->addParam('deviceCode');
        $db->addBound($this->houseCode);
        $db->addParam('houseCode');
        $db->query("DELETE FROM actuator WHERE devicecode = :deviceCode AND housecode = :houseCode");
    }


    /**
     * The function getRoomDropdownForAddActuator creates the dropdownmenu for
     * adding the room to the new created actuator.
     *
     * @param $dropdownID
     * @return string
     */

    function getRoomDropdownForAddActuator($dropdownID)
    {
        $dropdown = new Dropdown();
        $db = Database::getinstance();

        $rooms = $db->query("SELECT * FROM room WHERE id != 1");

        $dropdown->setObject($dropdownID);

        foreach ($rooms as $room) {

            $tmp = $room;
            $room['html'] = $tmp['name'];
            $room['value'] = $tmp['id'];

            $dropdown->addOption($room);
        }

        $html = $dropdown->getHTML();

        return $html;
    }

}