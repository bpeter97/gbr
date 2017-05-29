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

	$db = new Database;
	$db->connect();

	// This page will convert a quote to an order.
	if(isset($_GET['quote_id'])) {
		$quote_id = $_GET['quote_id'];
		$db->sql("SELECT * FROM quotes WHERE quote_id=$quote_id");
		$quote_sel_res = $db->getResult();

		// This will copy the quote from the quotes table over to the orders table, making it an official order.
		foreach($quote_sel_res as $x){
			$db->insert('orders',array('quote_id'=>$quote_id,'order_customer'=>$x['quote_customer'],'order_date'=>$x['quote_date'],'order_type'=>$x['quote_type'],'order_status'=>'Open','job_name'=>$x['job_name'],'job_address'=>$x['job_address'],'job_city'=>$x['job_city'],'job_zipcode'=>$x['job_zipcode'],'attn'=>$x['attn'],'cost_before_tax'=>$x['cost_before_tax'],'total_cost'=>$x['total_cost'],'sales_tax'=>$x['sales_tax'],'monthly_total'=>$x['monthly_total']));
		}

		// This will update the current status of the quote to "Closed" in the quotes table.
		$db->update('quotes',array('quote_status'=>'Closed'),'quote_id='.$quote_id);
		$quote_update = $db->getResult();

		header('Location: '.HTTP.HTTPURL.VIEW.'/quotes.php?action=c_success');

	} else {
		echo "GET['quote_id'] was not set.";
	}


?>
