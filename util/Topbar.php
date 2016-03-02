<?php

/**
 * The class Topbar contains the bar that is shown at the web pages top and contains
 * functions like logging the user in.
 */
 
class Topbar
{

    private static $instance;
    private $dropdownID;


    /**
     * The constructor function is used to set the needed values like the DOM_id
     * of the dropdown and the username. 
     */
     
    private function __construct()
    {
//        $this->getID();
//        $this->setUserName();

    }


    /**
     * This function handles the generation of the DOM_id for the dropdown.
     */
     
    private function getID()
    {
        $this->dropdownID = DOM_id::getID();
    }
    

    /**
     * This function sets the name of the user that where shown at the right 
     * side of the topbar, using the static function of the User class.
     */
     
    private function setUserName()
    {
        $this->username = User::getUserName();
    }


    /**
     * This function generates the html code that contains the topbar 
     * and return this code.
     */
     
    public static function getHTML()
    {
        $instance = self::getInstance();
        $username = User::getUserName();
        $dropdownID = DOM_id::getID();
        $dowpDown2ID = DOM_id::getID();

//        $instance->event();
        $entrys = $instance->importMenuEntriesFromSQL();
//        var_dump($entrys);
        $HTMLtext = "    <div class='header dark-bg'>
                            <div class='toggle-nav'>
                                <div class='icon-reorder tooltips' data-original-title='Toggle Navigation' data-placement='bottom'></div>
                            </div>

                            <!--logo start-->
                            <a href='?' class='logo'><span class='lite'>Smart</span>Home</a>
                            <!--logo end-->
                            <div class='top-nav notification-row'>
                                <!-- notificatoin dropdown start-->
                                <ul class='nav pull-right top-menu'>
                                    <li class='dropdown' id='$dowpDown2ID' >
                                        <a data-toggle='dropdown' class='dropdown-toggle' href='#'>
                                            <span class='username'>
                                                $username
                                            </span>
                                            <b class='caret'></b>
                                        </a>
                                        <ul id='$dropdownID' class='dropdown-menu extended logout' style='display: none;'>
                                            <div class='log-arrow-up'></div>
                                            $entrys
                                            <li>
                                                <a href='logout.php'><i class='icon_key_alt'></i>Log Out</a>
                                            </li>

                                        </ul>
                                    </li>
                                    <!-- user login dropdown end -->
                                </ul>
                                <!-- notificatoin dropdown end-->
                            </div>
                        </div>";

        EventHandler::newEvent($dowpDown2ID, "function(){ dropdownOpen('". $dropdownID ."') }", "mouseover");
        EventHandler::newEvent($dropdownID, "function(){ dropdownClose('". $dropdownID ."') }", "mouseleave");

        return $HTMLtext;

    }
    
    
    /**
     * The private static function getInstance gives the ability to call a public
     * static function which works every time it was called in the same instance.
     */
     
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * The event function initializes the js eventlistener
     * in the EventHandler class. This makes the dropdown menu covered by the username
     * open onclick and close it when the mouse is out of the html element
     *
     * @deprecated
     */
     
    private function event()
    {
        EventHandler::newEvent($this->dropdownID, "function(){ dropdown('a') }", "click");
        EventHandler::newEvent($this->dropdownID, "function(){ dropdown('a') }", "mouseout");
    }

    private function importMenuEntriesFromSQL()
    {
        //TODO finish the database connection
        $menu = "2";
        $entrys = "";
        $db = Database::getinstance();
        $db->addBound($menu);
        $db->addParam('menu');
        $db->addBound(User::getAccessLevel());
        $db->addParam('access');
        $result = $db->query("SELECT * FROM menu_entry LEFT JOIN symbols on menu_entry.symbol = symbols.id WHERE menu_id = :menu AND access_id >= :access ORDER BY sort");
//        var_dump($result);

        if($result != false){
            foreach ($result as $data) {
                $name = $data["name"];
                $symbol = $data["symbol_tag"];
                $getvar = $data['getvar'];
                $entrys .= "
                        <li class=''>
                            <a class='' href='?page=$getvar'>
                                <i class='$symbol'></i>
                                <span>$name</span>
                            </a>
                        </li>";

            }
//            $entrys .= "<li role='separator' class='divider'></li>";
        }

        return $entrys;
    }

}