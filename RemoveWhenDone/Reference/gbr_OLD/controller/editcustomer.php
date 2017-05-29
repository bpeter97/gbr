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

    //Include database connections
    include(BASEURL.CFG.'/database.php');

    $cid = $_POST['frmcid'];
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

    $db = new Database();
    $db->connect();

    $db->update('customers',array('customer_name'=>$cname,'customer_address1'=>$caddy1,'customer_address2'=>$caddy2,'customer_phone'=>$cpnumber,'customer_fax'=>$cfnumber,'customer_email'=>$cemail,'customer_state'=>$cstate,'customer_city'=>$ccity,'customer_zipcode'=>$czipcode,'customer_rdp'=>$crdp,'customer_notes'=>$cnotes,'flagged'=>$cflag,'flag_reason'=>$cflagreason),'customer_ID = '.$cid);

    $res = $db->getResult();

    if($res){
        header('Location: '.HTTP.HTTPURL.VIEW.'/customers.php?action=esuccess');
    } else {
        echo 'There was an error!';
    }