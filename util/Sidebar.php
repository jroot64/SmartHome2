<?php

/**
 * Class Sidebar handles the creation of the sidebar with the menu entry's.
 */
class Sidebar
{

//    private static $instance;
    private $entrys;


    /**
     * The __construct function runs the import of the menu entries from the database.
     */

    private function __construct()
    {
        $this->importMenuEntriesFromSQL();
    }


    /**
     * The function importMenuEntriesFromSQL imports the menu entries.
     */

    private function importMenuEntriesFromSQL()
    {
        //TODO finish the database connection
        $menu = "1";
        $entrys = "";
        $db = Database::getinstance();
        $db->addBound($menu);
        $db->addParam('menu');
        $db->addBound(User::getAccessLevel());
        $db->addParam('access');
        $result = $db->query("SELECT * FROM menu_entry JOIN symbols on menu_entry.symbol = symbols.id WHERE menu_id = :menu AND access_id >= :access ORDER BY sort");
//        var_dump($result);
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
        $this->entrys = $entrys;
    }


    /**
     * The getHTML function returns the generated sidebar with the menu entries.
     *
     * @return string
     */

    public static function getHTML()
    {
        $instance = new Sidebar();
        $idReloadNew = DOM_id::getID();
        $idReloadAlt = DOM_id::getID();
        $idTime = DOM_id::getID();
//        echo "test";
        $entrys = $instance->entrys;
        $HTMLtext = "   <aside class='hidden-phone hidden-xs'>
                            <div id='sidebar' class='nav-collapse'>
                            <!-- sidebar menu start-->
                                <ul class='sidebar-menu'>
                                <li id='$idTime' onclick=''>
                                    <a id=''>
                                        <i class='icon_clock'></i>
                                        <span id='time'>05:00</span>
                                        <span id='$idReloadAlt'>Zeit bis zum Neuladen</span>
                                        <span id='$idReloadNew' style='display: none' >Jetzt neu laden!</span>
                                    </a>
                                </li>
                                    $entrys
                                </ul>

                            </div>
                        </aside>";
        EventHandler::newEvent("$idTime", " function(){ change('$idReloadAlt', '$idReloadNew') } " , "mouseenter");
        EventHandler::newEvent("$idTime", " function(){ change('$idReloadNew', '$idReloadAlt') } " , "mouseleave");
        EventHandler::newEvent("$idReloadNew", " function(){reload()} " , "click");
        EventHandler::newEvent("$idReloadNew", " function(){reload()} " , "touch");
//        EventHandler::newEvent("$idReloadNew", " reload(); ","click" );
        return $HTMLtext;
    }
}