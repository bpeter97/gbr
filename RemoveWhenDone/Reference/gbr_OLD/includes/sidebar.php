<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

	echo '
    	<ul class="sidebar-nav" id="sidebarnav_gbr">

            <li class="sidebar-brand">
                <a href="#">
                    GBR Management System
                </a>
            </li>
            <b>
            <li>
                <a href="'.HTTP.HTTPURL.'/index.php">
                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;&nbsp;Home</a>
            </li>

            <li data-toggle="collapse" data-target="#containerlists" class="">
                <a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;&nbsp;Containers &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-menu-down" aria-hidden="true"></i>
            </li>

            <ul class="sub-menu collapse" id="containerlists">
                <li><a href="'.HTTP.HTTPURL.VIEW.'/mastercontainers.php">Master List</a></li>
                <li><a href="'.HTTP.HTTPURL.VIEW.'/rentalcontainers.php">Rental Fleet</a></li>
                <li><a href="'.HTTP.HTTPURL.VIEW.'/currentrentals.php">Currently Rented</a></li>
                <li><a href="'.HTTP.HTTPURL.VIEW.'/resalecontainerlist.php">Resale Fleet</a></li>
            </ul>

            <li>
                <a href="'.HTTP.HTTPURL.VIEW.'/customers.php">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;Customers</a>
            </li>

            <li>
                <a href="'.HTTP.HTTPURL.VIEW.'/quotes.php">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;Quotes</a>
            </li>
        </b>
        </ul>


	';
?>
