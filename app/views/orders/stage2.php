<?php 

    $drivers = $data['drivers'];
    $containers = $data['containers'];
    $order = $data['order'];
    $productCount = 1;

?>

<DOCTYPE html>

<html>
    <head>
        <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>

        <?php 
        echo '
        <script type="text/javascript">
            $(document).ready(function(){
                var date_input=$(\'input[name="frmdatedelivered"]\');
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
            var count = 1;
            function add_field() 
            {
                count++;
                var field = '<div class="row">';
                field += '<div class="col-md-12">';
                field += '<label class="col-md-4" for="frmcontainer'+ count +'" control-label>Container #'+ count +':</label>';
                field += '<div class="col-md-8">';
                field += '<select class="form-control" name="frmcontainer'+ count +'">'
                <?php foreach($containers as $container): ?>
                field += '<option value="<?= $container->getId(); ?>"><?= $container->getContainerNumber(); ?></option>';
                <?php endforeach; ?>
                field += '</select>';
                field += '<p class="help-block">Select the container that was delivered.</p>';
                field += '</div>';
                field += '</div>';
                field += '</div>';
                document.getElementById('additionalContainers').innerHTML += field;
                document.getElementById('productcount').value = count;

            }
        </script>
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
                                <form action="<?php echo Config::get('site/siteurl').Config::get('site/orders').'/upgrade/'.$order->getId().'/2'; ?>" method="post">
                                <input class="form-control" type="hidden" name="productcount" id="productcount" required="false" value="1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-4" for="frmdatedelivered" control-label>Date Delivered</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="frmdatedelivered" required="true">
                                                <p class="help-block">This field is the date that the products were delivered.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-4" for="frmdriver" control-label>Driver:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="frmdriver" id="frmdriver">
                                                    <?php foreach($drivers as $driver): ?>
                                                        <option value="<?= $driver->getId(); ?>"><?= $driver->getFirstName().' '.$driver->getLastName(); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <p class="help-block">Select the driver who delivered the container.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-4" for="frmdrivernotes" control-label>Driver Notes:</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" cols="40" rows="5" type="text" name="frmdrivernotes"></textarea>
                                                <p class="help-block">Enter in the driver's notes.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-4" for="frmcontainer" control-label>Container:</label>
                                            <div class="col-md-7">
                                                <select class="form-control" name="frmcontainer1" id="frmcontainer1">
                                                    <?php foreach($containers as $container): ?>
                                                        <option value="<?= $container->getId(); ?>"><?= $container->getContainerNumber(); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <p class="help-block">Select the container that was delivered.</p>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" onclick="add_field()" class="btn btn-default form-button" style="margin-top: -1px;">Add Container</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="additionalContainers"></div>
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