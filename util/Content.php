<?php

/**
 * The Content class handles the loading of the modules that where
 * defined in the database.
 */
class Content
{

    private static $instance = null;

    function __construct(){

    }


    /**
     * The function getInstance adds the ability of an singleton class to the content class.
     * This makes it possible to reach public functions from all points of the code.
     */

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * The public function getHTML reads the modules displayed on the called page by using the get variable "page".
     * If the page variable is not set the value 'home' is set to read the database. The modules get declared and the
     * function loadModule of the module class is called. For the settings of each module the restrictions are read from the
     * database.
     *
     * @param $page
     */

    public static function getHTML($page)
    {
//        echo 'hallo';
//        $page = $_GET['page'];
        $instanceDatabase = Database::getinstance();

        $instanceDatabase->addParam('page');
        $instanceDatabase->addBound($page);
        $instanceDatabase->addParam('loginname');
        $instanceDatabase->addBound(User::getUserLoginname());
        $instanceDatabase->addParam('access');
        $instanceDatabase->addBound(User::getAccessLevel());

//        var_dump($page);

        $result = $instanceDatabase->query("SELECT module.class as class, menu_entry_modul.id as id , module_id FROM menu_entry_modul JOIN module on menu_entry_modul.module_id = module.id WHERE menu_entry_id = (SELECT id FROM menu_entry WHERE getvar = :page AND access_id >= :access) AND menu_entry_modul.access_id >= (SELECT `access_id` FROM `user` WHERE `loginname` = :loginname)");

//        var_dump($result);

        if ($result == false) {
//            echo 'false';
            $instanceDatabase->addParam('page');
            $instanceDatabase->addBound('home');
            $instanceDatabase->addParam('loginname');
            $instanceDatabase->addBound(User::getUserLoginname());
            $result = $instanceDatabase->query("SELECT module.class as class, menu_entry_modul.id as id , module_id FROM menu_entry_modul JOIN module on menu_entry_modul.module_id = module.id WHERE menu_entry_id = (SELECT id FROM menu_entry WHERE getvar = :page ) AND menu_entry_modul.access_id >= (SELECT `access_id` FROM `user` WHERE `loginname` = :loginname)");
        }

//        var_dump($result);

//        echo $result[0]['id'];

        foreach($result as $modul){
//            var_dump($modul);
            $instanceDatabase->addParam('id');
            $instanceDatabase->addBound($modul['id']);
            $instanceDatabase->addParam('loginname');
            $instanceDatabase->addBound(User::getUserLoginname());

            $restrictionsFromSQL = $instanceDatabase->query("SELECT type, val FROM modul_entry_settings WHERE menu_entry_modul_id = :id AND modul_entry_settings.access_id >= (SELECT `access_id` FROM `user` WHERE `loginname` = :loginname)");

            $restrictions = [];

            foreach ($restrictionsFromSQL as $res) {
                $restrictions[$res['type']] = $res['val'];
            }

//            var_dump($restrictions);
//            echo "<br>";
            $test = new $modul['class']();
            echo $test->loadModule($restrictions, $modul['module_id']);
//            var_dump($modul['module_id']);
        }


    }
}