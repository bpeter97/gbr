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

    include('../cfg/mysqli_connect.php');

	$cname = $_POST['frmcname'];
	$caddy1 = $_POST['frmcaddy1'];
	$caddy2 = $_POST['frmcaddy2'];
	$cpnumber = $_POST['frmcpnumber'];
	$ccity = $_POST['frmccity'];
	$cfnumber = $_POST['frmcfnumber'];
	$cstate = $_POST['frmcstate'];
	$cemail = $_POST['frmcemail'];
	$czipcode = $_POST['frmczipcode'];
	$crdp = $_POST['frmcrdp'];
	$cnotes = $_POST['frmcnotes'];
    $cflag = $_POST['frmflaggedq'];
    $cflagreason = $_POST['frmflagreason'];

    $sql = "INSERT INTO customers (customer_name, customer_address1, customer_address2, customer_phone, customer_fax, customer_email, customer_state, customer_city, customer_zipcode, customer_rdp, customer_notes, flagged, flag_reason) VALUES ('$cname', '$caddy1', '$caddy2', '$cpnumber', '$cfnumber', '$cemail', '$cstate', '$ccity', '$czipcode', '$crdp', '$cnotes', '$cflag', '$cflagreason')";
    $res = @mysqli_query($dbc, $sql);
    $newcustid = mysqli_insert_id($dbc);

	if ($res) {
        if(isset($_GET['from'])) {
            if($_GET['from'] == "create_quote"){
                header("Location: ".HTTP.HTTPURL.VIEW."/create_quote.php?cust=".$newcustid);
            } else {
                header("Location: ".HTTP.HTTPURL.VIEW."/customers.php?c=success");
            }
        }
	} else {
		echo '
		<div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger alert-dismissible text-center" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    <strong>Heads up!</strong>
                    <hr class="divider headeralert"><br/>
                    <strong>There is an error with the server. Try again later.</strong>
                </div>
            </div>
        </div>
        ';
	}

?>
