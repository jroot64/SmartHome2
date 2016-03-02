<?php

/*
 * Including the classes that could be used during the execution
 *
 * This is reqired to get access to public functions an initialise Objects
 * without including them in every file it is used
 */

require_once(__DIR__ . "/../../util/User.php");
require_once(__DIR__ . "/../../Settings.php");
require_once(__DIR__ . "/../../util/Database.php");
require_once(__DIR__ . "/../../util/DOM_id.php");
require_once(__DIR__ . "/../../util/Errorlist.php");
require_once(__DIR__ . "/../../util/Panel.php");
require_once(__DIR__ . "/../../util/Send.php");
require_once(__DIR__ . "/../../util/SwitchOnOff.php");
require_once(__DIR__ . "/../../util/EventHandler.php");
require_once(__DIR__ . "/../../util/Topbar.php");
require_once(__DIR__ . "/../../util/InitJSObjects.php");
require_once(__DIR__ . "/../../util/Sidebar.php");
require_once(__DIR__ . "/../../util/Content.php");
require_once(__DIR__ . "/../../util/Table.php");
require_once(__DIR__ . "/../../util/Dropdown.php");
