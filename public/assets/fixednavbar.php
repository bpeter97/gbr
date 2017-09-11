<?php
    
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
    
    $main_website = Config::get('site/http').Config::get('site/httpurl');
    $index = Config::get('site/http').Config::get('site/httpurl');

    $con_array = array(
                        $main_website.Config::get('site/containers').'/resalecontainers',
                        $main_website.Config::get('site/containers').'/currentrentals',
                        $main_website.Config::get('site/containers').'/masterlist',
                        $main_website.Config::get('site/containers').'/rentalcontainers',
                        $main_website.Config::get('site/containers').'/create'
                        );

    $cust_array = array($main_website.Config::get('site/customers'),
                        $main_website.Config::get('site/customers').'/create');

    $quote_array = array($main_website.Config::get('site/quotes').'/masterlist',
                        $main_website.Config::get('site/quotes').'/create');

    $order_array = array($main_website.Config::get('site/orders').'/create',
                        $main_website.Config::get('site/orders').'/masterlist');

    $cal_url = $main_website.'/calendar';

    $prod_url = $main_website.'/products';

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
                            <li><a href="'.$main_website.'/users">View / Edit Users</a></li>
                            <li><a href="#">View / Edit Taxrates</a></li>
                        </ul>
                    </li>';
        }
    }

	echo '
		<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <img class="gbr_logo_image" height="25" src="'.$main_website.Config::get('site/resources/img').'/logo.png">
                    <img class="small_gbr_logo_image" height="25" src="'.$main_website.Config::get('site/resources/img').'/logosmall.png">
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav" id="myNavbar">
                        <li class="'.$index_active.'"><a href="'.$index.'">Home</a></li>
                        <li class="dropdown '.$containers_active.'">
                            <a href="'.$main_website.Config::get('site/containers').'/" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Containers <span class="caret"></span></a>
                            <ul class="dropdown-menu main-dropdown-effects">
                            	<li><a href="'. $main_website.Config::get('site/containers') . '/create">Create Container</a></li>
                                <li><a href="'. $main_website.Config::get('site/containers') . '/masterlist">Master List</a></li>
                                <li><a href="'. $main_website.Config::get('site/containers') . '/rentalcontainers">Rental Fleet</a></li>
                                <li><a href="'. $main_website.Config::get('site/containers') . '/currentrentals">Currently Rented</a></li>
                                <li><a href="'. $main_website.Config::get('site/containers') . '/resalecontainers">Resale Fleet</a></li>
                            </ul>
                        </li>
                        <li class="dropdown '.$customers_active.'">
                        	<a href="'.$main_website.Config::get('site/customers').'" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Customers <span class="caret"></a>
                        	<ul class="dropdown-menu main-dropdown-effects">
                        		<li><a href="'.$main_website.Config::get('site/customers').'/create">Create Customer</a></li>
                        		<li><a href="'.$main_website.Config::get('site/customers').'/">View Customers</a></li>
                        	</ul>
                        </li>
                        <li class="dropdown '.$quotes_active.'">
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Quotes <span class="caret"></span></a>
                        	<ul class="dropdown-menu main-dropdown-effects">
                        		<li><a href="'.$main_website.Config::get('site/quotes').'/create_quote">Create Quote</a></li>
                        		<li><a href="'.$quote_array[0].'/">View Quotes</a></li>
                        	</ul>
                        </li>
                        <li class="dropdown '.$orders_active.'">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="true">Orders <span class="caret"></span></a>
                            <ul class="dropdown-menu main-dropdown-effects">
                                <li><a href="'.$main_website.Config::get('site/orders').'/create/sales">Create Sale Order</a></li>
                                <li><a href="'.$main_website.Config::get('site/orders').'/create/rental">Create Rental Order</a></li>
                                <li><a href="'.$main_website.Config::get('site/orders').'/">View Orders</a></li>
                            </ul>
                        </li>
                        <li class="'.$prod_active.'"><a href="'.$main_website.Config::get('site/products').'/">Products</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="welcome_msg">Welcome, '.$_SESSION['userfname'].' '.$_SESSION['userlname'].'!</li>
                        <li class="dropdown">
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-delay="10000" data-close-others="true" style=""><span class="glyphicon glyphicon-search"></span></a>
                            <div class="dropdown-menu main-dropdown-effects" style="width: 415px;">
                            	<div id="#sbox">
	                                <form action="'.$main_website.'/view/search.php" method="post">
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
                        <li><a href="'.$main_website.'/home/logout" style="margin-right:5px;"><span class="glyphicon glyphicon-lock"></span></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        ';

    

?>