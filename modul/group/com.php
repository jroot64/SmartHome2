<?php

/**
 * The com file handles the module specific actions that where called by an async
 * communication from a javascript function at the web page.
 */


switch ($_GET['action']) {
    case 1:
        $group = initGroup();
        $group->addGroup($_GET['groupName']);
        break;
    case 2:
        $group = initGroup();
        $group->delGroup();
        break;
    case 3:
        $group = initGroup();
        $group->switchGroup($_GET['state'], $_GET['user']);
        break;
    case 4:
//        Changing the name of the group
        $group = initGroup();
        $group->changeName($_GET['name']);
        break;
    case 5:
        break;
    case 6:
        break;
    case 7:
        break;
}

function initGroup()
{
    $group = new Group($_GET['groupID']);
    return $group;
}