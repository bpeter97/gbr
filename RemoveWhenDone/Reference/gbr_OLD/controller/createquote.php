<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

	include(BASEURL.CFG.'/database.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }
   
	$db = new Database();
	$db->connect();

	if(isset($_GET['pqid'])){
		$pqid = $_GET['pqid'];
		$sql = 'SELECT product_name, product_qty, product_cost, product_msn FROM product_orders WHERE quote_id = '.$pqid;
		$db->sql($sql);
		$qitems = $db->getResult();

		// echo '<pre>';
		// var_dump($qitems);
		// echo '</pre>';

		$cart_edit_numrow = $db->numRows();
		$counter = 0;
		while($counter<>$cart_edit_numrow){
			$itemArray = array($qitems[$counter]['product_msn']=>array('mod_name'=>$qitems[$counter]['product_name'],'mod_short_name'=>$qitems[$counter]['product_msn'],'quantity'=>$qitems[$counter]['product_qty'],'cost'=>$qitems[$counter]['product_cost']));
			$_SESSION["quote_item"] = array_merge($_SESSION["quote_item"],$itemArray);
			$counter+=1;
		}
	}
	$item_total = 0;
	$deduction_total = 0;
	$monthly_total = 0;

	$taxrates_query = "SELECT * FROM taxrates";
	$taxrates_escaped = $db->escapeString($taxrates_query);
	$tr_safe = $db->stripSlashes($taxrates_escaped);
	$db->sql($tr_safe);
	$taxrates = $db->getResult();

	$sql = 'SELECT mod_short_name FROM modifications WHERE item_type="modification" OR item_type="delivery" OR item_type="pickup"';
	$sqlescaped = $db->escapeString($sql);
	$safesql = $db->stripSlashes($sqlescaped);
	$db->sql($safesql);
    $modarray = $db->getResult();

	$_SESSION['rent_array'] = array('10CONRENT','20DDCONRENT','20CONRENT','40CONRENT','24CONRENT','20COMBORENT','20FULLRENT','40COMBORENT','40SCOMBORENT','20SHELVRENT','LOADRAMP');

	// If an action takes place, check which action and choose a case.
	if(!empty($_GET["action"])) {

		switch($_GET["action"]) {

			// If the action was add ->
			case "add":
				if(!empty($_POST["quantity"])){

					// Grab the mod information from the database.
					$productByCode = $db->sql("SELECT * FROM modifications WHERE mod_short_name='" . $_GET["code"] . "'");
					$productResult = $db->getResult();

					// Create the item array based on the results from the database
					// and the quantity/cost of the item.
					$itemArray = array($productResult[0]["mod_short_name"]=>array('mod_name'=>$productResult[0]["mod_name"], 'mod_short_name'=>$productResult[0]["mod_short_name"], 'quantity'=>$_POST["quantity"], 'cost'=>$_POST["cost"]));

					// If quote_item is not empty and the item is not in the array,
					// update the quantity, else add the item to the array.
					if(!empty($_SESSION["quote_item"])) {
						if(in_array($productResult[0]["mod_short_name"],$_SESSION["quote_item"])) {
							foreach($_SESSION["quote_item"] as $k => $v) {
									if($productResult[0]["mod_short_name"] == $k)
										$_SESSION["quote_item"][$k]["quantity"] = $_POST["quantity"];
							}
						} else {
							$_SESSION["quote_item"] = array_merge($_SESSION["quote_item"],$itemArray);
						}
					} else {
						$_SESSION["quote_item"] = $itemArray;
					}
				}
			break;
			// If the action was remove ->
			case "remove":
				if(!empty($_SESSION["quote_item"])) {
					foreach($_SESSION["quote_item"] as $k => $v) {
						if($_GET["code"] == $k)
							unset($_SESSION["quote_item"][$k]);
						if(empty($_SESSION["quote_item"]))
							unset($_SESSION["quote_item"]);
					}
				}
			break;
			// If the action was empty ->
			case "empty":
				unset($_SESSION["quote_item"]);
			break;
		}
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

				<!-- Added item to quote successfully. -->
				<?php
				if(!empty($_GET['action'])){
					if($_GET['action'] == 'add'){
						echo '
						<div class="row">
		                    <div class="col-lg-12">
		                        <div id="alerts">
		                        </div>
		                    </div>
		                </div>
	                	';
					}

				}
				?>

                <!-- 1st Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Quotes</b>
                            </div>
                            <div class="panel-body">
	                            <?php
	                            	if(isset($_GET['pqid'])){
	                            		echo '<form action="../view/displayquote.php?pqid='.$pqid.'" method="post">';
	                            	} else {
	                            		echo '<form action="../view/displayquote.php" method="post">';
	                            	}
	                            ?>
		                            <div class="row"><!-- 1st Row -->
		                                <div class="col-lg-12">
		                                    <div class="form-group">
		                                        <label class="control-label col-md-4" for="frmquotedate">Quote Date</label>
		                                        <div class="col-md-8">
		                                            <div class="form-group">
		                                                <div class='input-group'>
		                                                    <input id="frmquotedate" name="frmquotedate" class="form-control" placeholder="" type="text" required="true" value="<?php echo $_SESSION['quote_date']; ?>">
		                                                    <span class="input-group-addon">
		                                                        <span class="glyphicon glyphicon-calendar"></span>
		                                                    </span>

		                                                </div>
		                                            </div>
		                                            <p class="help-block">Select the date the quote was created.</p>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div><!-- End of 1st Row -->
		                            <div class="row"><!-- 2nd Row -->
		                                <div class="col-lg-12">
		                                    <label class="col-md-4 control-label" for="frmcustomername" control-label">Select a Customer</label>
		                                    <div class="col-md-8">
		                                        <select class="form-control" name="frmcustomername" id="frmcustomername" required="true" value="<?php echo $_SESSION['customer_name']; ?>">
		                                            <option>Select Customer</option>
		                                            <!-- PHP to select customers names! -->
		                                            <?php
		                                                include('../cfg/mysqli_connect.php');
		                                                $query = "SELECT * FROM customers";
		                                                $custlist = @mysqli_query($dbc, $query);

		                                                if($custlist) {
		                                                    while ($row = mysqli_fetch_array($custlist)) {
		                                                    	if($row['customer_name'] == $_SESSION['customer_name']){
		                                                    		echo '
		                                                    			<option selected>'. $row['customer_name'] .'</option>
		                                                    		';
		                                                    	}else {
			                                                        echo '
			                                                            <option>'. $row['customer_name'] .'</option>
			                                                        ';
		                                                    	}
		                                                    }
		                                                }
		                                            ?>
		                                        </select>
		                                        <p class="help-block">Select which customer is getting the quote.</p>
		                                    </div>
		                                </div>
		                            </div><!-- End of 2nd Row -->
		                            <div class="row"><!-- 3rd Row -->
		                                <div class="col-lg-12">
		                                    <label class="col-md-4" for="frmquotetype" control-label">Quote Type</label>
		                                    <div class="col-md-8">
		                                        <select class="form-control" name="frmquotetype" id="frmquotetype" required="true">
		                                        	<?php
		                                        		echo '<option selected>'.$_SESSION["quote_type"].'</option>';
		                                        	?>
		                                            <option>Select One</option>
		                                            <option>Rental</option>
		                                            <option>Resale</option>
		                                            <option>Sales</option>
		                                        </select>
		                                        <p class="help-block">Select what type of quote this is.</p>
		                                    </div>
		                                </div>
		                            </div><!-- End of 3rd Row -->
		                            <div class="row"><!-- 4th Row -->
		                                <div class="col-lg-12">
		                                    <label class="col-md-4" for="frmquotestatus" control-label">Quote Status</label>
		                                    <div class="col-md-8">
		                                        <select class="form-control" name="frmquotestatus" id="frmquotestatus" required="true">
		                                        	<?php
		                                        		echo '<option selected>'.$_SESSION["quote_status"].'</option>';
		                                        	?>
		                                            <option>Select One</option>
		                                            <option>Open</option>
		                                            <option>Closed</option>
		                                        </select>
		                                        <p class="help-block">Select the status of the quote.</p>
		                                    </div>
		                                </div>
		                            </div><!-- End of 4th Row -->

		                            <div class="row"><!-- 5th Row -->
			                            <div class="col-lg-12">
			                            	<label class="col-md-4" for="frmjobname" control-label>Job Name</label>
			                            	<div class="col-md-8">
			                            		<input class="form-control" type="text" name="frmjobname" required="true" value="<?php echo $_SESSION['job_name']; ?>">
			                            	<p class="help-block">Fill out the job name if there is one.</p>
			                            	</div>
			                            </div>
			                        </div><!-- End of 5th Row -->

			                        <div class="row"><!-- 6th Row -->
			                            <div class="col-lg-12">
			                            	<label class="col-md-4" for="frmjobadress" control-label>Job Address</label>
			                            	<div class="col-md-8">
			                            		<input class="form-control" type="text" name="frmjobaddress" required="true" value="<?php echo $_SESSION['job_address']; ?>">
			                            	<p class="help-block">Fill out just the <strong>STREET</strong> address.</p>
			                            	</div>
			                            </div>
			                        </div><!-- End of 6th Row -->

			                        <div class="row"><!-- 7th Row -->
			                            <div class="col-lg-12">
			                            	<label class="col-md-4" for="frmjobcity" control-label>Job City</label>
			                            	<div class="col-md-8">
			                            		<input class="form-control" type="text" name="frmjobcity" required="true" value="<?php echo $_SESSION['job_city']; ?>">
			                            	<p class="help-block">Fill out the city of the job location.</p>
			                            	</div>
			                            </div>
			                        </div><!-- End of 7th Row -->

			                        <div class="row"><!-- 8th Row -->
			                            <div class="col-lg-12">
			                            	<label class="col-md-4" for="frmjobzipcode" control-label>Job Zipcode</label>
			                            	<div class="col-md-8">
			                            		<input class="form-control" type="text" name="frmjobzipcode" required="true" value="<?php echo $_SESSION['job_zipcode']; ?>">
			                            	<p class="help-block">Fill out the zipcode of the job location.</p>
			                            	</div>
			                            </div>
			                        </div><!-- End of 8th Row -->

		                            <div class="row"><!-- 9th Row -->
		                                <div class="col-lg-12">
	                                        <table class="table table-hover">
	                                            <thead>
	                                                <tr>
			                                            <th>Item Name</th>
			                                            <th>Quantity</th>
			                                            <th>Item Price</th>
			                                            <th>Remove Item</th>
	                                                </tr>
	                                            </thead>
	                                            <tbody>
	                                            <?php
						                            if(isset($_SESSION["quote_item"])){
				                                        $item_total = 0;
				                                        $deduction_total = 0;
				                                   		foreach ($_SESSION["quote_item"] as $item){
                                            		?>
                                        			<tr>
                                        				<!-- These need to be adjusted correctly. -->

														<td><?php echo $item["mod_name"]; ?></td>
														<td><?php echo $item["quantity"]; ?></td>
														<td>$<?php echo $item["cost"]; ?></td>
														<td><a style="color:black;" href="createquote.php?action=remove&code=<?php echo $item["mod_short_name"]; ?>">Remove Item</a></td>
													</tr>
                                            		<?php
                                            			$item_total += ($item["cost"]*$item["quantity"]);

                                            			// Grab mod short name and compare it in the array that contains all of the modifications.
                                            			$msn = $item["mod_short_name"];
                                            			foreach($modarray as $key => $value){
	                                            			if(in_array($msn, $modarray[$key])){
	                                            				$deduction_total += ($item["cost"]*$item["quantity"]);
	                                            			}
	                                            		}

	                                            		// Check mod short name to see if it is in the rental array.
                                            			if(in_array($item['mod_short_name'], $_SESSION['rent_array'])){
                                            				$monthly_total += ($item['cost']*$item['quantity']);
                                            			}
                                            		}
                                            	}
                                            	$cost_total = ($item_total - $deduction_total);

                                            	$_SESSION['monthly_total'] =  $monthly_total;
                                            	$_SESSION['cost_before_tax'] = $cost_total;
                                            	$_SESSION['deduction_total'] = $deduction_total;
                                            	// Still adding more session variables.
                                            	?>
                                        			<tr>
                                        				<td colspan="5" align="right"><strong>Total (Before Tax):</strong> <?php echo "$".$item_total; ?></td>
                                        			</tr>
                                        			<tr>
                                        				<td colspan="5" align="right"><i>Total after tax will appear on the quote preview. (After Tax Total = ((Total - Deliveries) * Tax Rate) + Total)</i></td>
                                        			</tr>
                                        		</tbody>
                                        	</table>
		                                </div>
		                            </div><!-- End of 9th Row -->
		                            <div class="modal-footer">
		                                <button type="submit" class="btn btn-gbr">Create Quote</button>
		                                <a href="<?php echo HTTP.HTTPURL.CONTROLLERS.'/cartunset.php?action=early&from=createquote'; ?>">

										<?php

										if(isset($_GET['pqid'])){
											echo '<button type="button" onclick="history.go(-1);" class="btn btn-gbr">Go Back</button>';
										} else {
											echo '<button type="button" class="btn btn-gbr">Cancel</button>';
										}

										?>

		                                </a>
		                            </div>
		                        </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 1st Row. -->

                <!-- 2nd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Delivery / Pickup</b>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Cost</th>
                                            <th>Quantity</th>
                                            <th>Add To Quote</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php
	                                    	$modquery = $db->sql("SELECT * FROM modifications WHERE item_type = 'pickup' OR item_type = 'delivery'");
	                                    	$modresults = $db->getResult();
                                    		if(!empty($modresults)){
                                    			foreach ($modresults as $key => $value) {

                                    			?>
                                    				<form method="post" action="createquote.php?action=add&code=<?php echo $modresults[$key]['mod_short_name']; ?>">
                                    					<tr>
															<!-- Short Name -->
															<td width="535"><?php echo $modresults[$key]["mod_name"]; ?></td> <!-- Long Name -->
															<td>
																<div class="input-group">
																	<span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
																	<input type="text" name="cost" aria-describedby="basic-addon1" value="<?php echo $modresults[$key]["mod_cost"]; ?>"/>
																</div>
															</td> <!-- Cost -->
															<td><input type="text" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
															<td><input type="submit" class="btn btn-gbr" value="Add To Quote"/></td> <!-- Add To Quote Button -->
														</tr>
                                    				</form>
                                    			<?php
                                    			}
                                    		}
                                    	?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- End of 2nd Row. -->

                <!-- 3rd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Containers / Extras</b>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Monthly Cost</th>
                                            <th>Quantity</th>
                                            <th>Add To Quote</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php
                                    		if($_SESSION['quote_type'] == "Sales" || $_SESSION['quote_type'] == "Resale"){
	                                    		$modquery = $db->sql("SELECT * FROM modifications WHERE item_type = 'container' AND monthly = 0");
	                                    		$modresults = $db->getResult();
                                    		} elseif ($_SESSION['quote_type'] == "Rental") {
                                    			$modquery = $db->sql("SELECT * FROM modifications WHERE item_type = 'container' AND monthly <> 0 OR item_type = 'rent_mod'");
	                                    		$modresults = $db->getResult();
                                    		}

                                    		if(!empty($modresults)){
                                    			foreach ($modresults as $key => $value) {

                                    			?>
                                    				<form method="post" action="createquote.php?action=add&code=<?php echo $modresults[$key]['mod_short_name']; ?>">
                                    					<tr>
															<!-- Short Name -->
															<td><?php echo $modresults[$key]["mod_name"]; ?></td> <!-- Long Name -->
															<td>
																<div class="input-group">
																	<span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
																	<input type="text" name="cost" aria-describedby="basic-addon1" value="<?php if($_SESSION['quote_type'] == "Rental"){echo $modresults[$key]["monthly"];}else{echo $modresults[$key]["mod_cost"];} ?>"/>
																</div>
															</td> <!-- Cost -->
															<td><input type="text" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
															<td><input type="submit" class="btn btn-gbr" value="Add To Quote"/></td> <!-- Add To Quote Button -->
														</tr>
                                    				</form>
                                    			<?php
                                    			}
                                    		}
                                    	?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- End of 3rd Row. -->

                <!-- 4th Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Modifications / Misc</b>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Item Price</th>
                                            <th>Quantity</th>
                                            <th>Add To Quote</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php
                                    		if($_SESSION['quote_type'] == "Sales" || $_SESSION['quote_type'] == "Resale"){
	                                    		$modquery = $db->sql("SELECT * FROM modifications WHERE monthly = 0 AND item_type <> 'container' AND item_type <> 'pickup' AND item_type <> 'delivery'");
	                                    		$modresults = $db->getResult();
                                    		} elseif ($_SESSION['quote_type'] == "Rental") {
                                    			// Double check to see if this matches the database!
												$modquery = $db->sql("SELECT * FROM modifications WHERE item_type <> 'rent_mod' AND item_type <> 'modification' AND item_type <> 'container' OR item_type <> 'delivery' OR item_type <> 'pickup'");
	                                    		$modresults = $db->getResult();
                                    		}

                                    		if(!empty($modresults)){
                                    			foreach ($modresults as $key => $value) {

                                    			?>
                                    				<form method="post" action="createquote.php?action=add&code=<?php echo $modresults[$key]['mod_short_name']; ?>">
                                    					<tr>
															<!-- Short Name -->
															<td><?php echo $modresults[$key]["mod_name"]; ?></td> <!-- Long Name -->
															<td>
																<div class="input-group">
																	<span class="input-group-addon" id="basic-addon1"><strong>$</strong></span>
																	<input type="text" name="cost" aria-describedby="basic-addon1" value="<?php if($modresults[$key]["mod_cost"]==0){echo $modresults[$key]["monthly"];}else{echo $modresults[$key]['mod_cost'];} ?>"/>
																</div>
															</td> <!-- Cost -->
															<td><input type="text" name="quantity" value="1" size="2"/></td> <!-- Quantity -->
															<td><input type="submit" class="btn btn-gbr" value="Add To Quote"/></td> <!-- Add To Quote Button -->
														</tr>
                                    				</form>
                                    			<?php
                                    			}
                                    		}
                                    	?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- End of 4th Row. -->

                <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>

            </div>
        </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
