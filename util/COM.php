<?php
//Errorlist::publicAddError(1,4); //TODO test error entfernen //TODO error files include
/**
 * Defining the time how old a token could be to be valid.
 */
require_once(__DIR__ . "/../Settings.php");

$validMinutes = Settings::getValidTokenTime();
$tokenTime = time() - (60 * $validMinutes) + 1;
//echo $tokenTime;
//

/**
 * Including all php classes by using the include.php file that is also used on side load with the definition
 * of the all variable that is the trigger that all classes are loaded.
 */

$all = true;
include(__DIR__ . "/../include.php");



/**
 * Checking whether the the token is valid or not
 */
$valid = false;
$db = Database::getinstance();

//$db->addBound($_GET['mod_id']);
//$db->addParam('mod_id');
//$db->addBound($_GET['aktion']);
//$db->addParam('action_id');
$db->addBound($_GET['token']);
$db->addParam('token');

$result = $db->query("SELECT user_id, timestamp FROM token WHERE token = :token AND active = 1  LIMIT 1");
//$result = $db->query("SELECT timestamp FROM token WHERE action_id = (SELECT id FROM actions WHERE mod_id = :mod_id AND action_id = :action_id ) AND active = 1 ");

//var_dump($result);
foreach ($result as $token) {
    $valid = ($token['timestamp'] > $tokenTime) ? true : $valid;
}


if (!$valid) {
    exit;
}
/**
 * Saving the user id for further actions.
 */

$user_id = $result[0]['user_id'];

/**
 * This part includes the com file that continue handling the action call from the web page.
 */

$db->addBound($_GET['mod_id']);
$db->addParam('id');

$result = $db->query('SELECT * FROM `module` WHERE id = :id');
include(__DIR__ . "/../" . $result[0]['com-file']);

if (Settings::isMultiModeAllowed()) {
    Send::execCommand();
}

/**
 * saving errors that has ben occurred.
 */

Errorlist::saveErrors($user_id);


/**
 * Initiation of actuator that is called by the com sections like switching the state disable an actuator and other actions.
 * @return Actuator
 */
function initActuator()
{
    $actuator = new Actuator($_GET['device'], $_GET['housecode']);
//    echo $_GET['device'];
    return $actuator;
}
