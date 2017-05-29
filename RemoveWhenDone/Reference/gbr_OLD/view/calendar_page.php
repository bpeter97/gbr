<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }
    
    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

    include(BASEURL.CFG.'/database.php');
    $db = new Database();
    $db->connect();

?>

    <div class="row">
        <div class="col-lg-7">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <b>Container Map</b>
                </div>
                <div class="panel-body">
                    <?php include(BASEURL.MODEL.'/calendar.php'); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <b>Calendar</b>
                </div>
                <div class="panel-body">
                    <?php include(BASEURL.MODEL.'/listcalendar.php'); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End of 2nd Row. -->
