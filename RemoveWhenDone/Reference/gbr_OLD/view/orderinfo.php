<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include DB Connection
    include(BASEURL.CFG.'/database.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

    $db = new Database;
    $db->connect();

    $quote_count = 0;
    $purchase_count = 0;
    $total_amount = 0;

    // Check to see if the customer ID was sent, else return to customers page.
    if(isset($_GET['oid'])) {
        $order_id = $_GET['oid'];
    } else {
        header('location: '.HTTP.HTTPURL.VIEW.'/orders.php');
    }

    // Select customer information from database based on customer ID.
    $db->sql("SELECT * FROM orders WHERE order_id=$order_id");
    $order_response = $db->getResult();

    // Assign variables to results.
    foreach($order_response as $order){
        $order_customer = $order['order_customer'];
        $order_date = $order['order_date'];
        $order_time = $order['order_time'];
        $order_type = $order['order_type'];
        $order_status = $order['order_status'];
        $job_name = $order['job_name'];
        $job_address = $order['job_address'];
        $job_city = $order['job_city'];
        $job_zipcode = $order['job_zipcode'];
        $ordered_by = $order['ordered_by'];
        $onsite_contact = $order['onsite_contact'];
        $onsite_contact_phone = $order['onsite_contact_phone'];
        $driver_name = $order['driver'];
        $driver_notes = $order['driver_notes'];
        $date_delivered = $order['date_delivered'];
        $delivered = $order['delivered'];
        $container_delivered = $order['container'];
        $stage = $order['stage'];
    }

    if($stage == 1){
        $stagelock = "disabled";
    } elseif ($stage == 2 ){
        $stagelock = "";
    } elseif ($stage == 3){
        $stagelock = "";
    } else {
        $stagelock = "disabled";
    }

    // Grabbing information for the purchase history.
    $db->sql("SELECT * FROM drivers");
    $driver_response = $db->getResult();

    if($order_response[0]['order_type']=="Resale" || $order_response[0]['order_type']=="Sales"){
        $db->sql("SELECT * FROM containers WHERE rental_resale = 'Resale' AND is_rented=FALSE");
        $container_response = $db->getResult();
    } else {
        // Grab a list of all of the available containers. IF ORDER TYPE = RENTAL, then is_rented = false, else this needs to be different. Add more later.
        $db->sql("SELECT * FROM containers WHERE rental_resale = 'Rental' AND is_rented=FALSE");
        $container_response = $db->getResult();
    }
    

    // // Select the list of available containers depending on what the order_type is.
    // if($order_type == "Resale"){
    //     $db->sql("SELECT * FROM containers WHERE rental_resale='Resale'");
    //     $container_response = $db->getResult();
    // } elseif ($order_type == "Sales"){
    //     $db->sql("SELECT * FROM containers WHERE rental_resale='Resale'");
    //     $container_response = $db->getResult();
    // } elseif ($order_type == "Rental"){
    //     $db->sql("SELECT * FROM containers WHERE rental_resale='Rental' AND is_rented=FALSE");
    //     $container_response = $db->getResult();
    // }

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
        <!-- http://www.rebol.com/cgi-bin/test-cgi.cgi -->
            <form action="<?php echo HTTP.HTTPURL.CONTROLLERS.'/orders.php?action=edit&oid='.$order_id.'&stage='.$stage; ?>" method="post">
            <input class="form-control" type="hidden" name="frmoid" value="<?php echo $order_id; ?>">
                <!-- End of 1st Row. -->
                <!-- 2nd Row. -->
                <div class="row">
                
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Order Information - CURRENT STAGE: <?php echo $stage; ?></b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcname" control-label>Customer Name:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcname" value="<?php echo $order_customer; ?>">
                                            <p class="help-block">This field is the customers first and last name or company name.</p>
                                        </div>
                                        <label class="col-md-2" for="frmorderedby" control-label>Ordered By:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmorderedby" value="<?php echo $ordered_by; ?>">
                                            <p class="help-block">This field is person who requested the order.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmorderdate" control-label>Order Date:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="date" name="frmorderdate" value="<?php echo $order_date; ?>">
                                            <p class="help-block">This field is the date of the order is requested by.</p>
                                        </div>
                                        <label class="col-md-2" for="frmonsitecontact" control-label>On-Site Contact:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmonsitecontact" value="<?php echo $onsite_contact; ?>">
                                            <p class="help-block">This field is the name of the contact that is on-site.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmordertime" control-label>Order Time:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmordertime" value="<?php echo $order_time; ?>">
                                            <p class="help-block">This field is the time the order is requested to be there.</p>
                                        </div>
                                        <label class="col-md-2" for="frmonsitecontactnumber" control-label>On-Site Contact #:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmonsitecontactnumber" value="<?php echo $onsite_contact_phone; ?>">
                                            <p class="help-block">This field is the phone number for the on-site contact.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmordertype" control-label>Order Type:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmordertype" value="<?php echo $order_type; ?>" <?php echo $stagelock?>>
                                            <p class="help-block">This field is the type of order.</p>
                                        </div>
                                        <label class="col-md-2" for="frmjobname" control-label>Job Name:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmjobname" value="<?php echo $job_name; ?>">
                                            <p class="help-block">This field is the name of the job.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2" for="frmdelivered" control-label">Delivered?</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="frmdelivered" id="frmdelivered" <?php echo $stagelock?>>
                                                    <?php 
                                                    if($delivered == "No"){
                                                        echo '
                                                        <option>Yes</option>
                                                        <option selected>No</option>
                                                        ';
                                                    } elseif ($delivered == "Yes"){
                                                        echo '
                                                        <option selected>Yes</option>
                                                        <option>No</option>
                                                        ';
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option>Yes</option>
                                                        <option>No</option>
                                                        ';
                                                    }?>
                                                </select>
                                                <p class="help-block">Select whether or not the container has been delivered.</p>
                                            </div>
                                        </div>
                                        <label class="col-md-2" for="frmjobaddress" control-label>Job Address:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmjobaddress" value="<?php echo $job_address; ?>">
                                            <p class="help-block">This field is address of the job, the physical location of the container.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmdatedelivered" control-label>Date Delivered:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmdatedelivered" value="<?php echo $date_delivered; ?>" <?php echo $stagelock?>>
                                            <p class="help-block">This is the date that the driver delivered the container.</p>
                                        </div>
                                        <label class="col-md-2" for="frmjobcity" control-label>Job City:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmjobcity" value="<?php echo $job_city ?>">
                                            <p class="help-block">This field is city of where the job is located.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">

                                            <!-- This needs to be changed to a multi-select box. -->

                                        <div class="form-group">
                                            <label class="control-label col-md-2" for="frmcontainerdelivered">Container Delivered</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="frmcontainerdelivered" id="frmcontainerdelivered" <?php echo $stagelock?>>
                                                    <option selected><?php echo $container_delivered; ?></option>
                                                    <!-- PHP to select customers names! -->
                                                    <?php
                                                        foreach($container_response as $con){
                                                            echo '
                                                                    <option value="'.$con['container_ID'].'">'. $con['container_serial_number'] .' ('.$con['container_number'].')</option>
                                                                ';
                                                        }
                                                    ?>
                                                </select>
                                                
                                                <p class="help-block">Select which container was delivered to the customer.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-2" for="frmdriver">Driver</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="frmdriver" id="frmdriver" <?php echo $stagelock?>>
                                                    <option selected><?php if($driver_name == ""){echo "Select One";}else{echo $driver_name;} ?></option>
                                                    <!-- PHP to select customers names! -->
                                                    <?php
                                                        foreach($driver_response as $d){
                                                            echo '
                                                                    <option value="'.$d['driver_ID'].'">'. $d['driver_name'] .'</option>
                                                                ';
                                                        }
                                                    ?>
                                                </select>
                                                
                                                <p class="help-block">Select the driver who delivered the container.</p>
                                            </div>
                                        </div>
                                        <!-- This needs to be changed to a multi-select box. -->
                                        <div class="col-md-4">
                                        <?php 
                                        if($stage ==1){
                                            echo '
                                            <button type="button" onclick="location.href="#" class="btn btn-default form-button btn-gbr" style="float: right;" data-toggle="modal" data-target="#stage2">Complete Delivery</button>
                                            ';
                                        } elseif ($stage == 2) {
                                            echo '
                                            <button type="button" class="btn btn-default form-button btn-gbr" style="float: right;margin-left: 10px;" data-toggle="modal" data-target="#stage3">Schedule Pickup</button> 
                                            
                                            ';
                                        }


                                        
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmdrivernotes" control-label>Driver Notes:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="frmdrivernotes" <?php echo $stagelock?>><?php echo $driver_notes; ?></textarea>
                                            <p class="help-block">This field is the drivers notes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->

                <!-- 4th Row - Save Changes/Go Back Buttons -->
                <div class="navbar navbar-default gbr-bar" style="text-align: center;">
                        <button type="submit" class="btn btn-default form-button" style="margin-top: 7px;">Save Changes</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default form-button" style="margin-top: 7px;">Go Back</button>
                </div>

                <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>

                </form>

                <!-- This is the STAGE 2 MODAL -->
                <div class="modal fade" id="stage2" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align:center;">Stage 2</h4>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo HTTP.HTTPURL.CONTROLLERS.'/orders.php?action=edit&oid='.$order_id.'&stage=2'; ?>" method="post">
                                <input class="form-control" type="hidden" name="frmoid" value="<?php echo $order_id; ?>">
                                <input class="form-control" type="hidden" name="frmstage" value="2">
                                    <div class="row"><!-- 1st Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-md-4" for="frmdelivered" control-label">Delivered?</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="frmdelivered" id="frmdelivered">
                                                        <?php 
                                                        if($delivered == "No"){
                                                            echo '
                                                            <option>Yes</option>
                                                            <option selected>No</option>
                                                            ';
                                                        } elseif ($delivered == "Yes"){
                                                            echo '
                                                            <option selected>Yes</option>
                                                            <option>No</option>
                                                            ';
                                                        } else {
                                                            echo '
                                                            <option selected>Select One</option>
                                                            <option>Yes</option>
                                                            <option>No</option>
                                                            ';
                                                        }?>
                                                    </select>
                                                    <p class="help-block">Select whether or not the container has been delivered.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 1st Row -->
                                    <div class="row"><!-- 2nd Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="frmdatedelivered">Date Delivered</label>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class='input-group'>
                                                            <input id="frmdatedelivered" name="frmdatedelivered" class="form-control" placeholder="" type="text" required="true" value="">
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="help-block">Select the date that the container was delivered.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 2nd Row -->
                                    <div class="row"><!-- 3rd Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="frmcontainerdelivered">Container Delivered</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="frmcontainerdelivered" id="frmcontainerdelivered">
                                                        <option selected><?php echo $container_delivered; ?></option>
                                                        <!-- PHP to select customers names! -->
                                                        <?php
                                                            foreach ($container_response as $con) {
                                                                echo '
                                                                        <option value="'.$con['container_ID'].'">'. $con['container_serial_number'] .' ('.$con['container_number'].')</option>
                                                                    ';
                                                            }
                                                        ?>
                                                    </select>
                                                    
                                                    <p class="help-block">Select which container was delivered to the customer.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 3rd Row -->
                                    <div class="row"><!-- 4th Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-4" for="frmdriver">Driver</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="frmdriver" id="frmdriver">
                                                        <option selected><?php if($driver_name == ""){echo "Select One";}else{echo $driver_name;} ?></option>
                                                        <!-- PHP to select customers names! -->
                                                        <?php
                                                            foreach($driver_response as $d){
                                                                echo '
                                                                        <option value="'.$d['driver_ID'].'">'. $d['driver_name'] .'</option>
                                                                    ';
                                                            }
                                                        ?>
                                                    </select>
                                                    
                                                    <p class="help-block">Select the driver who delivered the container.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 4th Row -->
                                    <div class="row"><!-- 5th Row -->
                                        <div class="col-md-12">
                                            <label class="col-md-4" for="frmdrivernotes" control-label>Driver Notes:</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" cols="40" rows="5" type="text" name="frmdrivernotes"><?php echo $driver_notes; ?></textarea>
                                                <p class="help-block">This field is the drivers notes.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 5th Row -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-default">Submit</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
