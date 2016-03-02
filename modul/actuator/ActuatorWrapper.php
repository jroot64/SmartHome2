<?php

/**
 * Class ActuatorWrapper handles the building of the actuator module on the webpage.
 * The class loads the actuators, initialises them and prepares the panel and the table.
 */
class ActuatorWrapper
{
    private $colums;
    private $actuators;
    private $modul_id;


    /**
     * The function loadModule is the function that is called when the module
     * is loaded by the Content class.
     *
     * @param $restrictions
     * @param $moduleId
     * @return string
     */

    public function loadModule($restrictions, $moduleId)
    {
        $this->modul_id = $moduleId;
        $this->loadAllAktorFromDB();
        $content = $this->getHTMLFromActuator($restrictions);

        $panel = new Panel();
        $panel->setTitle("Aktor");
        $panel->setSize($restrictions['size']);
        $panel->addContent($content);

        $panelHTML = $panel->getHTML();

        return $panelHTML;
    }


    /**
     * The function is used to load all actuators from the database.
     */

    function loadAllAktorFromDB()
    {
        $instanceDatabase = Database::getinstance();

        $result = $instanceDatabase->query("SELECT * FROM actuator");
        $this->actuators = $result;

    }


    /**
     *
     * @deprecated
     * @param $restrictions
     * @return string
     */

    function getTable($restrictions)
    {
        $tableContent = $this->getHTMLFromActuator($restrictions);
        $table = "$tableContent";

        return $table;

    }


    /**
     * The function is used to load the html code from each actuator based on the 
     * restrictions.
     *
     * @param $restrictions
     * @return string
     */

    function getHTMLFromActuator($restrictions)
    {
        $html = "";
        $table = new Table();

//        Generating the table head
        $tableHead = $this->genTableHead($restrictions);
        $table->setTableHead($tableHead);

//        Generating the table content
        $i = 0;
        $results = $this->actuators;

        foreach ($results as $result) {
//            var_dump($result);
            $actuator = new Actuator($result['devicecode'], $result['housecode'], $result['status']);

            $actuatorRow = $actuator->getHTML($restrictions, $this->modul_id);

            foreach ($actuatorRow as $data) {
                $table->addTableContent($data);
            }
            $rowID = $table->getCurrentRowID();
            $actuator->initActuator($rowID);
            $i++;
        }

        $html = $table->getHTML();

        if (isset($restrictions['aa']) AND $restrictions['aa'] == 'true') {
            $addActuator = $this->getAddActuator();
            $html .= $addActuator;
        }

        return $html;
    }


    /**
     * The function genTableHead is used to generate the head of the table that where
     * displayed on the web frontend.
     *
     * @param $restrictions
     * @return array
     */

    function genTableHead($restrictions)
    {
        $head = [];
        $i = 0;

        if (isset($restrictions['sd']) AND $restrictions['sd'] === 'true') {
            $head[$i] = "ID";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['sh']) AND $restrictions['sh'] === 'true') {
            $head[$i] = "Housecode";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['cs']) AND $restrictions['cs'] === 'true') {
            $head[$i] = "On/Off";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['cr']) AND $restrictions['cr'] === 'true') {
            $head[$i] = "Raum";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['cg']) AND $restrictions['cg'] === 'true') {
            $head[$i] = "Gruppe";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['ca']) AND $restrictions['ca'] === 'true') {
            $head[$i] = "Activ/Inactiv";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['da']) AND $restrictions['da'] === 'true') {
            $head[$i] = "Entfernen";
//            echo $i;
            $i++;
        }
        $this->colums = $i;
        return $head;
    }


    /**
     * The function getAddActuator generates the code that is needed to register
     * a new actuator using the web frontend.
     *
     * @return string
     */

    function getAddActuator()
    {
        $devicecodeID = DOM_id::getID();
        $housecodeID = DOM_id::getID();
        $dropdownID = DOM_id::getID();
        $buttonID = DOM_id::getID();
        $user = User::getUserName();
        $token = User::registerToken($this->modul_id, 6);

        $dummyActuator = new Actuator(99, 99999);
        $dummyActuatorID = DOM_id::getID();

        $dropdown = $dummyActuator->getRoomDropdownForAddActuator($dropdownID);
        InitJSObjects::initObject($dummyActuatorID, 'Actuator', "99 , 99, '$user' , '$this->modul_id' ");
        EventHandler::newEvent("$buttonID", "function(){ $dummyActuatorID.newActuator( '$token' , '$devicecodeID' , '$housecodeID' , '$dropdownID' ) }", 'click');

        $table = new Table();
        $table->setTableColumns(6);

        $html = "<div class='form-inline' role='form'>
    <div class='form-group'>
        <input type='number' class='form-control' maxlength='5' id='$devicecodeID' placeholder='Devicecode'>
    </div>
    <div class='form-group'>
        <input type='number' class='form-control' id='$housecodeID' placeholder='Housecode'>
    </div>
    <div class='form-group'>
        $dropdown
    </div>
    <button class='btn btn-success' id='$buttonID'><i class='icon_plus'></i></button>
</div>";
        return $html;
    }
}