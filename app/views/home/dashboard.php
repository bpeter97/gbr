<?php

$chart = $data['chart'];
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$con_list = [
            "10' Containers",
            "20' Containers",
            "20' Combos",
            "20' Full Offices",
            "20' Double Door",
            "20' Containers w/ Shelves",
            "20' High Cube",
            "22' DD/HC",
            "22' High Cube",
            "24' Containers",
            "24' High Cube",
            "40' Containers",
            "40' Combos",
            "40' Double Doors",
            "40' Full Offices",
            "40' High Cubes"
            ];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.APP.ASSETS.'/header.php'); ?>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"
            type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo HTTP.HTTPURL.PUB.JS.'/mapprinting.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo HTTP.HTTPURL.PUB.JS.'/map.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo HTTP.HTTPURL.PUB.JS.'/dashboard_charts.js'; ?>"></script>
    
</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.APP.ASSETS.'/fixednavbar.php'; ?>
    
        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <!-- include BASEURL.VIEW.'/dashboard.php'; ?> -->
                <div class="row">
                    <div class="col-lg-7">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                    <button class="map-print float-left btn btn-default" style="padding-top: 2px; padding-bottom: 2px; margin-top: -2px;">Print</button>
                                    <b>Container Map</b> 
                            </div>
                            <div class="panel-body">
                                <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                                <div id="map" style="width:100%;height:450px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b><a class="homeLink" href="<?php echo HTTP.HTTPURL.VIEW.'/calendar.php';?>">Calendar</a></b>
                            </div>
                            <div class="panel-body">
                                <?php include(BASEURL.MODEL.'/calendar.php') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->
                <!-- 3rd Row. -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Orders / Quotes In <?php echo date('Y'); ?></b>
                            </div>
                            <div class="panel-body">
                                <div id="total_orders" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                                <table id="ordertable" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Quotes</th>
                                            <th>Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for($i=0;$i<12;$i++)
                                        {
                                        ?>
                                        <tr>
                                            <th><?php echo $months[$i]; ?></th>
                                            <td><?php echo $chart->quotes[$i]; ?></td>
                                            <td><?php echo $chart->orders[$i]; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Containers In Stock</b>
                            </div>
                            <div class="panel-body">
                                <div id="stock_container" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                                <table id="datatable" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Rentals</th>
                                            <th>Sales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for($i=0;$i<16;$i++)
                                        {
                                        ?>
                                        <tr>
                                            <th><?php echo $con_list[$i]; ?></th>
                                            <td><?php echo $chart->rentals[$i]; ?></td>
                                            <td><?php echo $chart->resales[$i]; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php include(BASEURL.APP.ASSETS.'/copyright.php'); ?>

                </div>

    </div>

    <?php include BASEURL.APP.ASSETS.'/botjsincludes.php'; ?>

</body>

</html>