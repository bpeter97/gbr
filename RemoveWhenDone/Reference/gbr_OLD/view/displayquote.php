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

    include(BASEURL.CFG.'/database.php');
    $db = new Database;
    $db->connect();

    $taxrate = 0.00;

    if(isset(
        $_SESSION['quote_item'],
        $_SESSION['rent_array'],
        $_SESSION['cost_before_tax'],
        $_SESSION['monthly_total'],
        $_SESSION['deduction_total'])){
            $quote_items = $_SESSION['quote_item'];
            $deduction_total = $_SESSION['deduction_total'];
            $rent_array = $_SESSION['rent_array'];
            $cbt = $_SESSION['cost_before_tax'];
            $monthly_total = $_SESSION['monthly_total'];
    } else {
        echo 'There was an error recieving the quote items. Please recreate the quote and try again.';
    }

    $db->sql("SELECT * FROM customers");
    $customer_results = $db->getResult();

    foreach ($customer_results as $customer) {
        if($customer['customer_name'] == $_SESSION['customer_name']) {
            $cphone = $customer['customer_phone'];
            $cfax = $customer['customer_fax'];
            $cemail = $customer['customer_email'];
        }
    }

    $db->sql("SELECT * FROM taxrates");
    $zipcode_list = $db->getResult();

    foreach($zipcode_list as $zip){
        if($zip['zipcode'] == $_SESSION['job_zipcode']){
            $taxrate = $zip['tax_rate'];
        }
    }

    $sales_tax = $_SESSION['cost_before_tax'] * $taxrate;
    $total_cost = $sales_tax + $deduction_total + $_SESSION['cost_before_tax'];

 ?>

<!DOCTYPE html>
<html>
	<head>

        <?php include(BASEURL.MODEL.'/header.php'); ?>

	</head>

	<body style="background-color: white;">

		<div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <!-- 1st Row. [Confidentiality Portion] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<span>
                        	CONFIDENTIAL QUOTE <br/>
                        	For Individual Use Only
                       	</span>
                    </div>
                </div>
                <!-- End of 1st Row. -->

                <!-- 2nd Row. [Page Logo] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:20px;">
                    		<div class="row">
                    			<img src="../img/logo.png" alt="Green Box Rentals Logo">
                    		</div>
                    		<div class="row" style="font-weight: bold;margin-top:5px;">
                    			6988 AVENUE 304, VISALIA, CA 93291 &nbsp; &nbsp; &nbsp; PH (559) 733-5345 &nbsp; &nbsp; &nbsp; FX (559) 651-4288
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->

                <!-- 3rd Row. [Page Title] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:5px;">
                    		<div class="row">
                    			<h3 style="font-weight: bold;">
                                <?php
                                    if($_SESSION['quote_type'] == "Sales" || $_SESSION['quote_type'] == "Resale"){
                                        echo 'SALES QUOTE';
                                    } elseif ($_SESSION['quote_type'] == "Rental") {
                                        echo 'RENTAL QUOTE';
                                    }
                                ?>
                    			</h3>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 3rd Row. -->

                <!-- 4th Row. [Customers Information] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:10px;">
                    		<div class="row">
                    			<table class="cust_data_table table-bordered">
                    				<tbody>
                    					<tr align="left">
                    						<td class="cust_data_td_left"><strong>TO:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $_SESSION['customer_name']; ?></td>
                    						<td class="cust_data_td_left"><strong>ATTN:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $_SESSION['attn']; ?></td>
                    					</tr>
                    					<tr align="left">
                    						<td class="cust_data_td_left"><strong>PH:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $cphone; ?></td>
                    						<td class="cust_data_td_left"><strong>FAX:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $cfax; ?></td>
                    					</tr>
                    					<tr align="left">
                    						<td class="cust_data_td_left"><strong>EMAIL:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $cemail; ?></td>
                    						<td class="cust_data_td_left"><strong>JOB:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $_SESSION['job_name']; ?></td>
                    					</tr>
                    				</tbody>
                    			</table>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 4th Row. -->

                <!-- 5th Row. [Products Ordered] -->
                <div class="row">
                    <div class="col-lg-12">
                    	<div style="margin-top:30px;">
                    		<div class="row">
                            <?php
                                if($_SESSION['quote_type'] == "Sales" || $_SESSION['quote_type'] == "Resale"){ ?>

                                    <table class="cust_data_table table-bordered">
                                        <thead>
                                            <tr align="center" style="font-weight: bold;">
                                                <td>Item Name</td>
                                                <td>Quantity</td>
                                                <td>Cost</td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if(isset($_SESSION["quote_item"])){
                                                foreach ($_SESSION["quote_item"] as $item){
                                            ?>

                                            <tr>
                                                <!-- Sales Quote -->
                                                <td><?php echo $item["mod_name"]; ?></td>
                                                <td class="text-center"><?php echo $item["quantity"]; ?></td>
                                                <td class="text-center">$ <?php
                                                    $cost = round($item['cost'],2);
                                                    $quantity = round($item['quantity'],2);
                                                    $totalitemcost = $cost*$quantity;
                                                    $totalformatted = number_format($totalitemcost, 2, '.', '');
                                                    echo $totalformatted; ?></td>
                                            </tr>

                                            <?php } } ?>

                                            <tr>
                                                <td>Sales Tax <?php echo '('.number_format($taxrate, 4, '.', '').')'; ?></td>
                                                <td class="text-center">1</td>
                                                <td class="text-center"><?php echo '$ '.round($sales_tax,2); ?></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="sum_row">
                                                <td>Total:</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><?php echo '$ '.round(($total_cost),2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                <?php } elseif ($_SESSION['quote_type'] == "Rental") { ?>

                                    <table class="cust_data_table table-bordered">
                                        <thead>
                                            <tr align="center" style="font-weight: bold;">
                                                <td>Item Name</td>
                                                <td>Quantity</td>
                                                <td>Monthly Cost</td>
                                                <td>Cost</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <!-- Will obviously need to do a foreach here and then have the total row at the end after the foreach to display the proper total, easy enough. -->
                                            <?php
                                            if(isset($_SESSION["quote_item"])){
                                                foreach ($_SESSION["quote_item"] as $item){

                                                    if(in_array($item['mod_short_name'],$rent_array)){
                                            ?>
                                            <tr>
                                                <!-- Rental Quote -->
                                                <td><?php echo $item["mod_name"]; ?></td>
                                                <td class="text-center"><?php echo $item["quantity"]; ?></td>
                                                <td class="text-center">$ <?php
                                                    $totalitemcost = $item["cost"]*$item['quantity'];
                                                    $totalformatted = number_format($totalitemcost, 2, '.', '');
                                                    echo $totalformatted; ?></td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <?php
                                                } else {
                                            ?>
                                            <tr>
                                                <!-- Sales Quote -->
                                                <td><?php echo $item["mod_name"]; ?></td>
                                                <td class="text-center"><?php echo $item["quantity"]; ?></td>
                                                <td class="text-center"></td>
                                                <td class="text-center">$ <?php
                                                    $totalitemcost = $item["cost"]*$item['quantity'];
                                                    $totalformatted = number_format($totalitemcost, 2, '.', '');
                                                    echo $totalformatted; ?></td></td>
                                            </tr>
                                            <?php
                                                }
                                            }
                                        }
                                            ?>
                                            <tr>
                                                <td>Sales Tax</td>
                                                <td class="text-center">1</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><?php echo '$ '.round($sales_tax,2); ?></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="sum_row">
                                                <td>Total:</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><?php
                                                $formattedmonthly = number_format($monthly_total, 2, '.', '');
                                                echo '$ '.$formattedmonthly ?></td>
                                                <td class="text-center"><?php echo '$ '.round(($total_cost-$monthly_total),2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <?php } ?>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 5th Row. -->

                <!-- 6th Row. [Charge Explination] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:20px;">
                    		<div class="row">
                    			<span style="font-weight: bold;">
                    				No sales tax on delivery charges.
                    			</span>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 6th Row. -->

				<!-- 7th Row. [Thanks!] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:10px;">
                    		<div class="row">
                    			<table class="cust_data_table">
                    				<tbody>
                    					<tr class="tr_thanks">
                    						<td class="td_thanks">THANK YOU FOR CHOOSING GREEN BOX RENTALS, INC.</td>
                    					</tr>
                    				</tbody>
                    			</table>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 7th Row. -->

                <!-- 8th Row. [Visit Us!] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:10px;">
                    		<div class="row">
                    			<span style="font-weight: bold;">
                    				Visit www.greenboxrentals.com for more information on our services!
                    			</span>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 8th Row. -->

                <!-- 9th Row. [Comments Box] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:10px;">
                    		<div class="row">
                    			<table class="cust_data_table cust_border">
                    				<tbody>
                    					<tr>
                    						<td align="left">COMMENTS:</td>
                    					</tr>
                    					<tr style="height: 60px;">
                    						<td></td>
                    					</tr>
                    					<tr>
                    						<td align="center">Add sales tax on monthly rentals only.</td>
                    					</tr>
                    					<tr>
                    						<td align="center">One month minimum rental.</td>
                    					</tr>
                    				</tbody>
                    			</table>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 9th Row. -->

                <!-- 10th Row. [Manager Signature] -->
                <div class="row">
                    <div class="col-lg-12">
                    	<div style="margin-top:10px;">
                    		<div class="row">
                    			<span class="manager_sig">
                    				<?php echo $_SESSION['userfname'].' '.$_SESSION['userlname'].' - '.$_SESSION['usertitle']; ?>
                    			</span>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 10th Row. -->

                <!-- 11th Row. [Customer Signature] -->
                <div class="row">
                    <div class="col-lg-12">
                    	<div style="margin-top:50px;">
                    		<div class="row">
                    			<table class="cust_data_table" style="border-top: 2px solid black;">
                    				<tbody>
                    					<tr style="font-weight: bold;">
                    						<td align="left">SIGNATURE</td>
                    						<td align="center">DATE</td>
                    					</tr>
                    				</tbody>
                    			</table>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 11th Row. -->

                <!-- 12th Row. [Visit Us!] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                    	<div style="margin-top:10px;">
                    		<div class="row">
                    			<span>
                    				Quote good for thirty days from issue date!
                    			</span>
                    		</div>
                       	</div>
                    </div>
                </div>
                <!-- End of 12th Row. -->
                <!-- 13th Row. [Go Back Buttons] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a class="containerlink" href="../view/quotes.php"><button type="button" class="btn btn-gbr no-print" style="">Go Back</button></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- End of 13th Row. -->
            </div>
        </div>
	</body>
</html>
<?php
    // ----------- Need to create the order table. -----------
    if(isset($_GET['pqid'])){
        $quoteid = $_GET['pqid'];
        $db->delete('product_orders','quote_id='.$quoteid);
    } else {
        $quoteid = $_SESSION['new_quote_id'];
    }
    // This will simply add each product that was in the shopping cart into the orders table.
    foreach ($_SESSION['quote_item'] as $item) {
        if(isset($_GET['pqid'])){
            $quoteid = $_GET['pqid'];
        }
        $prodName = $item['mod_name'];
        $prodQty = $item['quantity'];
        $prodCost = $item['cost'];
        $prodMsn = $item['mod_short_name'];
        $db->insert('product_orders',array('quote_id'=>$quoteid,'product_name'=>$prodName,'product_qty'=>$prodQty,'product_cost'=>$prodCost,'product_msn'=>$prodMsn));
    }

    // This will update the total cost and sales tax on the quote at the end of this page.
    $db->update('quotes',array('total_cost'=>$total_cost,'sales_tax'=>$sales_tax,'cost_before_tax'=>$cbt, 'monthly_total'=>$monthly_total), 'quote_id='.$quoteid);

    include(BASEURL.CONTROLLERS.'/cartunset.php');

?>
