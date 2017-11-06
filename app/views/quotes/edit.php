<DOCTYPE html>
<?php $counter = 0; ?>
<?php 

$quote = $data['quote'];
$quotedProducts = $quote->getQuoteProducts();
$quoteType = $data['quoteType'];

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
            cart_order_type = <?php echo '"'.$quoteType.'"'; ?>;
        </script>
        <script type="text/javascript" src="<?php echo Config::get('site/siteurl').Config::get('site/resources/js').'/shoppingCart.js'; ?>"></script>

        <?php 
        echo '
        <script type="text/javascript">
            $(document).ready(function(){
                var date_input=$(\'input[name="frmquotedate"]\');
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
                                    <b>Create Quote</b>
                                </div>
                                <div class="panel-body">
                                    <!-- <form action="http://www.rebol.com/cgi-bin/test-cgi.cgi" id="orderForm" method="post"> -->
                                    <form action="<?php echo Config::get('site/siteurl').Config::get('site/quotes').'/update' ?>" id="orderForm" method="post">
                                    <input class="form-control" type="hidden" name="quoteid" value="<?= $quote->getId(); ?>">
                                        <div class="row"><!-- 1st Row -->
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="frmquotedate">Quote Date</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class='input-group'>
                                                                <input id="frmquotedate" name="frmquotedate" class="form-control datepicker" placeholder="YYYY-MM-DD" type="text" value="<?= $quote->getDate(); ?>">
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
                                        <div class="row"><!-- 3rd Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmcustomername" control-label">Select a Customer</label>
                                                <div class="col-md-7">
                                                    <?php
                                                    echo '<input class="form-control" type="text" id="frmcustomername" name="frmcustomername" value="'. $customer .'" readonly="readonly">';
                                                    ?>
                                                    
                                                    <p class="help-block">Select which customer is getting the quote.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 3rd Row -->
                                        <div class="row"><!-- 4th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmattn" control-label>Attention</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmattn" required="false" value="<?= $quote->getAttention(); ?>">
                                                    <p class="help-block">This field is the name of the person who requested the quote.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 4th Row -->
                                        <div class="row"><!-- 5th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmquotetype" control-label>Quote Type</label>
                                                <div class="col-md-8">
                                                    <?php 
                                                    if($quoteType == 'Rental' || $quoteType == 'rental')
                                                    {
                                                        echo '<input class="form-control" type="text" id="cart_create" name="frmquotetype" value="Rental" readonly="readonly">';
                                                    }
                                                    elseif($quoteType =='Sales')
                                                    {
                                                        echo '<input class="form-control" type="text" id="cart_create" name="frmquotetype" value="Sales" readonly="readonly">';
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
                                                    <input class="form-control" type="text" name="frmjobname" value="<?= $quote->getJobName(); ?>">
                                                <p class="help-block">Fill out the job name if there is one.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 6th Row -->

                                        <div class="row"><!-- 7th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmjobaddress" control-label>Job Address</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmjobaddress" value="<?= $quote->getJobAddress(); ?>">
                                                <p class="help-block">Fill out just the <strong>STREET</strong> address.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 7th Row -->

                                        <div class="row"><!-- 8th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmjobcity" control-label>Job City</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmjobcity" value="<?= $quote->getJobCity(); ?>">
                                                <p class="help-block">Fill out the city of the job location.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 8th Row -->

                                        <div class="row"><!-- 9th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmjobzipcode" control-label>Job Zipcode</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmjobzipcode" value="<?= $quote->getJobZipcode(); ?>">
                                                <p class="help-block">Fill out the zipcode of the job location.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 9th Row -->

                                        <div class="row"><!-- 9th Row -->
                                            <div class="col-lg-12">
                                                <label class="col-md-4" for="frmtaxrate" control-label>Tax Rate</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" name="frmtaxrate" value="<?= $quote->getTaxRate(); ?>" onchange="cart.getTaxRate(this.value)">
                                                <p class="help-block">Fill out the tax rate of the order.</p>
                                                </div>
                                            </div>
                                        </div><!-- End of 9th Row -->


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
                                                            <input type="button" onclick="cart.postData();" class="btn btn-gbr" value="Submit Quote"/>
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
                                                                                <!-- Quantity -->
                                                                                <td width="250"><input type="text" id="shippingQty<?= $counter ?>" name="quantity" value="1" size="2"/></td> 
                                                                                <!-- Add To Order Button -->
                                                                                <td width="250"><input type="button" onclick='cart.addItem(new Product(<?= $shippingProducts->getId() ?>, "<?= $shippingProducts->getModName() ?>", "<?= $shippingProducts->getModShortName() ?>", document.getElementById("shippingCost<?= $counter ?>").value, "<?= $shippingProducts->getRentalType() ?>"), document.getElementById("shippingQty<?= $counter ?>").value);' class="btn btn-gbr" value="Add To Order"/></td>
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
                                                                                        <input type="text" id="containerCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php if($quoteType == 'Rental' || $quoteType == 'rental'){echo $containerProducts->getMonthly();}else{echo $containerProducts->getModCost();} ?>"/>
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
                                                                                        <input type="text" id="modCost<?= $counter ?>" name="cost" aria-describedby="basic-addon1" value="<?php if($quoteType == 'Rental' || $quoteType == 'rental'){echo $modificationProducts->getMonthly();}else{echo $modificationProducts->getModCost();} ?>"/>
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
                    
                    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <!-- This is teh alert modal if a customer is flagged. -->
        <div class="modal fade" id="alertModal" role="dialog">

        <script type="text/javascript">
        <?php 

        echo 'cart.getTaxRate('.$quote->getTaxRate().');';
        
        foreach ($quotedProducts as $qProd)
        {

            echo 'cart.addItem(new Product('.$qProd->getId().',"'.$qProd->getModName().'","'.$qProd->getModShortName().'","'.$qProd->getProductCost().'","'.$qProd->getRentalType().'","false"),'.$qProd->getProductQuantity().');';
            echo "\n";
        }

        ?>
        </script>

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

    </body>

</html>