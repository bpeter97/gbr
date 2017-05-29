<?php

	// Create URL to CFG/SETTINGS.PHP file.
	$cfgurl = $_SERVER['DOCUMENT_ROOT'];
	$cfgurl .= "/cfg/settings.php";

	//Variable Constants
	include($cfgurl);

	//Include database connections
	include(BASEURL.CFG.'/database.php');
	include(BASEURL.CLASSES.'/Container.php');

	//Check if session is started or not.
	if(session_id() == '' || !isset($_SESSION)) {
		session_start();
	}

	// Check if logged in.
	if(!isset($_SESSION['loggedin'])) {
		$locked = HTTP.HTTPURL.'/view/locked.php';
		header('Location: '.$locked);
	}

	// Create new database object.
	$db = new Database();
	$db->connect();

	// Create new container object.
	$con = new Container($db);

	// Get the users id.
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	}

	// If from edit users then the ID will be posted.
	if(isset($_POST['frmcid'])) {
		$id = $_POST['frmcid'];
	}

	// Get the action.
	if(isset($_GET['action'])) {
		$action = $_GET['action'];
	}

	// What page is the info coming from
	if(isset($_GET['from'])) {
		$from = $_GET['from'];
	}

	// opened from
	if(isset($_GET['of'])) {
		$of = $_GET['of'];
	}

	// Check to see which action we want to perform.
	// Perform the action & return to a different page.
	if($action == "delete"){
		$con->getDetails($id);
		delete($con,$of);
	} elseif ($action == "edit"){
		$con->getDetails($id);
		edit($con,$of,$db);
	} elseif ($action == "create"){
		create($db,$con,$of);
	}

	// Recieve data from the other pages that sent information to this file.
	function post($db, $con, $checkboxes=false){

		// Check if the container need's to be created or not.
		if ($checkboxes == false){

			$db->sql('SELECT DISTINCT container_size, container_size_code FROM containers');
			$res = $db->getResult();
			foreach ($res as $r){
				if($_POST['container_size'] == $r['container_size']){
					$con->container_size_code = $r['container_size_code'];
				}
			}

			$con->release_number = $_POST['release_number'];
			$con->container_size = $_POST['container_size'];
			$con->container_serial_number = $_POST['container_serial_number'];
			$con->container_number = $_POST['container_number'];
			$con->rental_resale = $_POST['rental_resale'];
			$con->is_rented = $_POST['is_rented'];
			$con->container_address = $_POST['container_address'];
			$con->type = "container";
			$con->flag = $_POST['flag'];
			$con->flag_reason = $_POST['flag_reason'];
			$con->getLatLon($_POST['container_address']);
			$con->container_shelves = $_POST['container_shelves'];
			$con->container_paint = $_POST['container_paint'];
			$con->container_onbox_numbers = $_POST['container_onbox_numbers'];
			$con->container_signs = $_POST['container_signs'];

		// Else if this is a container that is being created.
		} elseif ($checkboxes == true) {

			$db->sql('SELECT DISTINCT container_size, container_size_code FROM containers');
			$res = $db->getResult();
			foreach ($res as $r){
				if($_POST['frmcontainersize'] == $r['container_size']){
					$con->container_size_code = $r['container_size_code'];
				}
			}
			$con->rental_resale = $_POST['frmrentalresale'];
			$con->container_size = $_POST['frmcontainersize'];
			$con->release_number = $_POST['frmcontainerrelease'];
			$con->container_shelves = checkboxes($_POST['containershelves']);
			$con->container_paint = checkboxes($_POST['containerpainted']);
			$con->container_onbox_numbers = checkboxes($_POST['containergbrnumbers']);
			$con->container_signs = checkboxes($_POST['containersigns']);
			$con->container_serial_number = $_POST['frmcontainerserial'];
			$con->container_number = $_POST['frmcontainernumber'];
			$con->is_rented = 'FALSE';
			$con->container_address = "6988 Ave 304, Visalia, CA 93291";
			$con->getLatLon($con->container_address);
		}
	}

	// The delete function
	function delete($con,$of){
		
		$res = $con->delete();

		if($res){
			$type = 'deleted';
			relocate($of, $type);
		} else {
			echo 'There was an error!';
		}
	}

	// The edit function.
	function edit($con,$of,$db){  
		if ($_GET['from'] == "viewcontainerinfo") {
			
			post($db, $con);
			$res = $con->update();
			
			if($res){
				$type = "edited";
				relocate($of, $type);
			} else {
				echo 'There was an error!';
			}
		} else {
			header('Location: '.HTTP.HTTPURL.VIEW.'/containerinfo.php?of='.$of.'&action=edit&id='.$con->id);
		}
   
	}

	// This function will be used to create users in the future.
	function create($db, $con,$of){
		$checkboxes = true;
		post($db, $con, $checkboxes);
		$res = $con->create();

		if($res){
			$type = 'created';
			relocate($of, $type);
		} else {
			echo 'There was an error!';
		}

	}

	// This function grabs the "of" and then redirects the user.
	function relocate($of, $type){

		if($of == "mastercontainers"){
			header('Location: '.HTTP.HTTPURL.VIEW.'/mastercontainers.php?action='.$type);
		} elseif ($of == "resalecontainerlist"){
			header('Location: '.HTTP.HTTPURL.VIEW.'/resalecontainerlist.php?action='.$type);
		} elseif ($of == "rentalcontainers"){
			header('Location: '.HTTP.HTTPURL.VIEW.'/rentalcontainers.php?action='.$type);
		} elseif ($of == "currentlyrented"){
			header('Location: '.HTTP.HTTPURL.VIEW.'/currentrentals.php?action='.$type);
		} else {
			return;
		}		
	}

   	function checkboxes($check){
		if($check == 1){
			$checkvalue = "Yes";
		} else {
			$checkvalue = "No";
		}
		return $checkvalue;
	}

	
?>