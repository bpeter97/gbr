<DOCTYPE html>
<?php $counter = 0; ?>
<?php 

$order = $data['order'];
$orderedProducts = $data['orderedProducts'];
$orderType = $data['orderType'];

if($data['customer']->getId() !== null)
{
    $customer = $data['customer']->getCustomerName();
} else {
    $customer = null;
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
        <script type="text/javascript" src="<?php echo Config::get('site/siteurl').Config::get('site/resources/js').'/shoppingCart.js'; ?>"></script>

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
                                    <b>Edit Order</b>
                                </div>
                                <div class="panel-body">
                                    <!-- Need to fill in action when link is created. -->
                                    <!-- <form action="http://www.rebol.com/cgi-bin/test-cgi.cgi" id="orderForm" method="post"> -->
                                    <form action="<?php echo Config::get('site/siteurl').Config::get('site/orders').'/update'; ?>" id="orderForm" method="post">
                                    <input class="form-control" type="hidden" name="orderid" value="<?= $order->getId(); ?>">
                                    <input class="form-control" type="hidden" name="frmstage" value="1">
                                        <div class="row"><!-- 1st Row -->
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="frmorderdate">Order Date</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class='input-group'>
                                                                <input id="frmorderdate" name="frmorderdate" class="form-control datepicker" placeholder="YYYY-MM-DD" type="text" value="<?= $order->getDate(); ?>">
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
                                                                <input id="frmordertime" name="frmordertime" class="form-control datepicker" placeholder="00:00:00" type="text" value="<?= $order->getTime(); ?>">
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
                                                   
                                                    <input class="form-control" type="text" id="frmcustomername" name="frmcustomername" value="<?= $customer ?>" readonly="readonly">
                                                    
                                                    <p class="help-block">Select which customer is getting the order.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 3rd Row -->
                                        <div class="row"><!-- 4th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmorderedby" control-label>Ordered By</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmorderedby" required="false" value="<?= $order->getOrderedBy(); ?>">
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
                                                    <input class="form-control" type="text" name="frmjobname" value="<?= $order->getJobName(); ?>">
                                                <p class="help-block">Fill out the job name if there is one.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 6th Row -->

                                        <div class="row"><!-- 7th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmjobaddress" control-label>Job Address</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmjobaddress" value="<?= $order->getJobAddress(); ?>">
                                                <p class="help-block">Fill out just the <strong>STREET</strong> address.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 7th Row -->

                                        <div class="row"><!-- 8th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmjobcity" control-label>Job City</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmjobcity" value="<?= $order->getJobCity(); ?>">
                                                <p class="help-block">Fill out the city of the job location.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 8th Row -->

                                        <div class="row"><!-- 9th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmjobzipcode" control-label>Job Zipcode</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmjobzipcode" value="<?= $order->getJobZipcode(); ?>">
                                                <p class="help-block">Fill out the zipcode of the job location.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 9th Row -->

                                        <div class="row"><!-- 9th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmtaxrate" control-label>Tax Rate</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmtaxrate" value="<?= $order->getTaxRate(); ?>" onchange="cart.getTaxRate(this.value)">
                                                <p class="help-block">Fill out the tax rate of the order.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 9th Row -->

                                        <div class="row"><!-- 10th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmcontact" control-label>On-Site Contact</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmcontact" value="<?= $order->getOnsiteContact(); ?>">
                                                <p class="help-block">Fill out the on-site contact's name.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 10th Row -->

                                        <div class="row"><!-- 11th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmcontactphone" control-label>On-Site Contact Phone #</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" placeholder="000-000-0000" name="frmcontactphone" required="false" value="<?= $order->getOnsiteContactPhone(); ?>">
                                                <p class="help-block">Fill out the on-site contact's phone number.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 11th Row -->


                        <!-- ################################## Beginning of Shopping Cart ################################## -->


                                        <!-- Delivery and Pickup products. -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center">
                                                        <b>Current Cart</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div id='cart'></div>
                                                        <div class="text-center">
                                                            <input type="button" onclick="cart.postData();" class="btn btn-gbr" value="Submit Order"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- End of Delivery and Pickup products. -->
                                        <div id="insertCartData"></div>
                                    </form>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center">
                                                    <b>Products</b>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active"><a data-toggle="tab" class="gbr-header product-tab" href="#shippingProducts"><strong>Deliver/Pickup</strong></a></li>
                                                        <li><a data-toggle="tab" class="gbr-header product-tab" href="#containerProducts"><strong>Containers</strong></a></li>
                                                        <li><a data-toggle="tab" class="gbr-header product-tab" href="#modificationProducts"><strong>Modifications</strong></a></li>
                                                    </ul>

                                                    <div class="tab-content">
                                                        <div id="shippingProducts" class="tab-pane fade in active">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item Name</th>
                                                                        <th>Cost</th>
                                                                        <th>Quantity</th>
                                                                        <th>Add To Order</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php

                                                                        foreach ($data['shippingProducts'] as $shippingProducts) {

                                                                        ?>
                                                                            <tr>
                                                                                <!-- Short Name -->
                                                                                <td width="250"><?php echo $shippingProducts->getModName(); ?></td> <!-- Long Name -->
                                                                                <td width="250">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
                                                                                        <input type="text" id="shippingCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php echo $shippingProducts->getModCost(); ?>"/>
                                                                                    </div>
                                                                                </td> <!-- Cost -->
                                                                                <td width="250"><input type="text" id="shippingQty<?= $counter ?>" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
                                                                                <td width="250"><input type="button" onclick='cart.addItem(new Product(<?= $shippingProducts->getId() ?>, "<?= $shippingProducts->getModName() ?>", "<?= $shippingProducts->getModShortName() ?>", document.getElementById("shippingCost<?= $counter ?>").value, "<?= $shippingProducts->getRentalType() ?>"), document.getElementById("shippingQty<?= $counter ?>").value);' class="btn btn-gbr" value="Add To Order"/></td> <!-- Add To Order Button -->
                                                                            </tr>
                                                                        <?php
                                                                        $counter++;
                                                                        }
                                                                        $counter=0;
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div id="containerProducts" class="tab-pane fade in">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item Name</th>
                                                                        <th>Cost</th>
                                                                        <th>Quantity</th>
                                                                        <th>Add To Order</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        foreach ($data['containerProducts'] as $containerProducts) {

                                                                        ?>
                                                                            <tr>
                                                                                <!-- Short Name -->
                                                                                <td width="250"><?php echo $containerProducts->getModName(); ?></td> <!-- Long Name -->
                                                                                <td width="250">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
                                                                                        <input type="text" id="containerCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php if($orderType == 'rental' || $orderType == 'Rental'){echo $containerProducts->getMonthly();}else{echo $containerProducts->getModCost();} ?>"/>
                                                                                    </div>
                                                                                </td> <!-- Cost -->
                                                                                <td width="250"><input type="text" id="containerQty<?= $counter ?>" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
                                                                                <td width="250"><input type="button" onclick='cart.addItem(new Product(<?= $containerProducts->getId() ?>, "<?= $containerProducts->getModName() ?>", "<?= $containerProducts->getModShortName() ?>", document.getElementById("containerCost<?= $counter ?>").value, "<?= $containerProducts->getRentalType() ?>"), document.getElementById("containerQty<?= $counter ?>").value);' class="btn btn-gbr" value="Add To Order"/></td> <!-- Add To Order Button -->
                                                                            </tr>
                                                                        <?php
                                                                        $counter++;
                                                                        }
                                                                        $counter=0;
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div id="modificationProducts" class="tab-pane fade in">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item Name</th>
                                                                        <th>Cost</th>
                                                                        <th>Quantity</th>
                                                                        <th>Add To Order</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($data['modificationProducts'] as $modificationProducts) { ?>
                                                                            <tr>
                                                                                <!-- Short Name -->
                                                                                <td width="250"><?php echo $modificationProducts->getModName(); ?></td> <!-- Long Name -->
                                                                                <td width="250">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
                                                                                        <input type="text" id="modCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php if($orderType == 'rental' || $orderType == 'Rental'){echo $modificationProducts->getMonthly();}else{echo $modificationProducts->getModCost();} ?>"/>
                                                                                    </div>
                                                                                </td> <!-- Cost -->
                                                                                <td width="250"><input id="modQty<?= $counter ?>" type="text" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
                                                                                <td width="250"><input type="button" onclick='cart.addItem(new Product(<?= $modificationProducts->getId() ?>, "<?= $modificationProducts->getModName() ?>", "<?= $modificationProducts->getModShortName() ?>", document.getElementById("modCost<?= $counter ?>").value, "<?= $modificationProducts->getRentalType() ?>"), document.getElementById("modQty<?= $counter ?>").value);' class="btn btn-gbr" value="Add To Order"/></td> <!-- Add To Order Button -->
                                                                            </tr>
                                                                        <?php
                                                                        $counter++;
                                                                        }
                                                                        $counter=0;
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of 1st Row. -->

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