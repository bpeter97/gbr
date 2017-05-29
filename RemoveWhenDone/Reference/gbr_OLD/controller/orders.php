<?php

	// Create URL to CFG/SETTINGS.PHP file.
	$cfgurl = $_SERVER['DOCUMENT_ROOT'];
	$cfgurl .= "/cfg/settings.php";

	//Variable Constants
	include($cfgurl);

	//Include database connections
	include(BASEURL.CFG.'/database.php');
	include(BASEURL.CLASSES.'/Calendar.php');
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

	$db = new Database();
	$db->connect();

	$calendar = new Calendar();

	// ------------------------------------------------------------------------------------------------------------------

	// Check the variables.
	$action = set("GET","action");
	$from = set("GET","from");
	$stage = set("GET","stage");

	//Check to see which action we want to perform.
	if($action == "delete"){
		$oid = $_GET['oid'];
		delete($oid,$db);
	} elseif ($action == "edit"){
		$oid = $_POST['frmoid'];
		edit($oid,$db,$stage);
	}

	// The delete function
	function delete($id,$db){

		// Delete from orders where orderid = $id
		$db->delete('orders','order_id = '.$id);

		// Return to the orders page with a message.
		header('Location: '.HTTP.HTTPURL.VIEW.'/orders.php?action=orderdel');
	}

	// The edit function.
	function edit($oid,$db,$stage){

		if($stage == 1){
			// This is the stage where the order was created, but the page is just being edited.
			$where = 'order_id='.$oid;
			// Assign variables.
			$cname = set('POST','frmcname');
			$ordered_by = set('POST','frmorderedby');
			$order_date = set('POST','frmorderdate');
			$job_name = set('POST','frmjobname');
			$onsite_contact = set('POST','frmonsitecontact');
			$order_time = set('POST','frmordertime');
			$onsite_contact_number = set('POST','frmonsitecontactnumber');
			$job_address = set('POST','frmjobaddress');
			$job_city = set('POST','frmjobcity');

			$updateArray = array('order_customer'=>$cname,'ordered_by'=>$ordered_by,'order_date'=>$order_date,	'job_name'=>$job_name,'onsite_contact'=>$onsite_contact,'order_time'=>$order_time,'onsite_contact_phone'=>$onsite_contact_number,'job_address'=>$job_address,'job_city'=>$job_city);

			// Run the SQL
			$db->update('orders',$updateArray,$where);

			// Get results.
			$res = $db->getResult();

		} elseif ($stage == 2){
			// In this stage the container was delivered and the driver has returned to update information.

			// No event needs to be created. Old event can be updated to completed status.
			
			// Assign Variables
			$new_stage = set('POST','frmstage');
			$delivered = set('POST','frmdelivered');
			$date_delivered = set('POST','frmdatedelivered');
			$container_delivered = set('POST','frmcontainerdelivered');
			$driver = set('POST','frmdriver');
			$driver_notes = set('POST','frmdrivernotes');

			$where = 'order_id='.$oid;

			$updateArray = array('stage'=>$new_stage,
				'delivered'=>$delivered,
				'date_delivered'=>$date_delivered,
				'container'=>$container_delivered,
				'driver'=>$driver,
				'driver_notes'=>$driver_notes);

			// Run the SQL
			$db->update('orders',$updateArray,$where);
			$res = $db->getResult();

			$db->select('orders','*','',$where);
			$order_res = $db->getResult();

			$container = new Container($db);
			$container->getDetails($container_delivered);
            
            if($order_res[0]['order_type']=="Sales" || $order_res[0]['order_type']=="Resale"){
                $container->deliver($order_res[0]['job_address'],"FALSE");
            } else {
                $container->deliver($order_res[0]['job_address'],"TRUE");
            }

			$db->insert('rental_history',array('container_id'=>$container->id,'start_date'=>$date_delivered,'end_date'=>'','customer'=>$order_res[0]['order_customer']));

		} elseif ($stage == 3){
			// In this stage the order is ready for pickup if it is a rental.
			// A new event must be created.

			

		}



		// Handle results.
		if($res){
			header('Location: '.HTTP.HTTPURL.VIEW.'/orders.php?action=esuccess');
		} else {
			echo 'There was an error!';
		}
   
	}

	// This function is to check if the variables are here or not.
	function set($type, $set, $default = ""){

		if($type == "GET"){
			if(isset($_GET[$set])){
				$set_val = $_GET[$set];
			} else {
				$set_val = $default;
			}
		} elseif ($type == "POST"){
			if(isset($_POST[$set])){
				$set_val = $_POST[$set];
			} else {
				$set_val = $default;
			}
		}

		return $set_val;
		
	}

?>