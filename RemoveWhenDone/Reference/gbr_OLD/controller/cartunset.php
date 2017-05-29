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

    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

	// If came from create quote, delete old record that was being created.
    if(isset($_GET['from'])){
    	$from = $_GET['from'];

        if($from == 'createquote'){

        	if(isset($_SESSION['new_quote_id'])){
				$sess_id = $_SESSION['new_quote_id'];
			}

	    	$db->delete('quotes','quote_id='.$sess_id);
	    	$res = $db->getResult();
	    }
    }

    // Unset all session variables, minus user log-in information.
   	foreach ($_SESSION['cart_array'] as $session_variable) {
        unset($session_variable);
    }

    // Unset quote_item variables if it is set.
    if(isset($_SESSION["quote_item"])) {
        unset($_SESSION["quote_item"]);
    }

    // Take us back to the index page.
	if(isset($_GET['action'])){
        $action = $_GET['action'];
        if($action == "early"){
   			header('Location: '.HTTP.HTTPURL.'/index.php');
    	} 
    }

?>
