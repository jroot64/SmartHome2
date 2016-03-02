<?php

/*
 * Including the classes that could be used during the execution
 *
 * This is required to get access to public functions and initialise objects
 * without including them in every file where they are used
 */

require_once(__DIR__ . "/util/User.php");
require_once(__DIR__ . "/util/Module.php");
require_once(__DIR__ . "/util/Edittextfield.php");
require_once(__DIR__ . "/util/Database.php");
require_once(__DIR__ . "/util/DOM_id.php");
require_once(__DIR__ . "/util/Errorlist.php");
require_once(__DIR__ . "/util/Error.php");
require_once(__DIR__ . "/util/Panel.php");
require_once(__DIR__ . "/util/Send.php");
require_once(__DIR__ . "/util/SwitchOnOff.php");
require_once(__DIR__ . "/util/EventHandler.php");
require_once(__DIR__ . "/util/Topbar.php");
require_once(__DIR__ . "/util/InitJSObjects.php");
require_once(__DIR__ . "/util/Sidebar.php");
require_once(__DIR__ . "/util/Content.php");
require_once(__DIR__ . "/util/Table.php");
require_once(__DIR__ . "/util/Dropdown.php");
require_once(__DIR__ . "/util/Interfaces/StdModule.php");
require_once(__DIR__ . "/util/Interfaces/StdModuleWrapper.php");


$db = Database::getinstance();


if (isset($_GET['page'])) {
$db->addBound($_GET['page']);
} else {
    $db->addBound('');
}
$db->addParam('page');

$result = $db->query('SELECT * FROM menu_entry_modul JOIN module ON module.id = menu_entry_modul.module_id
            WHERE menu_entry_id = ( SELECT id FROM menu_entry WHERE getvar = :page )');

if (isset($all) AND $all == true) {
    $result = $db->query('SELECT * FROM menu_entry_modul JOIN module ON module.id = menu_entry_modul.module_id GROUP BY `include-file`');
}

if($result == false){
    $db->addBound('home');
    $db->addParam('page');

    $result = $db->query('SELECT * FROM menu_entry_modul JOIN module ON module.id = menu_entry_modul.module_id
            WHERE menu_entry_id = ( SELECT id FROM menu_entry WHERE getvar = :page )');
}


foreach ($result as $include) {
    include(__DIR__ . $include['include-file']);
}

//var_dump($result[0]['include-file']);