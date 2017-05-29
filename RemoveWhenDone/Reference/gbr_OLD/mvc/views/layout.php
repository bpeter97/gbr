<?php 

	// Include constants
	require_once('assets/settings.php');

	//Check if session is started or not.
	if(session_id() == '' || !isset($_SESSION)) {
		session_start();
	}

	// Check if user is logged in.
	// if(!isset($_SESSION['loggedin'])) {
	//	$locked = HTTP.HTTPURL.'/view/locked.php';
	//  header('Location: '.$locked);
	// }

?>

<DOCTYPE html>

<html>
	<head>
		<?php require_once('assets/header.php'); ?>
	</head>

	<body>

		<div id="wrapper">

			<?php require_once('assets/fixednavbar.php'); ?>

			<!-- Page Content -->
			<div id="page-content-wrapper">

				<div class="container-fluid" id="webbg">

					<?php require_once('assets/routes.php'); ?>
          
				</div>

			</div>

		</div>

		<?php require_once('assets/botjsincludes.php'); ?>

	<body>

<html>