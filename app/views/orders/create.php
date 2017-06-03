<DOCTYPE html>
<?php $counter = 0; ?>
<html>
    <head>
        <?php require_once(BASEURL.APP.ASSETS.'/header.php'); ?>
        <script type="text/javascript">
            cart_order_type = <?php echo '"'.$data['order_type'].'"'; ?>;
        </script>
        <script type="text/javascript" src="<?php echo HTTP.HTTPURL.PUB.JS.'/shoppingCart.js'; ?>"></script>

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



<!--     <script type="text/javascript">

        $(document).ready(function () {
            $('#frmcustomername').change(function(){
                var value = $(this).val();
                var jqueryarray = <php echo json_encode($alertvalues); ?>;
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

    </script> -->

    </head>

    <body>

        <div id="wrapper">

            <?php include(BASEURL.APP.ASSETS.'/fixednavbar.php'); ?>

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div class="container-fluid" id="webbg">
                    <!-- 1st Row. -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <b>Create Order</b>
                                </div>
                                <div class="panel-body">
                                    <!-- Need to fill in action when link is created. -->
                                    <!-- <form action="http://www.rebol.com/cgi-bin/test-cgi.cgi" id="orderForm" method="post"> -->
                                    <form action="<?php echo HTTP.HTTPURL.PUB.ORDERS.'/create/sales/create'; ?>" id="orderForm" method="post">
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
                                                        <!-- Need to implement customer auto chosen when from create customer! -->
                                                        <!-- <option>php echo $cname; ?></option> -->
                                                        <option>Choose One</option>
                                                        <!-- PHP to select customers names! -->
                                                        <?php         

                                                            foreach($data['custList'] as $customer) {

                                                                echo '
                                                                    <option value="' . $customer->customer_name . '">'. $customer->customer_name .'</option>
                                                                ';
                                                            }
                                                        ?>
                                                    </select>
                                                    
                                                    <p class="help-block">Select which customer is getting the order.</p>
                                                </div>
                                                <div class="col-md-1">
                                                    <!-- <button type="button" onclick="location.href='php echo $newcusturl; ?>'" class="btn btn-default btn-gbr">New</button> -->
                                                    <button type="button" onclick="" class="btn btn-default btn-gbr">New</button>
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
                                                    <select class="form-control" name="frmordertype" id="frmordertype" disabled>
                                                    <?php 

                                                    if($data['order_type']=='rental')
                                                    {
                                                        echo '<option value="Rental" id="cart_create" selected>Rental</option>';
                                                    }
                                                    elseif($data['order_type']=='sales')
                                                    {
                                                        echo '<option value="Sales" id="cart_create" selected>Sales</option>';
                                                    }

                                                    ?>
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

                                        <div class="row"><!-- 9th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmtaxrate" control-label>Tax Rate</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmtaxrate" onchange="cart.getTaxRate(this.value)">
                                                <p class="help-block">Fill out the tax rate of the order.</p>
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
                                                            <input type="button" onclick='cart.postData();' class="btn btn-gbr" value="Submit Order"/>
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
                                                                                <td width="250"><?php echo $shippingProducts->mod_name; ?></td> <!-- Long Name -->
                                                                                <td width="250">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
                                                                                        <input type="text" id="shippingCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php echo $shippingProducts->mod_cost; ?>"/>
                                                                                    </div>
                                                                                </td> <!-- Cost -->
                                                                                <td width="250"><input type="text" id="shippingQty<?= $counter ?>" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
                                                                                <td width="250"><input type="button" onclick='cart.addItem(new Product(<?= $shippingProducts->id ?>, "<?= $shippingProducts->mod_name ?>", "<?= $shippingProducts->mod_short_name ?>", document.getElementById("shippingCost<?= $counter ?>").value, "<?= $shippingProducts->rental_type ?>"), document.getElementById("shippingQty<?= $counter ?>").value);' class="btn btn-gbr" value="Add To Order"/></td> <!-- Add To Order Button -->
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
                                                                                <td width="250"><?php echo $containerProducts->mod_name; ?></td> <!-- Long Name -->
                                                                                <td width="250">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
                                                                                        <input type="text" id="containerCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php if($data['order_type'] == 'rental'){echo $containerProducts->monthly;}else{echo $containerProducts->mod_cost;} ?>"/>
                                                                                    </div>
                                                                                </td> <!-- Cost -->
                                                                                <td width="250"><input type="text" id="containerQty<?= $counter ?>" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
                                                                                <td width="250"><input type="button" onclick='cart.addItem(new Product(<?= $containerProducts->id ?>, "<?= $containerProducts->mod_name ?>", "<?= $containerProducts->mod_short_name ?>", document.getElementById("containerCost<?= $counter ?>").value, "<?= $containerProducts->rental_type ?>"), document.getElementById("containerQty<?= $counter ?>").value);' class="btn btn-gbr" value="Add To Order"/></td> <!-- Add To Order Button -->
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
                                                                                <td width="250"><?php echo $modificationProducts->mod_name; ?></td> <!-- Long Name -->
                                                                                <td width="250">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
                                                                                        <input type="text" id="modCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php if($data['order_type'] == 'rental'){echo $modificationProducts->monthly;}else{echo $modificationProducts->mod_cost;} ?>"/>
                                                                                    </div>
                                                                                </td> <!-- Cost -->
                                                                                <td width="250"><input id="modQty<?= $counter ?>" type="text" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
                                                                                <td width="250"><input type="button" onclick='cart.addItem(new Product(<?= $modificationProducts->id ?>, "<?= $modificationProducts->mod_name ?>", "<?= $modificationProducts->mod_short_name ?>", document.getElementById("modCost<?= $counter ?>").value, "<?= $modificationProducts->rental_type ?>"), document.getElementById("modQty<?= $counter ?>").value);' class="btn btn-gbr" value="Add To Order"/></td> <!-- Add To Order Button -->
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
                    
                    <?php include(BASEURL.APP.ASSETS.'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <!-- This is teh alert modal if a customer is flagged. -->
        <div class="modal fade" id="alertModal" role="dialog">

        <?php include(BASEURL.APP.ASSETS.'/botjsincludes.php'); ?>

    </body>

</html>