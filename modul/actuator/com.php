<?php

/**
 * The com file handles the module specific actions that where called by an async
 * communication from a javascript function at the web page.
 */

switch ($_GET['action']) {
    case 1:
        $actuator = initActuator();
        $actuator->switchActuator($_GET['status'], $_GET['user']);
        break;
    case 2:
        $actuator = initActuator();
        $actuator->aktiv($_GET['status']);
        break;
    case 3:
        $actuator = initActuator();
        $actuator->addGroup($_GET['group_id']);
        break;
    case 4:
        $actuator = initActuator();
        $actuator->delGroup($_GET['group_id']);
        break;
    case 5:
        $actuator = initActuator();
        $actuator->changeRoom($_GET['room_id']);
        break;
    case 6:
        $actuator = initActuator();
        $retrun = $actuator->addNewActuator();
        if ($retrun) {
            $actuator->changeRoom($_GET['room_id']);
        }
        break;
    case 7:
        $actuator = initActuator();
        $actuator->delActuator();
        break;
}