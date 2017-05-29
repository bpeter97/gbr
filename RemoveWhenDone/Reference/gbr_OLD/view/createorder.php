<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    // Include DB Connection
    include('../cfg/mysqli_connect.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

    $newcusturl = HTTP.HTTPURL.VIEW.'/createcustomer.php?from=createorder';

    // If cust has been sent, then select the customers name based off of his/her ID, else stay as "Choose One".
    $cname = "Choose One";
    if(isset($_GET['cust'])) {
        $query = "SELECT * FROM customers WHERE customer_ID = ".$_GET['cust'];
        $response = @mysqli_query($dbc, $query);

        if($response) {
            while ($row = mysqli_fetch_array($response)) {
                $cname = $row['customer_name'];
            }
        }
    }

    // Create the alert value array.
    $alertvalues = array();

    // Querry to add flagged customers to an array.
    $query = "SELECT * FROM customers WHERE flagged = 'Yes'";
    $response = @mysqli_query($dbc, $query);

    // If there are flagged customers push them to the alert values array.
    if($response) {
        while ($row = mysqli_fetch_array($response)) {

            array_push($alertvalues, $row['customer_name']);

        }
    }

    $alert_response = "";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.MODEL.'/header.php'); ?>

    <?php 
    echo '
    <script type="text/javascript">
        $(document).ready(function(){
            var date_input=$(\'input[name="frmorderdate"]\');
            var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
            var options={
                format: \'yyyy-mm-dd\',
                container: container,
                todayHighlight: true,
                autoclose: true,
                orientation: "top",
            };
            date_input.datepicker(options);
        })
    </script>
    ';
     ?>
    
    <script type="text/javascript">
        $(function () {
            $('#frmordertime').datetimepicker({
                format: 'HH:mm:ss'
            });
        });
    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            $('#frmcustomername').change(function(){
                var value = $(this).val();
                var jqueryarray = <?php echo json_encode($alertvalues); ?>;
                if ($.inArray(value, jqueryarray)) {
                    
                } else {
                    $.ajax({
                        type: 'post',
                        url: '../controller/flagcust.php',
                        data: {
                            cust:value,
                        },
                        success: function (response){
                            $("#alertModal").html(response);
                            $("#alertModal").modal("show");
                        }
                    });
                    
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

                <!-- 2nd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Create Order</b>
                            </div>
                            <div class="panel-body">
                                <form action="../model/createorder_sessions.php" method="post">
                                    <div class="row"><!-- 1st Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="frmorderdate">Order Date</label>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class='input-group'>
                                                            <input id="frmorderdate" name="frmorderdate" class="form-control datepicker" placeholder="YYYY-MM-DD" type="text">
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="help-block">Select the date requested, the format must match YYYY-MM-DD (2017-01-08 for January 8, 2017).</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 1st Row -->
                                    <div class="row"><!-- 2nd Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="frmorderdate">Order Time</label>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class='input-group'>
                                                            <input id="frmordertime" name="frmordertime" class="form-control datepicker" placeholder="00:00:00" type="text">
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-time"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="help-block">Select the time requested, the format needs to be 24 hour format (00:00:00).</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 2nd Row -->
                                    <div class="row"><!-- 3rd Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmcustomername" control-label">Select a Customer</label>
                                            <div class="col-md-7">
                                                <select class="form-control" name="frmcustomername" id="frmcustomername">
                                                    <option><?php echo $cname; ?></option>
                                                    <!-- PHP to select customers names! -->
                                                    <?php
                                                        $query = "SELECT * FROM customers ORDER BY customer_name";
                                                        $response = @mysqli_query($dbc, $query);

                                                        if($response) {
                                                            while ($row = mysqli_fetch_array($response)) {

                                                                echo '
                                                                    <option value="'.$row['customer_name'].'">'. $row['customer_name'] .'</option>
                                                                ';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                
                                                <p class="help-block">Select which customer is getting the order.</p>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" onclick="location.href='<?php echo $newcusturl; ?>'" class="btn btn-default btn-gbr">New</button>
                                            </div>
                                        </div>
                                    </div><!-- End of 3rd Row -->

                                    <div class="row"><!-- 4th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmorderedby" control-label>Ordered By</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmorderedby" required="false">
                                                <p class="help-block">This field is the name of the person who requested the order.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 4th Row -->

                                    <div class="row"><!-- 5th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmordertype" control-label">Order Type</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="frmordertype" id="frmordertype">
                                                    <option>Select One</option>
                                                    <option value="Rental">Rental</option>
                                                    <option value="Resale">Resale</option>
                                                    <option value="Sales">Sales</option>
                                                </select>
                                                <p class="help-block">Select what type of order this is.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 5th Row -->
                                    <div class="row"><!-- 6th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobname" control-label>Job Name</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobname">
                                            <p class="help-block">Fill out the job name if there is one.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 6th Row -->

                                    <div class="row"><!-- 7th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobaddress" control-label>Job Address</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobaddress">
                                            <p class="help-block">Fill out just the <strong>STREET</strong> address.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 7th Row -->

                                    <div class="row"><!-- 8th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobcity" control-label>Job City</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobcity">
                                            <p class="help-block">Fill out the city of the job location.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 8th Row -->

                                    <div class="row"><!-- 9th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobzipcode" control-label>Job Zipcode</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobzipcode">
                                            <p class="help-block">Fill out the zipcode of the job location.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 9th Row -->

                                    <div class="row"><!-- 10th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmcontact" control-label>On-Site Contact</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmcontact">
                                            <p class="help-block">Fill out the on-site contact's name.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 10th Row -->

                                    <div class="row"><!-- 11th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmcontactphone" control-label>On-Site Contact Phone #</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" placeholder="000-000-0000" name="frmcontactphone" required="false">
                                            <p class="help-block">Fill out the on-site contact's phone number.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 11th Row -->

                                    <div class="modal-footer">
                                        <button type="submit"  name="submit_but1" class="btn btn-default">Next</button>
                                        <button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->
                <?php include BASEURL.INCLUDES.'/copyright.php'; ?>

            </div>

        </div>

    </div>

    <!-- This is teh alert modal if a customer is flagged. -->
    <div class="modal fade" id="alertModal" role="dialog">

    </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
