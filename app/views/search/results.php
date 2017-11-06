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
                                    <b>Search Results</b>
                                </div>
                                <div class="panel-body">

                                <?php
                                    
                                    switch ($data['selected']) {
                                        case 'containers':
                                                echo "There were ". count($data['containers']) ." result(s) found.</br></br>";

                                                // Set up the table to display results.
                                                echo '
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>GBR Number</th>
                                                            <th>Serial Number</th>
                                                            <th>Size</th>
                                                            <th>Shelves?</th>
                                                            <th>Paint?</th>
                                                            <th>Numbers?</th>
                                                            <th>Signs?</th>
                                                            <th>Rental or Resale</th>
                                                            <th>Is it rented?</th>
                                                            <th>Release Number</th>
                                                        </tr>
                                                    </thead>
                                                ';

                                            // Display results if there is more than one result!
                                            foreach($data['containers'] as $con){
                                                if($con['is_rented']=="TRUE"){
                                                    $isrented = "Yes";
                                                } else {
                                                    $isrented = "No";
                                                }

                                                echo '
                                                    <tbody>
                                                        <tr class="clickable-row" data-href="'.Config::get('site/siteurl').'/containers/id/' . $con['container_ID'].'">
                                                            <td>' . $con['container_number'] .'</td>
                                                            <td>' . $con['container_serial_number'] . '</td>
                                                            <td>' . $con['container_size'] . '</td>
                                                            <td>' . $con['container_shelves'] . '</td>
                                                            <td>' . $con['container_paint'] . '</td>
                                                            <td>' . $con['container_onbox_numbers'] . '</td>
                                                            <td>' . $con['container_signs'] . '</td>
                                                            <td>' . $con['rental_resale'] . '</td>
                                                            <td>' . $isrented . '</td>
                                                            <td>' . $con['release_number'] . '</td>
                                                        </tr>
                                                    </tbody>
                                                ';
                                            }

                                            echo '</table>';
                                            break;

                                        case 'customers':
                                            echo "There were ". count($data['customers']) ." result(s) found.</br></br>";

                                            // Set up the table to display results.
											echo '
                                            
                                            <table class="table table-striped table-hover" id="custTable">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Ext</th>
                                                        <th>Fax</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                            ';

                                            foreach($data['customers'] as $cust) {

                                                echo '
                                                <tbody>
                                                    <tr class="clickable-row" data-href="'.Config::get('site/siteurl').'/customers/id/' . $cust['customer_ID'] .'">
                                                        <td>' . $cust['customer_name'] . '</td>
                                                        <td>' . $cust['customer_phone'] . '</td>
                                                        <td>' . $cust['customer_ext'] . '</td>
                                                        <td>' . $cust['customer_fax'] . '</td>
                                                        <td>' . $cust['customer_email'] . '</td>
                                                    </tr>
                                                </tbody>
                                                ';
                                            }

                                            echo '</table>';

                                            break;

                                        case 'quotes':

                                            echo "There were ". count($data['quotes']) ." result(s) found.</br></br>";
                                            
											// Set up the table to display results.
											echo '
                                            
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Quote ID</th>
                                                        <th>Customer</th>
                                                        <th>Date</th>
                                                        <th>Rental or Resale</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                            ';
                                            
                                            foreach($data['quotes'] as $quote) {

                                            if($quote['quote_status'] == "Closed") {
                                                $tablebg = '<tr class="success clickable-row" data-href="'.Config::get('site/siteurl').'/quotes/viewinfo/'. $quote['quote_id'] . '">';
                                            } else {
                                                $tablebg = '<tr class="danger clickable-row" data-href="'.Config::get('site/siteurl').'/quotes/viewinfo/'. $quote['quote_id'] . '">';
                                            }

                                            echo '

                                            <tbody>
                                                '. $tablebg .'
                                                        <td>' . $quote['quote_id'] . '</td>
                                                        <td>' . $quote['quote_customer'] . '</td>
                                                        <td>' . $quote['quote_date'] . '</td>
                                                        <td>' . $quote['quote_type'] . '</td>
                                                        <td>' . $quote['quote_status'] . '</td>
                                                    </tr>
                                                </tbody>
                                                ';
                                            }

                                            echo '</table>';

                                            break;

                                        case 'orders':

                                            echo "There were ". count($data['orders']) ." result(s) found.</br></br>";
                                            
                                            // Set up the table to display results.
                                            echo '

                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Quote ID</th>
                                                        <th>Customer</th>
                                                        <th>Date</th>
                                                        <th>Rental or Resale</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                            ';

                                            foreach($data['orders'] as $order) {
                                                
                                                $new_order = new Order($order['order_id']);

                                                if($order->getStage() == 1) {
                                                    $tablebg = '<tr class="warning clickable-row" data-href="'.Config::get('site/siteurl').'/orders/viewinfo'.$order->getId().'">';
                                                } elseif($order->getStage() == 2) {
                                                    $tablebg = '<tr class="info clickable-row" data-href="'.Config::get('site/siteurl').'/orders/viewinfo'.$order->getId().'">';
                                                } elseif($order->getStage() == 3){
                                                    $tablebg = '<tr class="success clickable-row" data-href="'.Config::get('site/siteurl').'/orders/viewinfo'.$order->getId().'">';
                                                }
    
                                                echo '
    
                                                <tbody>
                                                    '. $tablebg .'
                                                        <td>' . $order->getId() . '</td>
                                                        <td>' . $order->getStage() . '</td>
                                                        <td>' . $order->getOrderCustomer() . '</td>
                                                        <td>' . $order->getOrderDate() . '</td>
                                                        <td>' . $order->getOrderTime() . '</td>
                                                        <td>' . $order->getOrderType() . '</td>
                                                        <td>' . $order->getOrderedBy() . '</td>
                                                        <td>' . $order->getOnsiteContact() . '</td>
                                                        <td>' . $order->getOnsiteContactPhone() . '</td>
                                                    </tr>
                                                </tbody>
                                                ';
                                            }

                                            echo '</table>';

                                            break;

                                        case 'products':

                                            echo "There were ". count($data['products']) ." result(s) found.</br></br>";

											// Set up the table to display results.
											echo '
                                            
                                            <table class="table table-striped table-hover" id="modTable">
                                                <thead>
                                                    <tr>
                                                        <th>Mod ID</th>
                                                        <th>Mod Name</th>
                                                        <th>Mod Short Name</th>
                                                        <th>Mod Cost</th>
                                                        <th>Monthly Cost</th>
                                                        <th>View/Edit</th>
                                                    </tr>
                                                </thead>
                                            ';

                                            foreach($data['products'] as $product) {

                                                echo '
                                                <tbody>
                                                    <tr>
                                                        <td>' . $product['mod_ID'] . '</td>
                                                        <td>' . $product['mod_name'] . '</td>
                                                        <td>' . $product['mod_short_name'] . '</td>
                                                        <td>' . $product['mod_cost'] . '</td>
                                                        <td>' . $product['monthly'] . '</td>
                                                        <td><a class="containerlink" href="#?id=' . $product['mod_ID'] . '">View/Edit</a></td>
                                                    </tr>
                                                </tbody>
                                                ';
                                            }

                                            echo '</table>';

                                            break;

                                        default:
                                            break;
                                    }


                                ?>

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