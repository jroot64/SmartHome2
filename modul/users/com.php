<?php

/**
 * The com file handles the module specific actions that are called by an async
 * communication from a javascript function at the web page.
 */

switch ($_GET['action']) {
    case 1:
        $user = initUser();
        $user->active($_GET['state']);
        break;
    case 2:
        $user = initUser(true);
        echo "test";
        $user->addUser($_GET['userName'], $_GET['login'], $_GET['hash']);
        break;
    case 3:
        echo $_GET['userID'];
        $user = initUser();
        $user->delUser();
        break;
    case 4:
        break;
    case 5:
        break;
    case 6:
        break;
    case 7:
        break;
}

function initUser($dummy = false)
{
    if ($dummy == false) {
        $user = new Users($_GET['userID']);
    } else {
        $user = new Users($_GET['userID'], true);
    }
    return $user;
}