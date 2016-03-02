
<?php
/**
 *
 */

include(__DIR__ . "/modul/install/include.php");
include(__DIR__ . "/modul/install/headder.php");
if(Database::testConnection()){
    header('location: index.php?page=home');
}

//var_dump($_POST);

if (isset($_POST['loginname']) AND isset($_POST['passphrase'])) {
    $password = Settings::getPassword();
    $username = Settings::getUsername();
    if (isLoggedIn() AND isSetupData()) {
        include(__DIR__ . "/modul/install/install.php");
        install();
    } elseif (isLoggedIn()) {
        echo "<form action='' method='post'>";
        include(__DIR__ . "/modul/install/installForm.html");
        echo "
        <input type='text' class='form-control hidden' name='loginname' placeholder='Username' value='$username'>
        <input type='password' class='form-control hidden' name='passphrase' placeholder='Password' value='$password'>
        </form>";
    } else {
        include_once(__DIR__ . "/modul/login.html");
    }
} else {
    include(__DIR__ . "/modul/login.html");
}


function isLoggedIn()
{
    $password = Settings::getPassword();
    $username = Settings::getUsername();

    if ($_POST['loginname'] == $username AND $_POST['passphrase'] == $username) {
        return true;
    }
    return false;

}

function isSetupData()
{
    if (isset($_POST['admin-password']) AND isset($_POST['check-admin-password']) AND isset($_POST['login']) AND $_POST['name'] AND $_POST['admin-password'] == $_POST['check-admin-password']) {
        return true;
    }
    return false;
}