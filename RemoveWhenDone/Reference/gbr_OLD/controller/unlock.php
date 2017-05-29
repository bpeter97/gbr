<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

	include(BASEURL.CFG.'/database.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

	// Check if logged in.
	if(!isset($_SESSION['loggedin'])) {
	    header('location: '.HTTP.HTTPURL.VIEW.'/locked.php');
	}

	$db = new Database();
	$db->connect();

	$unsafe_username = $_POST['username'];
	$unsafe_password = $_POST['password'];

	$escaped_un = $db->escapeString($unsafe_username);
	$username = $db->stripSlashes($escaped_un);

	$escaped_pass = $db->escapeString($unsafe_password);
	$password = $db->stripSlashes($escaped_pass);

	//check the username and password
	check($username,$password,$db);

// This function checks the username to see if it is on the database.
function check($username,$password,$db){

	$sql = 'SELECT * FROM users WHERE username = "'.$username.'"';
	$db->sql($sql);
	$sel_results = $db->getResult();
	$sel_rows = $db->numRows();

	if ($sel_rows == 1) {
	 	login($username,$password,$db);
 	}
 	else {echo "<strong>Incorrect username <br>
 		Redirecting back to locked page in 5 seconds....</strong>";
 		header('refresh:5;url='.HTTP.HTTPURL.VIEW.'/locked.php?action=uname');
 	}
}

// This function then checks the password if the username was correct.
function login($username,$password,$db){

	$sql = 'SELECT * FROM users WHERE username = "'.$username.'" AND password = "'.$password.'"';
	$db->sql($sql);
	$sel_results = $db->getResult();
	$sel_numrows = $db->numRows();

	if ($sel_numrows == 1) {
		$_SESSION['username'] = $username;
		$_SESSION['userfname'] = $sel_results[0]['firstname'];
		$_SESSION['userlname'] = $sel_results[0]['lastname'];
		$_SESSION['usertitle'] = $sel_results[0]['title'];
		$_SESSION['usertype'] = $sel_results[0]['user_type'];
		$_SESSION['loggedin'] = true;
		header('location: '.HTTP.HTTPURL.'/index.php');
	}
	else {
		echo "<strong>Incorrect password. <br> Redirecting back to locked page in 5 seconds....</strong>";
		header('refresh:5;url='.HTTP.HTTPURL.VIEW.'/locked.php?action=pass');
	}
}

?>
