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
                    if($_GET['action'] == 'c_success'){
                        echo '
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-success fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The quote you selected has been converted to an order successfully.</strong></span>
                                    </div>
                                </div>
                            </div>
                        ';
                    } elseif ($_GET['action'] == 'cdelb') {
                        echo'
                            <!-- quote converted to order successfully. -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The quote you selected was open therefore the products associated with the quote have been removed from the database as well and the quote has been deleted successfully.</strong></span>
                                    </div>
                                </div>
                            </div>
                        ';
                    } elseif ($_GET['action'] == 'cdela') {
                        echo'
                            <!-- quote converted to order successfully. -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The quote you selected was closed therefore the products associated with the quote have been left in the database to be associated with the order they\'re attached to from the database as well and the quote has been deleted successfully.</strong></span>
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
                                <b>Quotes</b>
                            </div>
                            <div class="panel-body">
                                <?php include(BASEURL.MODEL.'/listallquotes.php') ?>
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
