<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    // Database include & connection.
    include(BASEURL.CFG.'/database.php');
    $db = new Database;
	$db->connect();

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

	if(!isset($_GET['pqid'])) {

		// Create session variables - they're easier to use later on.
		$_SESSION['order_date'] = $_POST['frmorderdate'];
		$_SESSION['order_time'] = $_POST['frmordertime'];
		$_SESSION['ordered_by'] = $_POST['frmorderedby'];
		$_SESSION['customer_name'] = $_POST['frmcustomername'];
		$_SESSION['order_type']	= $_POST['frmordertype'];
		$_SESSION['job_name'] = $_POST['frmjobname'];
		$_SESSION['job_address'] = $_POST['frmjobaddress'];
		$_SESSION['job_city'] = $_POST['frmjobcity'];
		$_SESSION['job_zipcode'] = $_POST['frmjobzipcode'];
		$_SESSION['onsite_contact'] = $_POST['frmcontact'];
		$_SESSION['onsite_contact_phone'] = $_POST['frmcontactphone'];

		// Assign session variables to regular variables as it will be easier to create SQL statement with.
		$orderdate = $_SESSION['order_date'];
		$ordertime = $_SESSION['order_time'];
		$orderedby = $_SESSION['ordered_by'];
		$custname = $_SESSION['customer_name'];
		$ordertype = $_SESSION['order_type'];
		$jobname = $_SESSION['job_name'];
		$jobaddress = $_SESSION['job_address'];
		$jobcity = $_SESSION['job_city'];
		$jobzipcode = $_SESSION['job_zipcode'];
		$onsitecontact = $_SESSION['onsite_contact'];
		$onsitecontactphone = $_SESSION['onsite_contact_phone'];

		// Insert the first part of the order into the database. If order is canceled later, we will delete it from the database.
		$db->insert('orders',array('order_customer'=>$custname,'order_date'=>$orderdate,'order_time'=>$ordertime,'ordered_by'=>$orderedby,'order_type'=>$ordertype,'job_name'=>$jobname,'job_address'=>$jobaddress,'job_city'=>$jobcity,'job_zipcode'=>$jobzipcode,'onsite_contact'=>$onsitecontact,'onsite_contact_phone'=>$onsitecontactphone));
		
		$res = $db->getResult();

		if($res){
			// Grab the new ID if the insert worked.
			$_SESSION['new_order_id'] = $db->grabID();

			// Set the url to the create order page.
			$url = '../controller/createorder.php';
		} else {
            echo 'There was an error!';
        }

	} else {

		// This would mean that we are coming from the order's page and we need to edit an order. Therefore, get the pqid.
		$pqid = $_GET['pqid'];

		// Now we will pull the data from the orders table from that ID and assign them to session variables.
		$sql = 'SELECT * FROM orders WHERE order_id = '.$pqid;
		$db->sql($sql);
		$qres = $db->getResult();

		foreach ($qres as $quote){

			$_SESSION['order_date'] = $quote['order_date'];
			$_SESSION['order_time'] = $quote['order_time'];
			$_SESSION['ordered_by'] = $quote['ordered_by'];
			$_SESSION['customer_name'] = $quote['order_customer'];
			$_SESSION['order_type']	= $quote['order_type'];
			$_SESSION['job_name'] = $quote['job_name'];
			$_SESSION['job_address'] = $quote['job_address'];
			$_SESSION['job_city'] = $quote['job_city'];
			$_SESSION['job_zipcode'] = $quote['job_zipcode'];
			$_SESSION['onsite_contact'] = $quote['onsite_contact'];
			$_SESSION['onsite_contact_phone'] = $quote['onsite_contact_phone'];

		}

		// Pass the id to the createorder page.
		$url = '../controller/createorder.php?pqid='.$pqid;
	}

	// Session array in order to easily unset session items.
	$_SESSION['cart_array'] = array($_SESSION['order_date'], $_SESSION['order_time'], $_SESSION['ordered_by'], $_SESSION['customer_name'], $_SESSION['order_type'], $_SESSION['job_name'], $_SESSION['job_address'], $_SESSION['job_city'], $_SESSION['job_zipcode'], $_SESSION['onsite_contact'], $_SESSION['onsite_contact_phone']);

	// Here we are going to push some session variables to the session cart array for easier destruction, down the road.
	if(isset($_SESSION['deduction_total'])){
		array_push($_SESSION['cart_array'], $_SESSION['deduction_total']);
	}

	if(isset($_SESSION['cost_before_tax'])){
		array_push($_SESSION['cart_array'], $_SESSION['cost_before_tax']);
	}

	if(isset($_SESSION['new_order_id'])){
		array_push($_SESSION['cart_array'], $_SESSION['new_order_id']);
	}

	if(isset($_SESSION['monthly_total'])){
		array_push($_SESSION['cart_array'], $_SESSION['monthly_total']);
	}

	if(isset($_SESSION['rent_array'])){
		array_push($_SESSION['cart_array'], $_SESSION['monthly_total']);
	}

	if(isset($_SESSION['quote_item'])){
		array_push($_SESSION['cart_array'], $_SESSION['quote_item']);
	}

	//Send us to the create order page!
	header("Location: $url");

?>
