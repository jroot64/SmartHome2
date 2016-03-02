<!--
All needed files like css js ... were included and some
meta settings, title were set at this point.

-->

<!DOCTYPE html>
<html>
<head lang="de">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart-Home">
    <meta name="author" content="GeeksLabs">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>SmartHome</title>

    <!-- Bootstrap CSS -->
<!--    <link href="css/bootstrap-theme.min.css" rel="stylesheet">-->
<!--    <link href="css/bootstrap.css" rel="stylesheet">-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet"/>
    <link href="css/font-awesome.min.css" rel="stylesheet"/>
    <!-- other styledocs -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet"/>
    <link href="css/jquery-ui-1.10.4.min.css" rel="stylesheet">
    <link href="css/own-css.css" rel="stylesheet">
    <!--own-->
    <script src="js/jquery-1.11.3.min.js" language="javascript" type="text/javascript"></script>
    <script src="js/own-Genaral.js" language="javascript" type="text/javascript"></script>
    <script src="js/spin.js" language="javascript" type="text/javascript"></script>
    <script src="js/Panel.js" language="javascript" type="text/javascript"></script>
    <script src="js/EditTextField.js" language="javascript" type="text/javascript"></script>
    <script src="js/sha256.js" language="javascript" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"
            type="text/javascript"></script>

    <?php
    /**
     * At this point the javascript classes were included based on he page given at the get variable.
     */

    $db = Database::getinstance();


    if (isset($_GET['page'])) {
        $db->addBound($_GET['page']);
    } else {
        $db->addBound('home');
    }
    $db->addParam('page');

    $result = $db->query('SELECT * FROM menu_entry_modul JOIN module ON module.id = menu_entry_modul.module_id
    WHERE menu_entry_id = ( SELECT id FROM menu_entry WHERE getvar = :page )');

    if (isset($all) AND $all == true) {
        $result = $db->query('SELECT * FROM menu_entry_modul JOIN module ON module.id = menu_entry_modul.module_id GROUP BY `js-Class`');
    }

    if($result == false){
        $db->addBound('home');
        $db->addParam('page');
        $result = $db->query('SELECT * FROM menu_entry_modul JOIN module ON module.id = menu_entry_modul.module_id
    WHERE menu_entry_id = ( SELECT id FROM menu_entry WHERE getvar = :page )');
    }

    foreach ($result as $include) {
        $js = $include['js-Class'];
        echo "<script language='javascript' type='text/javascript' src='$js'></script>";
    }
//    var_dump($result);
    ?>

</head>