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

	if(isset($_POST['cust'])) {

		$bad_name = $_POST['cust'];
		$name = str_replace('%20', ' ', $bad_name);

		$db->sql("SELECT * FROM customers WHERE customer_name = '$name'");
		$res = $db->getResult();
		foreach($res as $sel){
			$cust = $sel['customer_name'];
			$cust_flag = $sel['flag_reason'];
		}
	}

	echo '

	<div class="modal-dialog"> 
	    <!-- Modal content-->
	    <div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal">&times;</button>
	            <h4 class="modal-title">!!! ALERT !!!</h4>
	        </div>
	        <div class="modal-body">
	            <p>The customer ('.$cust.') has a current flag on his account!</p>
	            </p> Flag Reason: '.$cust_flag.'
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    </div>
    </div>
	';

?>