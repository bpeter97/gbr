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

    if(isset($_GET['quote_id'])) {
        $quote_id = $_GET['quote_id'];
    }

    // This should select all of the data from the three tables to be able to reproduce a quote.
    $db->sql("SELECT
                    quotes.quote_id,
                    quotes.quote_type,
                    quotes.quote_date,
                    quotes.quote_customer,
                    quotes.attn,
                    quotes.sales_tax,
                    quotes.total_cost,
                    quotes.monthly_total,
                    quotes.job_name,
                    customers.customer_phone,
                    customers.customer_fax,
                    customers.customer_email
                FROM
                    quotes
                INNER JOIN
                    customers
                    ON quotes.quote_customer=customers.customer_name
                WHERE quotes.quote_id=$quote_id");
    $quote_repro = $db->getResult();

    $db->sql("SELECT
                    product_orders.product_name,
                    product_orders.product_qty,
                    product_orders.product_cost,
                    modifications.mod_short_name
                FROM
                    product_orders
                INNER JOIN
                    modifications
                    ON product_orders.product_name=modifications.mod_name
                WHERE product_orders.quote_id=$quote_id");
    $order_repro = $db->getResult();

    $new_total_cost = $quote_repro[0]["total_cost"]-$quote_repro[0]['monthly_total'];

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
                                    if($quote_repro[0]['quote_type'] == "Sales" || $quote_repro[0]['quote_type'] == "Resale"){
                                        echo 'SALES QUOTE';
                                    } elseif ($quote_repro[0]['quote_type'] == "Rental") {
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
                    						<td class="cust_data_td_middle"><?php echo $quote_repro[0]['quote_customer']; ?></td>
                    						<td class="cust_data_td_left"><strong>ATTN:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $quote_repro[0]['attn']; ?></td>
                    					</tr>
                    					<tr align="left">
                    						<td class="cust_data_td_left"><strong>PH:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $quote_repro[0]['customer_phone']; ?></td>
                    						<td class="cust_data_td_left"><strong>FAX:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $quote_repro[0]['customer_fax']; ?></td>
                    					</tr>
                    					<tr align="left">
                    						<td class="cust_data_td_left"><strong>EMAIL:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $quote_repro[0]['customer_email']; ?></td>
                    						<td class="cust_data_td_left"><strong>JOB:</strong></td>
                    						<td class="cust_data_td_middle"><?php echo $quote_repro[0]['job_name']; ?></td>
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
                                if($quote_repro[0]['quote_type'] == "Sales" || $quote_repro[0]['quote_type'] == "Resale"){ ?>

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
                                            foreach($order_repro as $item){
                                            ?>

                                            <tr>
                                                <!-- Sales Quote -->
                                                <td><?php echo $item['product_name']; ?></td>
                                                <td class="text-center"><?php echo $item["product_qty"]; ?></td>
                                                <td class="text-center">$ <?php echo $item["product_cost"]; ?></td>
                                            </tr>

                                            <?php } ?>

                                            <tr>
                                                <td>Sales Tax</td>
                                                <td class="text-center">1</td>
                                                <td class="text-center"><?php echo '$ '.$quote_repro[0]["sales_tax"] ?></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="sum_row">
                                                <td>Total:</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><?php echo '$ '.$quote_repro[0]["total_cost"] ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                <?php } elseif ($quote_repro[0]['quote_type'] == "Rental") { ?>

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

                                            <?php
                                                if(isset($_SESSION['rent_array'])) {
                                                    $rent_array = $_SESSION['rent_array'];
                                                } else {
                                                    $rent_array = array('10CONRENT','20DDCONRENT','20CONRENT','40CONRENT','24CONRENT','20COMBORENT','20FULLRENT','40COMBORENT','40SCOMBORENT','20SHELVRENT','LOADRAMP');
                                                }

                                                foreach ($order_repro as $item){

                                                    if(in_array($item['mod_short_name'],$rent_array)){
                                            ?>
                                            <tr>
                                                <!-- Rental Quote -->
                                                <td><?php echo $item["product_name"]; ?></td>
                                                <td class="text-center"><?php echo $item["product_qty"]; ?></td>
                                                <td class="text-center">$ <?php echo $item["product_cost"]; ?></td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <?php
                                                } else {
                                            ?>
                                            <tr>
                                                <!-- Sales Quote -->
                                                <td><?php echo $item["product_name"]; ?></td>
                                                <td class="text-center"><?php echo $item["product_qty"]; ?></td>
                                                <td class="text-center"></td>
                                                <td class="text-center">$ <?php echo $item["product_cost"]; ?></td>
                                            </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td>Sales Tax</td>
                                                <td class="text-center">1</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><?php echo '$ '.$quote_repro[0]['sales_tax']; ?></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="sum_row">
                                                <td>Total:</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><?php echo '$ '.$quote_repro[0]['monthly_total']; ?></td>
                                                <td class="text-center"><?php echo '$ '.$new_total_cost; ?></td>
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
                                    <li><a class="containerlink" onclick="history.go(-1);" ><button type="button" class="btn btn-gbr no-print" style="">Go Back</button></a></li>
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
