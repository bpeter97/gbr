<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include database.
    include(BASEURL.CFG.'/database.php');

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

	// Pagination Query
	$db->sql("SELECT COUNT(customer_ID) FROM customers");
	$pag_response = $db->getResult();
	foreach($pag_response as $rowcount){
		$row = $rowcount["COUNT(customer_ID)"];
	}

	// # of containers in containers table
	$rows = $row;

	// # of containers to display per pagination
	if(isset($_GET['f'])){
		$page_rows = 9999;
	} else {
		$page_rows = 100;
	}

	// This tells us the page # of our last page
	$last = ceil($rows/$page_rows);

	// This ensures that last is not less than 1.
	if ($last < 1)
	{
		$last = 1;
	}

	// Current Pagination number.
	$pagenum = 1;

	// Get pagenum from URL vars if it is present, else it will equal 1.
	if (isset($_GET['pn'])) {
		$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
	}

	// This is to ensure pagenum never gets less than 1 or more than the last page.
	if ($pagenum < 1) {
		$pagenum = 1;
	} else if ($pagenum > $last) {
		$pagenum = $last;
	}

	// This sets the range of rows to query for the chosen $pagenum.
	$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

	if(!isset($_GET['f'])){
		// This is the beginning of listing each row of data.
		$db->sql("SELECT customer_ID, customer_name, customer_address1, customer_address2, customer_city, customer_state, customer_zipcode, customer_phone, customer_ext, customer_fax, customer_email, customer_rdp, customer_notes, flagged, flag_reason FROM customers ORDER BY customer_name ".$limit);
		$response = $db->getResult();
	} else {
		$filter_char = $_GET['f'];
		$db->sql("SELECT customer_ID, customer_name, customer_address1, customer_address2, customer_city, customer_state, customer_zipcode, customer_phone, customer_ext, customer_fax, customer_email, customer_rdp, customer_notes, flagged, flag_reason FROM customers WHERE customer_name LIKE '".$filter_char."%' ORDER BY customer_name ".$limit);
		$response = $db->getResult();
	}

	$paginationCtrls = '';
	// If there is more than 1 page worth of results
	if($last != 1){
		/* First we check if we are on page one. If we are then we don't need a link to
		   the previous page or the first page so we do nothing. If we aren't then we
		   generate links to the first page, and to the previous page. */

		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn=1">First</a></li>';

		if ($pagenum > 1) {
	        $previous = $pagenum - 1;
			$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a></li>';
			// Render clickable number links that should appear on the left of the target page number
			for($i = $pagenum-2; $i < $pagenum; $i++){
				if($i > 0){
			        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
				}
		    }
	    }

		// Render the target page number, but without it being a link
	    $paginationCtrls .= '<li class="active"><a href="'.$_SERVER['PHP_SELF'].'?pn='.$pagenum.'">'.$pagenum.'</a></li>';

		// Render clickable number links that should appear on the right of the target page number
		for($i = $pagenum+1; $i <= $last; $i++){
			$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li> &nbsp;';
			if($i >= $pagenum+2){
				break;
			}
		}
		// This does the same as above, only checking if we are on the last page, and then generating the "Next"
	    if ($pagenum != $last) {
	        $next = $pagenum + 1;
	        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a></li>';
	    }

	    $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$last.'">Last</a></li>';
	}

	$letterlist = '';
	$c = 'A';
	$chars = array($c);
	while ($c < 'Z') {
		$chars[] = ++$c;
	}

	$counter = 0;
	while ($counter <> 26) {
		$letterlist .= '<li><a href="'.$_SERVER['PHP_SELF'].'?f='.$chars[$counter].'">'.$chars[$counter].'</a></li> &nbsp;';
		$counter += 1;
	}

	if($response) {

		echo '
  			<ul class="pagination">
  				<li><a href="'.$_SERVER['PHP_SELF'].'">ALL</a></li> &nbsp;
    			' . $letterlist . '
    		</ul>
    	';

		echo '
  			<ul class="pagination">
    			' . $paginationCtrls . '
    		</ul>
    	';

		echo '

		<table class="table table-striped table-hover" id="custTable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Phone</th>
					<th>Ext</th>
					<th>Fax</th>
					<th>Email</th>
				</tr>
			</thead>
		';

		$toolcount = 0;

		foreach($response as $row) {

			if($row['flagged'] == "Yes"){
				$toolcount += 1;
				$danger = 'danger';
				$flag_reason = $row['flag_reason'];
				$tooltip = ' data-toggle="popover" data-placement="top" data-popover-content="#a'.$toolcount.'"';
				echo '
				<div id="a'.$toolcount.'" class="hidden">
					<div class="popover-body"><b>'.$flag_reason.'</b></div>
				</div>
				';
			} else {
				$danger = '';
				$flag_reason = '';
				$tooltip = '';
			}

			echo '

			<tbody>
				<tr class="clickable-row '.$danger.'" data-href="'.HTTP.HTTPURL.VIEW.'/customerinfo.php?id=' . $row['customer_ID'].'" '.$tooltip.'>
					<td>' . $row['customer_name'] . '</td>
					<td>' . $row['customer_phone'] . '</td>
					<td>' . $row['customer_ext'] . '</td>
					<td>' . $row['customer_fax'] . '</td>
					<td>' . $row['customer_email'] . '</td>
				</tr>
			</tbody>
			';

		}

		echo '</table>';

	} else {

		echo "Couldn't issue database query.";

	}

	echo '
		<nav aria-label="Page navigation">
  			<ul class="pagination">
    			' . $paginationCtrls . '
    		</ul>
    	</nav>
    	';
?>
