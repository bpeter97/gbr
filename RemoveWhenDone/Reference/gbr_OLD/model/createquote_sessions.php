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

	if(!isset($_GET['pqid'])) {

		$_SESSION['quote_date'] = $_POST['frmquotedate'];
		$_SESSION['customer_name'] = $_POST['frmcustomername'];
		$_SESSION['quote_type']	= $_POST['frmquotetype'];
		$_SESSION['quote_status'] = $_POST['frmquotestatus'];
		$_SESSION['job_name'] = $_POST['frmjobname'];
		$_SESSION['job_address'] = $_POST['frmjobaddress'];
		$_SESSION['job_city'] = $_POST['frmjobcity'];
		$_SESSION['job_zipcode'] = $_POST['frmjobzipcode'];
		$_SESSION['attn'] = $_POST['frmattn'];

		$quotedate = $_SESSION['quote_date'];
		$custname = $_SESSION['customer_name'];
		$quotetype = $_SESSION['quote_type'];
		$quotestatus = $_SESSION['quote_status'];
		$jobname = $_SESSION['job_name'];
		$jobaddress = $_SESSION['job_address'];
		$jobcity = $_SESSION['job_city'];
		$jobzipcode = $_SESSION['job_zipcode'];
		$quoteattn = $_SESSION['attn'];

		// New Database Connection
		include('../cfg/mysqli_connect.php');
		$sql = "INSERT INTO quotes (quote_customer, quote_date, quote_type, quote_status, job_name, job_address, job_city, job_zipcode, attn) VALUES ('$custname', '$quotedate', '$quotetype', '$quotestatus', '$jobname', '$jobaddress', '$jobcity', '$jobzipcode', '$quoteattn')";
		$response = @mysqli_query($dbc, $sql);

		$_SESSION['new_quote_id'] = mysqli_insert_id($dbc);

		$url = '../controller/createquote.php';

	} else {

		$pqid = $_GET['pqid'];
		$db = new Database;
		$db->connect();

		$sql = 'SELECT quote_date, quote_customer, quote_type, quote_status, job_name, job_address, job_city, job_zipcode, attn FROM quotes WHERE quote_id = '.$pqid;
		$db->sql($sql);
		$qres = $db->getResult();

		foreach ($qres as $quote){
			$_SESSION['quote_date'] = $quote['quote_date'];
			$_SESSION['customer_name'] = $quote['quote_customer'];
			$_SESSION['quote_type']	= $quote['quote_type'];
			$_SESSION['quote_status'] = $quote['quote_status'];
			$_SESSION['job_name'] = $quote['job_name'];
			$_SESSION['job_address'] = $quote['job_address'];
			$_SESSION['job_city'] = $quote['job_city'];
			$_SESSION['job_zipcode'] = $quote['job_zipcode'];
			$_SESSION['attn'] = $quote['attn'];
		}

		$url = '../controller/createquote.php?pqid='.$pqid;
	}

		// Session array in order to easily unset session items.
		$_SESSION['cart_array'] = array($_SESSION['quote_date'], $_SESSION['customer_name'], $_SESSION['quote_type'], $_SESSION['quote_status'], $_SESSION['job_name'], $_SESSION['job_address'], $_SESSION['job_city'], $_SESSION['job_zipcode'], $_SESSION['attn']);

		if(isset($_SESSION['deduction_total'])){
			array_push($_SESSION['cart_array'], $_SESSION['deduction_total']);
		}

		if(isset($_SESSION['cost_before_tax'])){
			array_push($_SESSION['cart_array'], $_SESSION['cost_before_tax']);
		}

		if(isset($_SESSION['new_quote_id'])){
			array_push($_SESSION['cart_array'], $_SESSION['new_quote_id']);
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

		// echo $_SESSION['new_quote_id'];

		header("Location: $url");


?>
