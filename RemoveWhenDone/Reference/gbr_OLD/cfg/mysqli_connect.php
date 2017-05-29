<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

	// This is to simply connect to the database.

	$dbc = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME)
		OR die('Could not connect to MySQL DB ' .
				mysqli_connect_error());

?>
