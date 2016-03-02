<?php

/**
 * Class UsersWrapper handles the building of the actuator module on the webpage.
 * The class loads the users initialise them prepares the panel and the table.
 */
class UsersWrapper
{
    private $users;
    private $mod_id;


    /**
     * The function loadModule is the function that is called when the module
     * is loaded by the Content class.
     *
     * @param $restrictions
     * @param $mod_id
     * @return string
     */

    public function loadModule($restrictions, $mod_id)
    {
//        var_dump($restrictions);
        $this->mod_id = $mod_id;
        $this->loadAllUsersFromDB();
        $content = $this->getHTMLFromUsers($restrictions);

        $panel = new Panel();
        $panel->setTitle("User");
        $panel->setSize($restrictions['size']);
        $panel->addContent($content);

        $panelHTML = $panel->getHTML();

        return $panelHTML;

    }


    /**
     * The function is used to load all users from the database.
     */

    function loadAllUsersFromDB()
    {
        $instanceDatabase = Database::getinstance();

        $result = $instanceDatabase->query("SELECT id FROM `user`");
        $this->users = $result;

    }


    /**
     * @param $restrictions
     * @return string
     */

    function getHTMLFromUsers($restrictions)
    {
        $html = "";
        $table = new Table();

        //        Generating the table head
        $tableHead = $this->genTableHead($restrictions);
        $table->setTableHead($tableHead);

        //        Generating the table content
        $i = 0;
        $results = $this->users;

        foreach ($results as $result) {
            $user = new Users($result['id']);

            $userRow = $user->getHTML($restrictions, $this->mod_id);

            foreach ($userRow as $data) {
                $table->addTableContent($data);
            }
            $i++;
        }

        $html = $table->getHTML();

        if (isset($restrictions['au']) AND $restrictions['au'] == 'true') {
            $addUser = $this->getAddUser();
            $html .= $addUser;
        }


        return "$html";
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

        if (isset($restrictions['sn']) AND $restrictions['sn'] == 'true') {
            $head[$i] = "Login";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['sl']) AND $restrictions['sl'] == 'true') {
            $head[$i] = "Name";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['cs']) AND $restrictions['cs'] == 'true') {
            $head[$i] = "Active/Inactive";
//            echo $i;
            $i++;
        }
        if (isset($restrictions['du']) AND $restrictions['du'] == 'true') {
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

    function getAddUser()
    {
        $userNameID = DOM_id::getID();
        $loginNameID = DOM_id::getID();
        $passwordID = DOM_id::getID();
        $buttonID = DOM_id::getID();
        $userID = DOM_id::getID();
        $user = User::getUserName();
        $token = User::registerToken($this->mod_id, 2);

        InitJSObjects::initObject($userID, 'User', "'99999' , '$this->mod_id'");

        $html = "<div class='form-inline' role='form'>
    <div class='form-group'>
    <lable>Login</lable>
        <input type='text' class='form-control' id='$loginNameID' placeholder='Login'>
    </div>
    <div class='form-group'>
    <lable>Name</lable>
        <input type='text' class='form-control' id='$userNameID' placeholder='Name'>
    </div>
    <div class='form-group'>
    <lable>Password</lable>
        <input type='password' class='form-control' id='$passwordID' placeholder='Password'>
    </div>
    <button class='btn btn-success' id='$buttonID'><i class='icon_plus'></i></button>
</div>";

        EventHandler::newEvent("$buttonID", "function(){ $userID.addUser( '$token' , '$loginNameID' , '$userNameID' , '$passwordID' ) }", 'click');

        return $html;
    }
}