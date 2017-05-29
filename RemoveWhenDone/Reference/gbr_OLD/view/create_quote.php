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

    $cname = "Choose One";

    $newcusturl = HTTP.HTTPURL.VIEW.'/createcustomer.php?from=create_quote';

    if(isset($_GET['cust'])) {
        $query = "SELECT * FROM customers WHERE customer_ID = ".$_GET['cust'];
        $response = @mysqli_query($dbc, $query);

        if($response) {
            while ($row = mysqli_fetch_array($response)) {
                $cname = $row['customer_name'];
            }
        }
    }

    $alertvalues = array();

    // Querry to add flagged customers to an array.
    $query = "SELECT * FROM customers WHERE flagged = 'Yes'";
    $response = @mysqli_query($dbc, $query);

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

    <script type="text/javascript">
        $(document).ready(function () {
            $('#frmquotedate').datepicker({
                format: "dd/mm/yyyy"
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
                                <b>Create Quote</b>
                            </div>
                            <div class="panel-body">
                                <form action="../model/createquote_sessions.php" method="post">
                                    <div class="row"><!-- 1st Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="frmquotedate">Quote Date</label>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class='input-group'>
                                                            <input id="frmquotedate" name="frmquotedate" class="form-control datepicker" placeholder="YYYY-MM-DD" type="text">
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="help-block">Select the date the quote was created, the format must match YYYY-MM-DD (2017-01-08 for January 8, 2017).</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 1st Row -->
                                    <div class="row"><!-- 2nd Row -->
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
                                                
                                                <p class="help-block">Select which customer is getting the quote.</p>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" onclick="location.href='<?php echo $newcusturl; ?>'" class="btn btn-default btn-gbr">New</button>
                                            </div>
                                        </div>
                                    </div><!-- End of 2nd Row -->

                                    <div class="row"><!-- 3rd Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmattn" control-label>ATTN:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmattn" required="true">
                                                <p class="help-block">This field is the persons name you would like this quote to go to.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 3rd Row -->

                                    <div class="row"><!-- 4th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmquotetype" control-label">Quote Type</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="frmquotetype" id="frmquotetype">
                                                    <option>Select One</option>
                                                    <option>Rental</option>
                                                    <option>Resale</option>
                                                    <option>Sales</option>
                                                </select>
                                                <p class="help-block">Select what type of quote this is.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 4th Row -->
                                    <div class="row"><!-- 5th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmquotestatus" control-label">Quote Status</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="frmquotestatus" id="frmquotestatus">
                                                    <option>Select One</option>
                                                    <option>Open</option>
                                                    <option>Closed</option>
                                                </select>
                                                <p class="help-block">Select the status of the quote.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 5th Row -->
                                    <div class="row"><!-- 6th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobname" control-label>Job Name</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobname" required="true">
                                            <p class="help-block">Fill out the job name if there is one.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 6th Row -->

                                    <div class="row"><!-- 7th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobaddress" control-label>Job Address</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobaddress" required="true">
                                            <p class="help-block">Fill out just the <strong>STREET</strong> address.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 7th Row -->

                                    <div class="row"><!-- 8th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobcity" control-label>Job City</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobcity" required="true">
                                            <p class="help-block">Fill out the city of the job location.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 8th Row -->

                                    <div class="row"><!-- 9th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobzipcode" control-label>Job Zipcode</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobzipcode" required="true">
                                            <p class="help-block">Fill out the zipcode of the job location.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 9th Row -->

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
