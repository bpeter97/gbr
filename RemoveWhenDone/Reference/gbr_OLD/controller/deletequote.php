<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include Database
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

	if(isset($_GET['id']) && isset($_GET['quote_status'])) {

		$quote_id = $_GET['id'];
		$status = $_GET['quote_status'];

		// This will check to see if the quote being deleted is open or closed, then it decides which function to run.
		if($status == "Open"){
			before_order($quote_id,$db);
		} elseif ($status == "Closed"){
			after_order($quote_id,$db);
		} else {
			echo 'There has been an error.';
		}

	} else {
		echo "GET['id'] or GET['quote_status'] was not set.";
	}

	// This function will delete all of the quote, including the products that were within the quote.
	function before_order($id,$db){
		$db->sql("SELECT * FROM product_orders WHERE quote_id=$id");
		$res = $db->getResult();

		foreach ($res as $res) {
			$db->delete("product_orders",'product_order_id='.$res["product_order_id"]);
		}

		$db->delete('quotes','quote_id='.$id);
		header('Location: '.HTTP.HTTPURL.VIEW.'/quotes.php?action=cdelb');
	}

	// This function will delete all of the quote, excluding the products that were in the quote because it needs to be referenced for the order.
	function after_order($id,$db){
		$db->delete('quotes','quote_id='.$id);
		header('Location: '.HTTP.HTTPURL.VIEW.'/quotes.php?action=cdela');
	}

?>
