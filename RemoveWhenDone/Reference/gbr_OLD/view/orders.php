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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.MODEL.'/header.php'); ?>

</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.INCLUDES.'/fixednavbar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">
                <!-- 1st Row. -->
                <?php 

                if(!empty($_GET['action'])){
                    if ($_GET['action'] == 'orderdel') {
                        echo'
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The order has been removed successfully.</strong></span>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
                ?>

                <!-- 2nd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Orders</b>
                            </div>
                            <div class="panel-body">
                                <?php include(BASEURL.MODEL.'/listallorders.php') ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>

            </div>
        </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
