<?php

/*
 * Initiating the top and side bar
 */
echo Topbar::getHTML();
echo Sidebar::getHTML();

?>


<!--
Area for the content displayed at the side
-->
<section id="main-content">
    <section class="wrapper">
        <?php

        /*
         * The content class reads the database and returns the HTML-code of the modules that appear on the page
         * that were given as $_GET['page']
         */
        $page = "";
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 'home';
        }

        Content::getHTML($page);

        //        echo Errorlist::getErrors();
        ?>
    </section>
</section>
<section id='main-content'>
    <section class='wrapper'>
        <?php
        Errorlist::loadErrors(User::getID());
        echo Errorlist::getErrors();
        ?>
    </section>
</section>