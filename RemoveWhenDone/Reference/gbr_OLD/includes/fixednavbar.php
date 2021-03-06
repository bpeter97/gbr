<?php
    
    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    $cog = "";

    function getCurrentURL(){
        $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $currentURL .= $_SERVER["SERVER_NAME"];
     
        if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
        {
            $currentURL .= ":".$_SERVER["SERVER_PORT"];
        } 
     
            $currentURL .= $_SERVER["REQUEST_URI"];
        return $currentURL;
    }

    $curl = getCurrentURL();

    $index = HTTP.HTTPURL.'/index.php';

    $con_array = array(HTTP.HTTPURL.VIEW.'/containers.php',
                        HTTP.HTTPURL.VIEW.'/resalecontainerlist.php',
                        HTTP.HTTPURL.VIEW.'/currentrentals.php',
                        HTTP.HTTPURL.VIEW.'/mastercontainers.php',
                        HTTP.HTTPURL.VIEW.'/rentalcontainers.php',
                        HTTP.HTTPURL.VIEW.'/createcontainer.php');

    $cust_array = array(HTTP.HTTPURL.VIEW.'/customers.php',
                        HTTP.HTTPURL.VIEW.'/createcustomer.php');

    $quote_array = array(HTTP.HTTPURL.VIEW.'/quotes.php',
                        HTTP.HTTPURL.VIEW.'/create_quote.php');

    $order_array = array(HTTP.HTTPURL.VIEW.'/createorder.php',
                        HTTP.HTTPURL.VIEW.'/orders.php');

    $cal_url = HTTP.HTTPURL.VIEW.'/calendar.php';

    $prod_url = HTTP.HTTPURL.VIEW.'/products.php';

    $index_active = "";
    $containers_active = "";
    $customers_active = "";
    $quotes_active = "";
    $calendar_active = "";
    $prod_active = "";
    $orders_active = "";

    if($curl == $index) {
        $index_active = "active";
    } elseif(in_array($curl, $con_array)) {
        $containers_active = "active";
    } elseif(in_array($curl, $cust_array)) {
        $customers_active = "active";
    } elseif(in_array($curl, $quote_array)) {
        $quotes_active = "active";
    } elseif($curl == $cal_url) {
        $calendar_active = "active";
    } elseif($curl == $prod_url) {
        $prod_active = "active";
    } elseif(in_array($curl, $order_array)) {
        $order_active = "active";
    }

    // Need to create arrays for pages with drop down navigation and then use in_array to check if the link is in the array to make the page active or not.

    if($_SESSION['usertype']){
        if($_SESSION['usertype'] == "admin"){
            $cog = '<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-delay="10000" data-close-others="true" style=""><span class="glyphicon glyphicon-cog"></span></a>
                        <ul class="dropdown-menu main-dropdown-effects">
                            <li><a href="'.HTTP.HTTPURL.VIEW.'/users.php">View / Edit Users</a></li>
                            <li><a href="#">View / Edit Taxrates</a></li>
                        </ul>
                    </li>';
        }
    }

	echo '
		<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <img height="25" src="'.HTTP.HTTPURL.IMG.'/logo.png">
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav" id="myNavbar">
                        <li class="'.$index_active.'"><a href="'.HTTP.HTTPURL.'/index.php">Home</a></li>
                        <li class="dropdown '.$containers_active.'">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Containers <span class="caret"></span></a>
                            <ul class="dropdown-menu main-dropdown-effects">
                            	<li><a href="'.HTTP.HTTPURL.VIEW.'/createcontainer.php">Create Container</a></li>
                                <li><a href="'.HTTP.HTTPURL.VIEW.'/mastercontainers.php">Master List</a></li>
                                <li><a href="'.HTTP.HTTPURL.VIEW.'/rentalcontainers.php">Rental Fleet</a></li>
                                <li><a href="'.HTTP.HTTPURL.VIEW.'/currentrentals.php">Currently Rented</a></li>
                                <li><a href="'.HTTP.HTTPURL.VIEW.'/resalecontainerlist.php">Resale Fleet</a></li>
                            </ul>
                        </li>
                        <li class="dropdown '.$customers_active.'">
                        	<a href="'.HTTP.HTTPURL.VIEW.'/customers.php" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Customers <span class="caret"></a>
                        	<ul class="dropdown-menu main-dropdown-effects">
                        		<li><a href="'.HTTP.HTTPURL.VIEW.'/createcustomer.php">Create Customer</a></li>
                        		<li><a href="'.HTTP.HTTPURL.VIEW.'/customers.php">View Customers</a></li>
                        	</ul>
                        </li>
                        <li class="dropdown '.$quotes_active.'">
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Quotes <span class="caret"></span></a>
                        	<ul class="dropdown-menu main-dropdown-effects">
                        		<li><a href="'.HTTP.HTTPURL.VIEW.'/create_quote.php">Create Quote</a></li>
                        		<li><a href="'.HTTP.HTTPURL.VIEW.'/quotes.php">View Quotes</a></li>
                        	</ul>
                        </li>
                        <li class="dropdown '.$orders_active.'">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Orders <span class="caret"></span></a>
                            <ul class="dropdown-menu main-dropdown-effects">
                                <li><a href="'.HTTP.HTTPURL.VIEW.'/createorder.php">Create Order</a></li>
                                <li><a href="'.HTTP.HTTPURL.VIEW.'/orders.php">View Orders</a></li>
                            </ul>
                        </li>
                        <li class="'.$prod_active.'"><a href="'.HTTP.HTTPURL.VIEW.'/products.php">Products</a></li>
                        <li class="'.$calendar_active.'"><a href="'.HTTP.HTTPURL.VIEW.'/calendar.php">Calendar</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="welcome_msg">Welcome, '.$_SESSION['userfname'].' '.$_SESSION['userlname'].'!</li>
                        <li class="dropdown">
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-delay="10000" data-close-others="true" style=""><span class="glyphicon glyphicon-search"></span></a>
                            <div class="dropdown-menu main-dropdown-effects" style="width: 415px;">
                            	<div id="#sbox">
	                                <form action="'.HTTP.HTTPURL.VIEW.'/search.php" method="post">
					                <ul class="nav navbar-nav">
					                    <li style="margin-left: 10px;">
					                        <select class="form-control" name="category" id="category">
					                            <option value="choose one">Choose One</option>
					                            <option value="containers">Containers</option>
					                            <option value="customers">Customers</option>
					                            <option value="quotes">Quotes</option>
					                            <option value="orders">Orders</option>
                                                <option value="modifications">Products</option>
					                        </select>
					                    </li>
					                    <li style="margin-left:10px;"><input type="text" class="form-control" name="query" id="query"></li>
					                    <li style="margin-left:10px;"><input class="btn btn-default headerbutton" type="submit" value="Search" /></li>

					                </ul>
					                </form>
				                </div>
                            </div>
                        </li>
                        '.$cog.'
                        <li><a href="'.HTTP.HTTPURL.CONTROLLERS.'/logout.php" style="margin-right:5px;"><span class="glyphicon glyphicon-lock"></span></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        ';

?>