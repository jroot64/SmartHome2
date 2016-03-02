<?php
/*
 * Initiating the session and including all classes that are needed during the execution
 */
require_once(__DIR__ . "/util/Database.php");
$dbState = Database::testConnection();
//var_dump($dbState);
if(!$dbState){
//    echo "test";
    header('location: setup.php');
    exit;
}

include(__DIR__ . "/include.php");

//error_reporting(0);
session_start();
session_set_cookie_params(3000000);

/*
 * the header.html contains the complete header information including the css, js,... files
 */
include(__DIR__ . "/modul/headder.php");

?>

<body id="body">
<?php
/*
 * Handling initiation of the user
 *
 * If the User is not logged in, it will show the login form.
 * If the User is already logged in or just pressed the submit button,
 * it will initiate the user as a system object
 */
if (isset($_GET['mobil']) AND $_GET['mobil'] == 1) {
    $post['loginname'] = $_GET['login'];
    $post['passphrase'] = $_GET['password'];
//    echo $_GET['login'];
//    echo $_GET['password'];
    User::getLogedIn($_SESSION, $post);
}

$user = User::getLogedIn($_SESSION, $_POST);

/*
 * generation of the content if the User is logged in
 */
if (User::isLogedIn($_SESSION)) {
    include(__DIR__ . "/content.php");
}else{
    echo "    <section id='own-main-content'>
                <section class='wrapper'>";
//    Errorlist::loadErrors(User::getID());
    echo Errorlist::getErrors();
    echo "      </section>
              </section>
";
}
?>

<div id="own-spin" class="own-cover-screen dark-bg own-opacity"  style='display: none'>

</div>

<?php
/*
 * Initiate the JS objects and enables the event-handler that were created at the content generation.
 */
//if (User::isLogedIn($_SESSION)) {
//    EventHandler::newEvent('body', "timmedFunction( 30000 , location.reload() )", 'load');
    echo InitJSObjects::getJSinitScript();
    echo EventHandler::getJSEventCode();
//}

?>

</body>
</html>