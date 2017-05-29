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

	//Destroy the entire session.
	session_destroy();

	//Set up logout redirection.
	$locked = HTTP.HTTPURL."/view/locked.php";
    header('Location: '.$locked);

?>
