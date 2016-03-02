<?php

/**
 * Class RoomWrapper handles the building of the actuator module on the webpage.
 * The class loads the rooms, initialises them and prepares the panel and the table.
 */
class RoomWrapper
{

    private $groups;
    private $modul_id;


    /**
     * The function loadModule is the function that is called when the module
     * is loaded by the Content class.
     *
     * @param $restrictions
     * @param $module_id
     * @return string
     */

    public function loadModule($restrictions, $module_id)
    {
//        var_dump($restrictions);
        $this->modul_id = $module_id;
        $this->loadAllGroupFromDB();
        $content = $this->getHTMLFromGroup($restrictions);

        $panel = new Panel();
        $panel->setTitle("Room");
        $panel->setSize($restrictions['size']);
        $panel->addContent($content);

        $panelHTML = $panel->getHTML();

        return $panelHTML;

    }


    /**
     * The function is used to load all rooms from the database.
     */

    function loadAllGroupFromDB()
    {
        $instanceDatabase = Database::getinstance();

        $result = $instanceDatabase->query("SELECT * FROM room WHERE id != 1");
        $this->groups = $result;
}

    /**
     *
     * The function is used to load the html code from each actuator based on the
     * restrictions.
     *
     * @param $restrictions
     * @return string
     */

    function getHTMLFromGroup($restrictions)
    {
        $html = "";
        $table = new Table();

        //        Generating the table head
        $tableHead = $this->genTableHead($restrictions);
        $table->setTableHead($tableHead);

        //        Generating the table content
        $i = 0;
        $results = $this->groups;

//        var_dump($results);

        foreach ($results as $result) {
            $room = new Room($result['id']);
            $groupRow = $room->getHTML($restrictions, $this->modul_id);

            foreach ($groupRow as $data) {
                $table->addTableContent($data);
            }
            $i++;
        }

        $html = $table->getHTML();

        if (isset($restrictions['ar']) AND $restrictions['ar'] == 'true') {
            $addGroup = $this->getAddGroup();
            $html .= $addGroup;
        }


        return $html;
    }

    /**
     * The function genTableHead is used to generate the head of the table that is
     * displayed on the web frontend.
     *
     * @param $restrictions
     * @return array
     */

    function genTableHead($restrictions)
    {
        $head = [];
        $i = 0;

        if ((isset($restrictions['sn']) AND $restrictions['sn'] === 'true') OR (isset($restrictions['cn']) AND $restrictions['cn'] === 'true')) {
            $head[$i] = "Name";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['cs']) AND $restrictions['cs'] == 'true') {
            $head[$i] = "On/Off";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['dr']) AND $restrictions['dr'] == 'true') {
            $head[$i] = "Entfernen";
//            echo $i;
            $i++;
        }
        $this->colums = $i;
        return $head;
    }

    /**
     * The function getAddGroup generates the code that is needed to register
     * a new group using the web frontend.
     *
     * @return string
     */

    function getAddGroup()
    {
        $groupNameID = DOM_id::getID();
        $buttonID = DOM_id::getID();
        $groupID = DOM_id::getID();
        $user = User::getUserLoginname();
        $token = User::registerToken($this->modul_id, 1);

        InitJSObjects::initObject($groupID, 'Room', "'99999' , '$user' , '$this->modul_id' , '$token' ");
//        InitJSObjects::initObject($dummyActuatorID, 'Actuator', "99 , 99, '$user' ");
//        EventHandler::newEvent("$buttonID", "function(){ $dummyActuatorID.newActuator( '$token' , '$devicecodeID' , '$groupNameID' , '$dropdownID' ) }", 'click');

//        $html = "<div class='col-sm-12'><div class='input-group'><input type='text' placeholder='Gruppen Name' class='input col-sm-9' maxlength='5' id='$groupNameID'>";
//        $html .= "<button class='btn btn-success col-sm-3' id='$buttonID'><i class='icon_plus'></i></button></div></div>";

        $html = "<div class='form-inline' role='form'>
    <div class='form-group'>
        <input type='text' class='form-control' id='$groupNameID' placeholder='Gruppen Name'>
    </div>
    <button class='btn btn-success' id='$buttonID'><i class='icon_plus'></i></button>
</div>";

        EventHandler::newEvent("$buttonID", "function(){ $groupID.addRoom( '$token' , '$groupNameID' ) }", 'click');

        return $html;
    }

    /**
     * @deprecated
     * @param $restrictions
     * @return string
     */

    function getTable($restrictions)
    {
        $tableContent = $this->getHTMLFromGroup($restrictions);
        $table = "$tableContent";

        return $table;
    }


}