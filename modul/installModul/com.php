<?php

/**
 * The com file handles the module specific actions tha where called by an asyncron
 * communication from a javascript function at the web page.
 */

switch ($_GET['aktion']) {
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
    echo "test";
    return $group;
}

