<DOCTYPE html>
<?php $counter = 0; ?>
<?php 

$order = $data['order'];
$orderedProducts = $data['orderedProducts'];
$orderType = $data['orderType'];
$driver = $data['driver'];

if($data['customer']->getId() !== null)
{
    $customer = $data['customer']->getCustomerName();
} else {
    $customer = null;
}

if($order->getDelivered() == TRUE)
{
    $status = '- Delivered';
} else {
    $status = '- Pending Delivery';
}

?>
<html>
    <head>
        <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>
        <script type="text/javascript">
            cart_order_type = <?php echo '"'.$orderType.'"'; ?>;
            rentalArray = <?php echo json_encode($data['rentArray']); ?>;
            pudArray = <?php echo json_encode($data['pudArray']); ?>;
        </script>
        <script type="text/javascript" src="<?php echo Config::get('site/siteurl').Config::get('site/resources/js').'/shoppingCart.js'; ?>" readonly="readonly"></script>

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

    </head>

    <body>

        <div id="wrapper">

            <?php include(Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'); ?>

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div class="container-fluid" id="webbg">

                <!-- 1st Row. -->
                <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <b>View Order <?= $status; ?></b>
                                </div>                                
                                <div class="panel-body">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#orderInfo">Order Info</a></li>
                                    <?php if($order->getDelivered() == TRUE): ?>
                                        <li><a data-toggle="tab" href="#deliveryInfo">Delivery Info</a></li>
                                    <?php endif; ?>
                                    <li><a data-toggle="tab" href="#orderProducts">Ordered Products</a></li>
                                </ul>

                                <div class="tab-content">
                                <div id="orderInfo" class="tab-pane fade in active">
                                </br>
                                    <!-- Need to fill in action when link is created. -->
                                    <!-- <form action="http://www.rebol.com/cgi-bin/test-cgi.cgi" id="orderForm" method="post"> -->
                                        <div class="row"><!-- 1st Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="frmorderdate">Order Date</label>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class='input-group'>
                                                            <input id="frmorderdate" name="frmorderdate" class="form-control datepicker" placeholder="YYYY-MM-DD" type="text" value="<?= $order->getDate(); ?>" readonly="readonly">
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
                                                            <input id="frmordertime" name="frmordertime" class="form-control datepicker" placeholder="00:00:00" type="text" value="<?= $order->getTime(); ?>" readonly="readonly">
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
                                            <div class="col-md-8">
                                                
                                                <input class="form-control" type="text" id="frmcustomername" name="frmcustomername" value="<?= $customer ?>" readonly="readonly">
                                                
                                                <p class="help-block">Select which customer is getting the order.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 3rd Row -->
                                    <div class="row"><!-- 4th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmorderedby" control-label>Ordered By</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmorderedby" required="false" value="<?= $order->getOrderedBy(); ?>" readonly="readonly">
                                                <p class="help-block">This field is the name of the person who requested the order.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 4th Row -->
                                    <div class="row"><!-- 5th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmordertype" control-label">Order Type</label>
                                            <div class="col-md-8">
                                                
                                                <?php 
                                                if($orderType == 'rental' || $orderType == 'Rental')
                                                {
                                                    echo '<input class="form-control" type="text" id="cart_create" name="frmordertype" value="Rental" readonly="readonly">';
                                                }
                                                elseif($orderType == 'sales' || $orderType == 'Sales')
                                                {
                                                    echo '<input class="form-control" type="text" id="cart_create" name="frmordertype" value="Sales" readonly="readonly">';
                                                }

                                                ?>
                                                
                                                <p class="help-block">Select what type of order this is.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 5th Row -->
                                    <div class="row"><!-- 6th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobname" control-label>Job Name</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobname" value="<?= $order->getJobName(); ?>" readonly="readonly">
                                            <p class="help-block">Fill out the job name if there is one.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 6th Row -->

                                    <div class="row"><!-- 7th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobaddress" control-label>Job Address</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobaddress" value="<?= $order->getJobAddress(); ?>" readonly="readonly">
                                            <p class="help-block">Fill out just the <strong>STREET</strong> address.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 7th Row -->

                                    <div class="row"><!-- 8th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobcity" control-label>Job City</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobcity" value="<?= $order->getJobCity(); ?>" readonly="readonly">
                                            <p class="help-block">Fill out the city of the job location.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 8th Row -->

                                    <div class="row"><!-- 9th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmjobzipcode" control-label>Job Zipcode</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmjobzipcode" value="<?= $order->getJobZipcode(); ?>" readonly="readonly">
                                            <p class="help-block">Fill out the zipcode of the job location.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 9th Row -->

                                    <div class="row"><!-- 9th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmtaxrate" control-label>Tax Rate</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmtaxrate" value="<?= $order->getTaxRate(); ?>" onchange="cart.getTaxRate(this.value)" readonly="readonly">
                                            <p class="help-block">Fill out the tax rate of the order.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 9th Row -->

                                    <div class="row"><!-- 10th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmcontact" control-label>On-Site Contact</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmcontact" value="<?= $order->getOnsiteContact(); ?>" readonly="readonly">
                                            <p class="help-block">Fill out the on-site contact's name.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 10th Row -->

                                    <div class="row"><!-- 11th Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4" for="frmcontactphone" control-label>On-Site Contact Phone #</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" placeholder="000-000-0000" name="frmcontactphone" required="false" value="<?= $order->getOnsiteContactPhone(); ?>" readonly="readonly">
                                            <p class="help-block">Fill out the on-site contact's phone number.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 11th Row -->                                    
                                </div>

                                <?php if($order->getDelivered() == TRUE): ?>
                                    <div id="deliveryInfo" class="tab-pane fade">
                                    </br>
                                        <div class="row"><!-- 1st Row -->
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="frmdeliverydate">Delivery Date</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class='input-group'>
                                                                <input id="frmdeliverydate" name="frmdeliverydate" class="form-control datepicker" placeholder="YYYY-MM-DD" type="text" value="<?= $order->getDateDelivered(); ?>" readonly="readonly">
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </div>
                                                            <p class="help-block">This is the date the product was delivered.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- End of 1st Row -->
                                        <div class="row"><!-- 1st Row -->
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="frmdriver">Driver</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class='input-group'>
                                                                <input id="frmdriver" name="frmdriver" class="form-control" type="text" value="<?= $driver->getFullName(); ?>" readonly="readonly">
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </div>
                                                            <p class="help-block">This is the name of the driver who delivered the container.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- End of 1st Row -->
                                        <div class="row"><!-- 1st Row -->
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="frmdrivernotes">Driver Notes:</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class='input-group'>
                                                                <textarea class="form-control" cols="500" rows="5" type="text" name="frmdrivernotes" readonly="readonly"><?= $order->getDriverNotes(); ?></textarea>
                                                                <p class="help-block">The driver's notes.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- End of 1st Row -->
                                    </div>
                                <?php endif; ?>
                                <div id="orderProducts" class="tab-pane fade">
                                    </br>
                                    <div id='cart'></div>
                                    <div id="insertCartData"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of 1st Row. -->
                </div>

                    <!-- This is the alert for when an item is added or removed from the cart. -->
                    <div id="insertAlert"></div>

                    <script type="text/javascript">
                    <?php 

                    echo 'cart.getTaxRate('.$order->getTaxRate().');';
                    
                    foreach ($orderedProducts as $oProd)
                    {

                        echo 'cart.addItem(new Product('.$oProd->getId().',"'.$oProd->getModName().'","'.$oProd->getModShortName().'","'.$oProd->getProductCost().'","'.$oProd->getRentalType().'","false"),'.$oProd->getProductQuantity().');';
                        echo "\n";
                    }

                    ?>
                    </script>
                    
                    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <!-- This is teh alert modal if a customer is flagged. -->
        <div class="modal fade" id="alertModal" role="dialog">

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

    </body>

</html>