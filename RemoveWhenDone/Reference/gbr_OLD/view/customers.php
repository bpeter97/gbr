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

    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover({
                trigger: 'hover',
                html : true,
                content: function() {
                    var content = $(this).attr("data-popover-content");
                    return $(content).children(".popover-body").html();
                }
            }); 
        });
    </script>

</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.INCLUDES.'/fixednavbar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <?php
                if(!empty($_GET['action'])){
                    if($_GET['action'] == 'userdel') {
                        echo'
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The customer has been deleted successfully.</strong></span>
                                    </div>
                                </div>
                            </div>
                        ';
                    } elseif($_GET['action'] == 'esuccess') {
                        echo'
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-success fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The customer has been edited successfully.</strong></span>
                                    </div>
                                </div>
                            </div>
                        ';
                    } elseif($_GET['action'] == 'csuccess') {
                        echo'
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-success fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The customer has been created successfully.</strong></span>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            ?>

            <div class="container-fluid" id="webbg">

                <!-- 2nd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Customers</b>
                            </div>
                            <div class="panel-body">
                                <input type="text" id="filterName" onkeyup="searchNames()" placeholder="Search for names..">
                                <?php include(BASEURL.MODEL.'/listallcustomers.php') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->
            <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>

            </div>
        </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
