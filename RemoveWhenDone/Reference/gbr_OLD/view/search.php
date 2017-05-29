<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.MODEL.'/header.php'); ?>

</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.INCLUDES.'/fixednavbar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <!-- 2nd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php

								include BASEURL.CFG.'/database.php';

								$db = new Database;
								$db->connect();

								// Check to see if a search was submitted.
									if(isset($_POST['category'])){
										$category = $_POST['category'];
										// We are going to check if the category was left as the default or selected.
										if($category == "Choose One"){
											// If not, go back with alert.
											header('Location: ' . $_SERVER['HTTP_REFERER'] . '?action=dcat');
										} else {
											// If so, then check if query is empty.
											if(isset($_POST['query'])){
												$query = $_POST['query'];
											} else {
												// If it is then go back with alert.
												header('Location: ' . $_SERVER['HTTP_REFERER'] . '?action=equery');
											}
										}
									}

									/*

									If category and query is good to go, we need to figure out what category (table) we are checking and then search for the query. We will use a switch case for this. This will reduce code clutter and let us block out each section better.

									*/

									switch ($category) {

							// -----------------------------------------Containers------------------------------------

										case 'containers':

											if(is_numeric($query) && strlen($query) == 6){
												$query = substr_replace($query, '-', 2, 0);
											} elseif (is_numeric($query) && strlen($query) == 7){
												$query = substr_replace($query, '-', 6, 0);
											}
											// This one needs to be done first.
											$sql = "SELECT * FROM containers WHERE
												release_number LIKE '%". $query ."%' OR container_size LIKE '%". $query ."%' OR
												container_serial_number LIKE '%". $query ."%' OR
												container_number LIKE '%". $query ."%' OR
												container_shelves LIKE '%". $query ."%' OR
												container_paint LIKE '%". $query ."%' OR
												container_onbox_numbers LIKE '%". $query ."%' OR
												container_signs LIKE '%". $query ."%' OR
												rental_resale LIKE '%". $query ."%' OR
												container_address LIKE '%". $query ."%' OR
												type LIKE '%". $query ."%'
												";
											$db->sql($sql);

											// Grab the results from the database object.
											$conres = $db->getResult();

											// Get the number of rows in the results and display that to the user.
											$connumres = $db->numRows();
											echo "There were ". $connumres ." results found.</br></br>";

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
											foreach($conres as $con){
												if($con['is_rented']=="TRUE"){
													$isrented = "Yes";
												} else {
													$isrented = "No";
												}

												echo '
													<tbody>
														<tr>
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

							// -----------------------------------------Customers------------------------------------

										case 'customers':

											// This one needs to be done first.
											$sql = "SELECT * FROM customers WHERE
												customer_name LIKE '%". $query ."%' OR
												customer_address1 LIKE '%". $query ."%' OR
												customer_address2 LIKE '%". $query ."%' OR
												customer_city LIKE '%". $query ."%' OR
												customer_state LIKE '%". $query ."%' OR
												customer_zipcode LIKE '%". $query ."%' OR
												customer_phone LIKE '%". $query ."%' OR
												customer_ext LIKE '%". $query ."%' OR
												customer_fax LIKE '%". $query ."%' OR
												customer_email LIKE '%". $query ."%' OR
												customer_rdp LIKE '%". $query ."%' OR
												customer_notes LIKE '%". $query ."%'
												";

											$db->sql($sql);

											// Grab the results from the database object.
											$results = $db->getResult();

											// Get the number of rows in the results and display that to the user.
											$numres = $db->numRows();
											echo "There were ". $numres ." results found.</br></br>";

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
														<th>View/Edit</th>
													</tr>
												</thead>
											';

											foreach($results as $row) {

												echo '
												<tbody>
													<tr>
														<td>' . $row['customer_name'] . '</td>
														<td>' . $row['customer_phone'] . '</td>
														<td>' . $row['customer_ext'] . '</td>
														<td>' . $row['customer_fax'] . '</td>
														<td>' . $row['customer_email'] . '</td>
														<td><a class="containerlink" href="'.HTTP.HTTPURL.VIEW.'/customerinfo.php?id=' . $row['customer_ID'] . '">View/Edit</a></td>
													</tr>
												</tbody>
												';
											}

											echo '</table>';

											break;

							// -----------------------------------------Orders---------------------------------------

										case 'orders':

											$sql = "SELECT * FROM orders WHERE

												order_customer LIKE '%". $query ."%' OR
												order_date LIKE '%". $query ."%' OR
												order_type LIKE '%". $query ."%' OR
												order_status LIKE '%". $query ."%' OR
												job_name LIKE '%". $query ."%' OR
												job_address LIKE '%". $query ."%' OR
												job_city LIKE '%". $query ."%' OR
												job_zipcode LIKE '%". $query ."%' OR
												attn LIKE '%". $query ."%' OR
												cost_before_tax LIKE '%". $query ."%' OR
												total_cost LIKE '%". $query ."%' OR
												sales_tax LIKE '%". $query ."%' OR
												monthly_total LIKE '%". $query ."%'
												";

											$db->sql($sql);

											// Grab the results from the database object.
											$results = $db->getResult();

											// Get the number of rows in the results and display that to the user.
											$numres = $db->numRows();
											echo "There were ". $numres ." results found.</br></br>";

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
														<th></th>
													</tr>
												</thead>
											';

											foreach($results as $row) {

												if($row['order_status']=="Closed") {
													$tablebg = '<tr class="success">';
												} else {
													$tablebg = '<tr class="danger">';
												}

												echo '

												<tbody>
													'. $tablebg .'
														<td>' . $row['order_id'] . '</td>
														<td>' . $row['quote_id'] . '</td>
														<td>' . $row['order_customer'] . '</td>
														<td>' . $row['order_date'] . '</td>
														<td>' . $row['order_type'] . '</td>
														<td>' . $row['order_status'] . '</td>
														<td>
															<a class="btn btn-xs btn-info button-link" href="'.HTTP.HTTPURL.VIEW.'/vieworder.php?order_id=' . $row['order_id'] . '">
														  	<span class="glyphicon glyphicon-print"></span>
															</a>
														</td>
													</tr>
												</tbody>
												';
											}

											echo '</table>';

											break;

							// -----------------------------------------Purchases------------------------------------

										case 'purchases':
											// code...
											break;

							// -----------------------------------------Vendors--------------------------------------

										case 'vendors':
											// code...
											break;

							// --------------------------------------Modifications-----------------------------------

										case 'modifications':

											// Generate SQL
											$sql = "SELECT * FROM modifications WHERE

												mod_name LIKE '%". $query ."%' OR
												mod_short_name LIKE '%". $query ."%'
												";

											// Execut SQL.
											$db->sql($sql);

											// Get results from DB
											$results = $db->getResult();

											// Get the number of rows in the results and display that to the user.
											$numres = $db->numRows();
											echo "There were ". $numres ." results found.</br></br>";

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

											foreach($results as $row) {

												echo '
												<tbody>
													<tr>
														<td>' . $row['mod_ID'] . '</td>
														<td>' . $row['mod_name'] . '</td>
														<td>' . $row['mod_short_name'] . '</td>
														<td>' . $row['mod_cost'] . '</td>
														<td>' . $row['monthly'] . '</td>
														<td><a class="containerlink" href="#?id=' . $row['mod_ID'] . '">View/Edit</a></td>
													</tr>
												</tbody>
												';
											}

											echo '</table>';

											break;

							// -----------------------------------------Drivers--------------------------------------

										case 'drivers':
											// code...
											break;

							// -------------------------------------Ordered Products---------------------------------

										case 'product_orders':
											// code...
											break;

							// -----------------------------------------Quotes---------------------------------------

										case 'quotes':

											$sql = "SELECT * FROM quotes WHERE

												quote_customer LIKE '%". $query ."%' OR
												quote_date LIKE '%". $query ."%' OR
												quote_type LIKE '%". $query ."%' OR
												quote_status LIKE '%". $query ."%' OR
												job_name LIKE '%". $query ."%' OR
												job_address LIKE '%". $query ."%' OR
												job_city LIKE '%". $query ."%' OR
												job_zipcode LIKE '%". $query ."%' OR
												attn LIKE '%". $query ."%' OR
												cost_before_tax LIKE '%". $query ."%' OR
												total_cost LIKE '%". $query ."%' OR
												sales_tax LIKE '%". $query ."%' OR
												monthly_total LIKE '%". $query ."%'
												";

											$db->sql($sql);

											// Grab the results from the database object.
											$results = $db->getResult();

											// Get the number of rows in the results and display that to the user.
											$numres = $db->numRows();
											echo "There were ". $numres ." results found.</br></br>";

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
														<th></th>
													</tr>
												</thead>
											';

											foreach($results as $row) {

												if($row['quote_status']=="Closed") {
													$tablebg = '<tr class="success">';
												} else {
													$tablebg = '<tr class="danger">';
												}

												echo '

												<tbody>
													'. $tablebg .'
														<td>' . $row['quote_id'] . '</td>
														<td>' . $row['quote_customer'] . '</td>
														<td>' . $row['quote_date'] . '</td>
														<td>' . $row['quote_type'] . '</td>
														<td>' . $row['quote_status'] . '</td>
														<td>
															<a class="btn btn-xs btn-warning" href="'.HTTP.HTTPURL.MODEL.'/createquote_sessions.php?pqid='.$row['quote_id'].'">
														  	<span class="glyphicon glyphicon-pencil"></span>
															</a>
															<a type="button" class="btn btn-xs btn-success" href="'.HTTP.HTTPURL.CONTROLLERS.'/convertquote.php?quote_id='.$row['quote_id'].'&url=listallquotes">
														  	<span class="glyphicon glyphicon-usd"></span>
															</a>
															<a class="btn btn-xs btn-info button-link" href="'.HTTP.HTTPURL.VIEW.'/reproquote.php?quote_id=' . $row['quote_id'] . '">
														  	<span class="glyphicon glyphicon-print"></span>
															</a>
															<a class="btn btn-xs btn-danger" href="'.HTTP.HTTPURL.CONTROLLERS.'/deletequote.php?id='.$row['quote_id'].'&quote_status='.$row['quote_status'].'">
														  	<span class="glyphicon glyphicon-trash"></span>
															</a>
														</td>
													</tr>
												</tbody>
												';
											}

											echo '</table>';

											break;

							// -----------------------------------------Sales-------------------------------------

										case 'sales':
											// code...
											break;

							// -----------------------------------------Tax Rates---------------------------------

										case 'taxrates':
											// code...
											break;

							// -----------------------------------------Users-------------------------------------

										case 'users':
											// code...
											break;

							// -----------------------------------------Rentals-----------------------------------

										case 'rentals':
											// code...
											break;

							// --------------------------------------Modifications--------------------------------

										case 'modifications':
											// code...
											break;

										default:
											echo '</br>';
											echo 'You must select a category when conducting a search. Please try again.';
											echo '</br>';
											echo '</br>';
											break;
									}

							?>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->
            <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>

            </div>
        </div>

    <?php include (BASEURL.INCLUDES.'/modals.php'); ?>

	<?php include (BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
