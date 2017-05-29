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
?>

<nav class="navbar navbar-default gbr-bar">
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="cust-navbar-header" style="margin-top:11px;">
          	<a href="http://viking.dev/gbr/controller/logout.php" id="lockitup">
    	        <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
    	    </a>
    	    <a href="#menu-toggle" id=menu-toggle>
    	        <button type="button" class="navbar-toggle" id="menu-toggler" data-toggle="collapse" data-target="#myNavbar">
    	            <span class="icon-bar"></span>
    	            <span class="icon-bar"></span>
    	            <span class="icon-bar"></span>
    	        </button>
    	    </a>
	   </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class="navbar-form navbar-left" style="height:41px;" >
                <form action="<?php echo HTTP.HTTPURL.VIEW.'/search.php'; ?>" method="post">
                <ul class="nav navbar-nav navbar-left" style="">
                    <li style="margin-right:5px;">
                        <select class="form-control" name="category" id="category">
                            <option>Choose One</option>
                            <option value="Containers">Containers</option>
                            <option value="customers">Customers</option>
                            <option value="quotes">Quotes</option>
                            <option value="orrders">Orders</option>
                        </select>
                    </li>
                    <li style="margin-right:5px;"><input type="text" class="form-control" name="query" id="query"></li>
                    <li style="margin-right:5px;margin-left:15px;"><input class="btn btn-default headerbutton" type="submit" value="Search" /></li>
                    <li class="welcome_msg">Welcome, <?php echo $_SESSION['userfname'].' '.$_SESSION['userlname'].'!'; ?></li>
                </ul>
                </form>
            </div><!-- /.navbar-form -->
            <div class="navbar-form navbar-right">
                <ul class="nav navbar-nav navbar-right" style="margin-top:-3px;">
                    <li><a class="containerlink" href="http://viking.dev/gbr/view/create_quote.php"><button type="button" class="btn btn-default headerbutton" style="margin-top:-8px;">Create Quote</button></a></li>
                    <li><a class="containerlink" href="" data-toggle="modal" data-target="#createQuote"><button type="button" class="btn btn-default headerbutton" style="">Create Order</button></a></li>
                    <li><a class="containerlink" href="" data-toggle="modal" data-target="#createContainer"><button type="button" class="btn btn-default headerbutton" style="">Create Container</button></a></li>
                    <li><a class="containerlink" href="" data-toggle="modal" data-target="#createQuote"><button type="button" class="btn btn-default headerbutton" style="">Create Customer</button></a></li>
                </ul>
            </div>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
