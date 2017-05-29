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
    $rental_count = 0;
    $total_amount = 0;
    $rental_amount = 0;
    $rental_total_amount = 0;

    // Check to see if the customer ID was sent, else return to customers page.
    if(isset($_GET['id'])) {
        $customer_ID = $_GET['id'];
    } else {
        header('location: '.HTTP.HTTPURL.VIEW.'/customers.php');
    }

    // Select customer information from database based on customer ID.
    $db->sql("SELECT customer_name, customer_address1, customer_address2, customer_phone, customer_ext, customer_fax, customer_email, customer_rdp, customer_notes, customer_zipcode, customer_state, customer_city, flagged, flag_reason FROM customers WHERE customer_ID=$customer_ID");
    $customer_response = $db->getResult();

    // Assign variables to results.
    foreach($customer_response as $cust){
        $customer_name = $cust['customer_name'];
        $customer_address1 = $cust['customer_address1'];
        $customer_address2 = $cust['customer_address2'];
        $customer_city = $cust['customer_city'];
        $customer_state = $cust['customer_state'];
        $customer_zipcode = $cust['customer_zipcode'];
        $customer_phone = $cust['customer_phone'];
        $customer_ext = $cust['customer_ext'];
        $customer_fax = $cust['customer_fax'];
        $customer_email = $cust['customer_email'];
        $customer_rdp = $cust['customer_rdp'];
        $customer_notes = $cust['customer_notes'];
        $customer_flagged = $cust['flagged'];
        $customer_flag_reason = $cust['flag_reason'];
    }

    // Grabbing information for the purchase history.
    $db->sql("SELECT quote_date, quote_id, quote_type, total_cost, quote_status FROM quotes WHERE quote_customer='$customer_name'");
    $quote_response = $db->getResult();

    // Grabbing information for the purchase history.
    $db->sql("SELECT * FROM orders WHERE order_customer='$customer_name' AND order_type = 'Sales' OR order_customer = '$customer_name' AND order_type = 'Resale'");
    $purchase_response = $db->getResult();

    $db->sql("SELECT * FROM orders WHERE order_customer='$customer_name' AND order_type = 'Rental'");
    $rental_response = $db->getResult();

    // This function is solely for getting the # of months between two dates.
    function nb_mois($date1, $date2)
    {
        $begin = new DateTime( $date1 );
        $end = $date2;
        $end = $end->modify('-1 month');

        $interval = DateInterval::createFromDateString('1 month');

        $period = new DatePeriod($begin, $interval, $end);
        $counter = 0;
        foreach($period as $dt) {
            $counter++;
        }

        return $counter;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.MODEL.'/header.php'); ?>

    <?php
        if($customer_flagged == 'Yes'){
            echo '

                <script type="text/javascript">

                    $(document).ready(function(){
                        $("#alertModal").modal("show");
                    });

                </script>

            ';
        }
    ?>
    

</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.INCLUDES.'/fixednavbar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">
            <form action="<?php echo HTTP.HTTPURL.CONTROLLERS.'/editcustomer.php'; ?>" method="post">
            <input class="form-control" type="hidden" name="frmcid" value="<?php echo $customer_ID; ?>">
                <!-- End of 1st Row. -->
                <!-- 2nd Row. -->
                <div class="row">
                
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Customer Information</b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcname" control-label>Customer Name:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcname" required="true" value="<?php echo $customer_name ?>">
                                            <p class="help-block">This field is the customers first and last name or company name.</p>
                                        </div>
                                        <label class="col-md-2" for="frmcname" control-label>Address:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcaddy" value="<?php echo $customer_address1 ?>">
                                            <input class="form-control" type="text" name="frmcaddy2" value="<?php echo $customer_address2 ?>">
                                            <p class="help-block">This field is the first and second line of the customers address.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcpnumber" control-label>Phone Number:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcpnumber" value="<?php echo $customer_phone ?>">
                                            <p class="help-block">This field is the customers phone number.</p>
                                        </div>
                                        <label class="col-md-2" for="frmccity" control-label>City:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmccity" value="<?php echo $customer_city ?>">
                                            <p class="help-block">This field is the city of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcfnumber" control-label>Fax Number:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcfnumber" value="<?php echo $customer_fax ?>">
                                            <p class="help-block">This field is the customers fax number.</p>
                                        </div>
                                        <label class="col-md-2" for="frmcstate" control-label>State:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcstate" value="<?php echo $customer_state ?>">
                                            <p class="help-block">This field is the state of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcemail" control-label>E-Mail:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcemail" value="<?php echo $customer_email ?>">
                                            <p class="help-block">This field is the customers e-mail address.</p>
                                        </div>
                                        <label class="col-md-2" for="frmczipcode" control-label>Zipcode:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmczipcode" value="<?php echo $customer_zipcode ?>">
                                            <p class="help-block">This field is the zipcode of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcrdp" control-label>RDP:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcrdp" value="<?php echo $customer_rdp ?>">
                                            <p class="help-block">This field is the customers RDP.</p>
                                        </div>
                                        <div class="col-md-6" style="float: right;">
                                            <button type="button" onclick="location.href='<?php echo HTTP.HTTPURL.VIEW.'/createorder.php?cust='.$customer_ID; ?>'" class="btn btn-default form-button btn-gbr" style="float: right;margin-left: 10px;">Create Order</button>
                                            <button type="button" onclick="location.href='<?php echo HTTP.HTTPURL.VIEW.'/create_quote.php?cust='.$customer_ID; ?>'" class="btn btn-default form-button btn-gbr" style="float: right;">Create Quote</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcrdp" control-label>Flagged?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="frmflaggedq" id="frmflaggedq" required="true">
                                                <?php
                                                    echo '<option>' . $customer_flagged . '</option>';
                                                    if($customer_flagged == "Yes"){
                                                        echo "<option>No</option>";
                                                    } else {
                                                        echo "<option>Yes</option>";
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">This field is to let us know if the customer is flagged or not.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcrdp" control-label>Flag Reason:</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="frmflagreason"><?php echo $customer_flag_reason; ?></textarea>
                                            <p class="help-block">This field tells us why the customer is flagged or not.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcnotes" control-label>Notes:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="frmcnotes"><?php echo $customer_notes ?></textarea>
                                            <p class="help-block">This field is the customers notes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->

                <!-- 3rd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>History</b>
                            </div>
                            <div class="panel-body">

                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#qhistory">Quotes</a></li>
                                    <li><a data-toggle="tab" href="#phistory">Purchase History</a></li>
                                    <li><a data-toggle="tab" href="#rhistory">Rental History</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="qhistory" class="tab-pane fade in active">
                                        <table class="table table-striped table-hover">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Quote #</th>
                                                <th>Sales or Rental</th>
                                                <th>Quote Status</th>
                                                <th>Total Cost</th>
                                                <th>View Details</th>
                                            </tr>
                                            <?php

                                                if($quote_response){
                                                    // List out each and every purchase made by the customer.
                                                    foreach($quote_response as $quote){
                                                        $quote_count += 1;
                                                        $total_amount += $quote['total_cost'];
                                                        echo '

                                                       <tr>
                                                            <td>' . $quote_count . '</td>
                                                            <td>' . $quote["quote_date"] . '</td>
                                                            <td>' . $quote["quote_id"] . '</td>
                                                            <td>' . $quote["quote_type"] . '</td>
                                                            <td>' . $quote["quote_status"] . '</td>
                                                            <td>' . $quote["total_cost"] . '</td>
                                                            <td><a class="containerlink" href="'.HTTP.HTTPURL.VIEW.'/reproquote.php?quote_id='.$quote["quote_id"].'">View Details</a></td>
                                                        </tr>

                                                        ';
                                                    }
                                                    echo "
                                                        <tr>
                                                            <td><strong>Total:</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><strong>" . $total_amount . "</strong></td>
                                                            <td></td>
                                                        </tr>
                                                    ";
                                                } else {

                                                    echo "

                                                        <tr>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                        </tr>

                                                    ";
                                                }
                                            ?>
                                        </table>
                                    </div>

                                    <div id="phistory" class="tab-pane fade in">
                                        <table class="table table-striped table-hover">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Order #</th>
                                                <th>Total Cost</th>
                                                <th>View Details</th>
                                            </tr>
                                        <?php

                                        if($purchase_response){
                                            // List out each and every purchase made by the customer.
                                            foreach($purchase_response as $order){
                                                $purchase_count += 1;
                                                $total_amount += $order['total_cost'];
                                                echo '

                                               <tr>
                                                    <td>' . $purchase_count . '</td>
                                                    <td>' . $order["order_date"] . '</td>
                                                    <td>' . $order["order_id"] . '</td>
                                                    <td>' . $order["total_cost"] . '</td>
                                                    <td><a class="containerlink" href="#">View Details</a></td>
                                                </tr>

                                                ';
                                            }
                                            echo "
                                                <tr>
                                                    <td><strong>Total:</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>" . $total_amount . "</strong></td>
                                                    <td></td>
                                                </tr>
                                            ";
                                        } else {

                                            echo "

                                                <tr>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                </tr>

                                            ";
                                        }
                                        ?>

                                        </table>

                                    </div>
                                    <div id="rhistory" class="tab-pane fade in">

                                        <table class="table table-striped table-hover">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Order #</th>
                                                <th>Total Cost</th>
                                                <th>View Details</th>
                                            </tr>
                                        <?php
            
                                        if($rental_response){
                                            // List out each and every purchase made by the customer.
                                            foreach($rental_response as $rental){

                                                $start_date = $rental['order_date'];
                                                $now = new DateTime();
                                                $now->format('Y-m-d H:i:s');
                                                $now->setTimezone(new DateTimeZone('America/Los_Angeles'));
                                                $now->getTimestamp();
                                                $monthCount = nb_mois($start_date, $now);
                                                $monthCounter = 1;
                                                
                                                while ($monthCounter <= $monthCount){
                                                    $rental_amount += $rental['monthly_total'];
                                                    $monthCounter++;
                                                }
                                                if($monthCount == 0){
                                                    $rental_amount = $rental['monthly_total'];
                                                }
                                                
                                                $rental_count += 1;

                                                $rental_total_amount += $rental_amount;
                                                echo '

                                               <tr>
                                                    <td>' . $rental_count . '</td>
                                                    <td>' . $rental["order_date"] . '</td>
                                                    <td>' . $rental["order_id"] . '</td>
                                                    <td>' . $rental_amount . '</td>
                                                    <td><a class="containerlink" href="#">View Details</a></td>
                                                </tr>

                                                ';
                                            }
                                            echo "
                                                <tr>
                                                    <td><strong>Total:</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>" . $rental_total_amount . ".00</strong></td>
                                                    <td></td>
                                                </tr>
                                            ";
                                        } else {

                                            echo "

                                                <tr>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                </tr>

                                            ";
                                        }
                                        ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 3rd Row. -->

                <!-- 4th Row - Save Changes/Go Back Buttons -->
                <div class="navbar navbar-default gbr-bar" style="text-align: center; margin-bottom: 18px;">
                        <button type="submit" class="btn btn-default form-button" style="margin-top: 7px;">Save Changes</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default form-button" style="margin-top: 7px;">Go Back</button>
                </div>
                
                <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>

                <!-- If the customer is flagged this modal will appear. -->
                <div class="modal fade" id="alertModal" role="dialog">                
                    <div class="modal-dialog"> 
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">!!! ALERT !!!</h4>
                            </div>
                            <div class="modal-body">
                                <p>The customer (<?php echo $customer_name; ?>) has a current flag on his account!</p>
                                <p>Flag Reason: <?php echo $customer_flag_reason; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>

                </div>

                </form>

                

            </div>
            
        </div>

    </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
