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

    $newprodurl = HTTP.HTTPURL.VIEW.'/editproducts.php?action=create';

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

            <?php 

                if(!empty($_GET['action'])){
                    if($_GET['action'] == 'prodel') {
                        echo'
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger fade in alert-dismissable text-center">
                                        <a href="#" class="containerlink close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                                        <span><strong>The product has been deleted successfully.</strong></span>
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
                                        <span><strong>The product has been edited successfully.</strong></span>
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
                                        <span><strong>The product has been created successfully.</strong></span>
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
                                <b>Products</b>
                            </div>
                            <div class="panel-body">
                                <div class="modal-header rightalign">
                                    <button type="button" class="btn btn-default form-button" onclick="location.href='<?php echo $newprodurl; ?>'">New Product</button>
                                </div>
                                <?php include(BASEURL.MODEL.'/listallproducts.php') ?>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default form-button" onclick="location.href='<?php echo $newprodurl; ?>'">New Product</button>
                                </div>
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
