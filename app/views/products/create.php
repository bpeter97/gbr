<DOCTYPE html>

<html>
    <head>
        <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>
    </head>

    <body>

        <div id="wrapper">

            <?php include(Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'); ?>

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div class="container-fluid" id="webbg">

                    <!-- 2nd Row. -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <b>Edit Product</b>
                                </div>
                                <div class="panel-body">
                                <!-- <form action="http://www.rebol.com/cgi-bin/test-cgi.cgi" id="orderForm" method="post"> -->
                                <form action="<?php echo Config::get('site/siteurl').Config::get('site/products').'/createnew'; ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmpname" control-label>Product Name</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmpname" required="true" placeholder="Enter short name here (Example: Lock Box)">
                                                <p class="help-block">This field is the product's name.</p>
                                            </div>
                                            <label class="col-md-2" for="frmmsn" control-label>Product Label:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmmsn" required="false" placeholder="Enter short name here (Example: LBOX)">
                                                <p class="help-block">This field is the label of the product for database management (You can create your own <strong>unique</strong> ones).</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmpscost" control-label>Sales Cost:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmpscost" placeholder="0.00">
                                                <p class="help-block">This field is the sales cost of the product.</p>
                                            </div>
                                            <label class="col-md-2" for="frmprcost" control-label>Rental Cost</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmprcost" placeholder="0.00">
                                                <p class="help-block">This field is the monthly cost of the product if it is rentable.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmptype" control-label>Item Type:</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="frmptype">
                                                    <option selected>Select One</option>
                                                    <option value="modification">Modification</option>
                                                    <option value="container">Container</option>
                                                    <option value="pickup">Pickup</option>
                                                    <option value="delivery">Delivery</option>
                                                    <option value="rent_mod">Rental Modification</option>
                                                </select>
                                                <p class="help-block">This field is the type of product.</p>
                                            </div>
                                            <label class="col-md-2" for="frmprtype" control-label>Rental Status</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="frmprtype">
                                                    <option selected>Select One</option>
                                                    <option>Nonrental</option>
                                                    <option>Rental</option>
                                                </select>
                                                <p class="help-block">This field is the current status if it's a rental or nonrental product.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-default form-button">Submit</button>
                                        <button type="button" onclick="history.go(-1);" class="btn btn-default form-button" style="margin-top: 7px;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of 2nd Row. -->

                    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

    <body>

<html>