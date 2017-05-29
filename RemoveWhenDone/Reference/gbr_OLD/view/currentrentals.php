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

            <div class="container-fluid" id="webbg">

            <?php
                if(isset($_GET['action'])){
                    $con_action = $_GET['action'];

                    if($con_action = "created"){
                        echo '

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-success text-center alert-dismissible" role="alert" id="quoteAdded">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                    <strong>Success!</strong>
                                    <hr class="divider headeralert"><br/>
                                    <strong>The container was added successfully!</strong>
                                </div>
                            </div>
                        </div>
                        ';
                    } elseif ($con_action = "deleted"){
                        echo '

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-success text-center alert-dismissible" role="alert" id="quoteAdded">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                    <strong>Success!</strong>
                                    <hr class="divider headeralert"><br/>
                                    <strong>The container was deleted successfully!</strong>
                                </div>
                            </div>
                        </div>
                        ';
                    } elseif ($con_action = "edited"){
                        echo '

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-success text-center alert-dismissible" role="alert" id="quoteAdded">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                    <strong>Success!</strong>
                                    <hr class="divider headeralert"><br/>
                                    <strong>The container was edited successfully!</strong>
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
                                <b>Crrently Rented Containers</b>
                            </div>
                            <div class="panel-body">
                                <?php include(BASEURL.MODEL.'/listallcurrentlyrented.php'); ?>
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
