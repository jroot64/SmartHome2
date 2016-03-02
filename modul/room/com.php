<?php

/**
 * The com file handles the module specific actions that are called by an async
 * communication from a javascript function at the web page.
 */

switch ($_GET['action']) {
    case 1:
        $room = initRoom();
        $room->addRoom($_GET['roomName']);
        break;
    case 2:
        $room = initRoom();
        $room->delRoom();
        break;
    case 3:
        $room = initRoom();
        $room->switchRoom($_GET['state'], $_GET['user']);
        break;
    case 4:
//        Changing the name of the room
        $room = initRoom();
        $room->changeName($_GET['name']);
        break;
    case 5:
        break;
    case 6:
        break;
    case 7:
        break;
}

function initRoom()
{
    $group = new Room($_GET['roomID']);
    return $group;
}