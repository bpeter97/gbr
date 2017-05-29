<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

	include(BASEURL.CFG.'/database.php');
	include(BASEURL.CFG.'/mysqli_connect.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

	function checkboxes($check){
		if($check == 1){
			$checkvalue = "Yes";
		} else {
			$checkvalue = "No";
		}
		return $checkvalue;
	}

	$container_ID = $_POST['containerID'];
	$container_number = $_POST['frmcontainernumber'];
	$container_serial = $_POST['frmcontainerserial'];
	$container_shelves = checkboxes($_POST['containershelves']);
	$container_painted = checkboxes($_POST['containerpainted']);
	$container_gbr_numbers = checkboxes($_POST['containergbrnumbers']);
	$container_signs = checkboxes($_POST['containersigns']);
	$container_size = $_POST['frmcontainersize'];
	$container_resale = $_POST['frmrentalresale'];
	$container_release = $_POST['frmcontainerrelease'];

	$sql = "UPDATE containers
			SET
			container_number = '$container_number',
			container_serial_number = '$container_serial',
			container_size = '$container_size',
			container_shelves = '$container_shelves',
			container_paint = '$container_painted',
			container_onbox_numbers = '$container_gbr_numbers',
			container_signs = '$container_signs',
			rental_resale = '$container_resale',
			release_number = '$container_release'
			WHERE container_ID = $container_ID";

	if (mysqli_query($dbc, $sql)) {
		header("Location: ".HTTP.HTTPURL.VIEW."/mastercontainers.php");
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
