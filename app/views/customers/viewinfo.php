<?php

    $quote_count = 0;
    $purchase_count = 0;
    $rental_count = 0;
    $total_amount = 0;
    $rental_amount = 0;
    $rental_total_amount = 0;
    
    $customer = $data['customer'];
    $quoteList = $data['quoteList'];
    $orderList = $data['orderList'];
    $rentalList = $data['rentalList'];
    $url = Config::get('site/selfurl').'?action=update';
    $main_website = Config::get('site/http').Config::get('site/httpurl');

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

    <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>

    <?php
        if($customer->getFlag() == 'Yes'){
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

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'); ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">
            <form action="<?php echo $url; ?>" method="post">
            <input class="form-control" type="hidden" name="frmcid" value="<?php echo $customer->getId(); ?>">
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
                                            <input class="form-control" type="text" name="frmcname" required="true" value="<?php echo $customer->getCustomerName(); ?>">
                                            <p class="help-block">This field is the customers first and last name or company name.</p>
                                        </div>
                                        <label class="col-md-2" for="frmcname" control-label>Address:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcaddy" value="<?php echo $customer->getCustomerAddress1(); ?>">
                                            <input class="form-control" type="text" name="frmcaddy2" value="<?php echo $customer->getCustomerAddress2(); ?>">
                                            <p class="help-block">This field is the first and second line of the customers address.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcpnumber" control-label>Phone Number:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcpnumber" value="<?php echo $customer->getCustomerPhone(); ?>">
                                            <p class="help-block">This field is the customers phone number.</p>
                                        </div>
                                        <label class="col-md-2" for="frmccity" control-label>City:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmccity" value="<?php echo $customer->getCustomerCity(); ?>">
                                            <p class="help-block">This field is the city of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcfnumber" control-label>Fax Number:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcfnumber" value="<?php echo $customer->getCustomerFax(); ?>">
                                            <p class="help-block">This field is the customers fax number.</p>
                                        </div>
                                        <label class="col-md-2" for="frmcstate" control-label>State:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcstate" value="<?php echo $customer->getCustomerState(); ?>">
                                            <p class="help-block">This field is the state of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcemail" control-label>E-Mail:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcemail" value="<?php echo $customer->getCustomerEmail(); ?>">
                                            <p class="help-block">This field is the customers e-mail address.</p>
                                        </div>
                                        <label class="col-md-2" for="frmczipcode" control-label>Zipcode:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmczipcode" value="<?php echo $customer->getCustomerZipcode(); ?>">
                                            <p class="help-block">This field is the zipcode of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcrdp" control-label>RDP:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcrdp" value="<?php echo $customer->getCustomerRdp(); ?>">
                                            <p class="help-block">This field is the customers RDP.</p>
                                        </div>
                                        <div class="col-md-6" style="float: right;">
                                            <button type="button" onclick="location.href='<?php echo $main_website.'/orders/create/sales?cust='.$customer->getId(); ?>'" class="btn btn-default form-button btn-gbr" style="float: right;margin-left: 10px;">Create Sales Order</button>
                                            <button type="button" onclick="location.href='<?php echo $main_website.'/orders/create/rental?cust='.$customer->getId(); ?>'" class="btn btn-default form-button btn-gbr" style="float: right;margin-left: 10px;">Create Rental Order</button>
                                            <button type="button" onclick="location.href='<?php echo $main_website.'/quotes/create/sales?cust='.$customer->getId(); ?>'" class="btn btn-default form-button btn-gbr" style="float: right;margin-left: 10px;">Create Sales Quote</button>
                                            <button type="button" onclick="location.href='<?php echo $main_website.'/quotes/create/rental?cust='.$customer->getId(); ?>'" class="btn btn-default form-button btn-gbr" style="float: right;margin-left: 10px;">Create Rental Quote</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcrdp" control-label>Flagged?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="frmflaggedq" id="frmflaggedq" required="true">
                                                <?php
                                                    echo '<option>' . $customer->getFlag() . '</option>';
                                                    if($customer->getFlag() == "Yes"){
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
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="frmflagreason"><?php echo $customer->getFlagReason(); ?></textarea>
                                            <p class="help-block">This field tells us why the customer is flagged or not.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcnotes" control-label>Notes:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="frmcnotes"><?php echo $customer->getCustomerNotes(); ?></textarea>
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

                                                if(!empty($quoteList)){
                                                    // List out each and every purchase made by the customer.
                                                    foreach($quoteList as $quote){
                                                        $quote_count += 1;
                                                        $total_amount += $quote->getTotalCost();
                                                        echo '

                                                       <tr>
                                                            <td>' . $quote_count . '</td>
                                                            <td>' . $quote->getDate() . '</td>
                                                            <td>' . $quote->getId() . '</td>
                                                            <td>' . $quote->getType() . '</td>
                                                            <td>' . $quote->getStatus() . '</td>
                                                            <td>' . $quote->getTotalCost() . '</td>
                                                            <td><a class="containerlink" href="/reproquote.php?quote_id='.$quote->getId().'">View Details</a></td>
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

                                        if(!empty($orderList)){
                                            // List out each and every purchase made by the customer.
                                            foreach($orderList as $order){
                                                $purchase_count += 1;
                                                $total_amount += $order->getTotalCost();
                                                echo '

                                               <tr>
                                                    <td>' . $purchase_count . '</td>
                                                    <td>' . $order->getOrderDate() . '</td>
                                                    <td>' . $order->getId() . '</td>
                                                    <td>' . $order->getTotalCost() . '</td>
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
            
                                        if(!empty($rentalList)){
                                            // List out each and every purchase made by the customer.
                                            foreach($rentalList as $rental){

                                                $start_date = $rental->getOrderDate();
                                                $now = new DateTime();
                                                $now->format('Y-m-d H:i:s');
                                                $now->setTimezone(new DateTimeZone('America/Los_Angeles'));
                                                $now->getTimestamp();
                                                $monthCount = nb_mois($start_date, $now);
                                                $monthCounter = 1;
                                                
                                                while ($monthCounter <= $monthCount){
                                                    $rental_amount += $rental->getMonthlyTotal();
                                                    $monthCounter++;
                                                }
                                                if($monthCount == 0){
                                                    $rental_amount = $rental->getMonthlyTotal();
                                                }
                                                
                                                $rental_count += 1;

                                                $rental_total_amount += $rental_amount;
                                                echo '

                                               <tr>
                                                    <td>' . $rental_count . '</td>
                                                    <td>' . $rental->getOrderDate() . '</td>
                                                    <td>' . $rental->getId() . '</td>
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
                
                <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

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
                                <p>The customer (<?php echo $customer->getCustomerName(); ?>) has a current flag on his account!</p>
                                <p>Flag Reason: <?php echo $customer->getFlagReason(); ?></p>
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

    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

</body>

</html>
