<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include database connections
	include(BASEURL.CFG.'/database.php');
	include(BASEURL.CFG.'/mysqli_connect.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

	function phpAlert($msg) {
    	echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	// Pagination Query
	$pag = "SELECT COUNT(quote_id) FROM quotes";
	$pag_response = mysqli_query($dbc, $pag);
	$row = mysqli_fetch_row($pag_response);

	// # of containers in containers table
	$rows = $row[0];

	// # of containers to display per pagination
	$page_rows = 20;

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


	// This is the beginning of listing each row of data.
	$query = "SELECT quote_id, quote_customer, quote_date, quote_type, quote_status FROM quotes ORDER BY quote_customer $limit";
	$response = @mysqli_query($dbc, $query);

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

	// Are you sure you want to delete alert.
	function deleteClick($id) {
		$q_id = $id;
		$result = confirm("Want to delete?");
		if (result) {
	    //Logic to delete the item
		}
	}

	if($response) {

		echo '
		<nav aria-label="Page navigation">
  			<ul class="pagination">
    			' . $paginationCtrls . '
    		</ul>
    	</nav>
    	';

		echo '

		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Quote ID</th>
					<th>Customer</th>
					<th>Date</th>
					<th>Rental or Resale</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
		';

		while ($row = mysqli_fetch_array($response)) {

			if($row['quote_status']=="Closed") {
				$tablebg = '<tr class="success clickable-row" data-href="'.HTTP.HTTPURL.VIEW.'/reproquote.php?quote_id=' . $row['quote_id'] . '">';
			} else {
				$tablebg = '<tr class="danger clickable-row" data-href="'.HTTP.HTTPURL.VIEW.'/reproquote.php?quote_id=' . $row['quote_id'] . '">';
			}

			echo '

			<tbody>
				'. $tablebg .'
					<td>' . $row['quote_id'] . '</td>
					<td>' . $row['quote_customer'] . '</td>
					<td>' . $row['quote_date'] . '</td>
					<td>' . $row['quote_type'] . '</td>
					<td>' . $row['quote_status'] . '</td>
					<td>
						<a class="btn btn-xs btn-warning" href="'.HTTP.HTTPURL.MODEL.'/createquote_sessions.php?pqid='.$row['quote_id'].'">
					  	<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<a type="button" class="btn btn-xs btn-success" href="'.HTTP.HTTPURL.CONTROLLERS.'/convertquote.php?quote_id='.$row['quote_id'].'&url=listallquotes">
					  	<span class="glyphicon glyphicon-usd"></span>
						</a>
						<a class="btn btn-xs btn-info button-link" href="'.HTTP.HTTPURL.VIEW.'/reproquote.php?quote_id=' . $row['quote_id'] . '">
					  	<span class="glyphicon glyphicon-print"></span>
						</a>
						<a class="btn btn-xs btn-danger" href="'.HTTP.HTTPURL.CONTROLLERS.'/deletequote.php?id='.$row['quote_id'].'&quote_status='.$row['quote_status'].'">
					  	<span class="glyphicon glyphicon-trash"></span>
						</a>
					</td>
				</tr>
			</tbody>
			';

		}

		echo '</table>';

	} else {

		echo "Couldn't issue database query.";

		echo mysqli_error($dbc);

	}

	echo '
		<nav aria-label="Page navigation">
  			<ul class="pagination">
    			' . $paginationCtrls . '
    		</ul>
    	</nav>
    	';

mysqli_close($dbc);

?>
