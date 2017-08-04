<?php

// ************CONTROLLER************

/**
 *
 * @class Users
 */
class Users extends Controller
{
	// Index page that references the masterlist page.
	public function index()
	{
		$this->masterlist();
	}

	// This will be the page that shows all of the current containers.
	public function masterlist()
	{
		if($this->checkLogin())
		{
			$user = $this->model('User');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$userList = $user->fetchUsers('',$limit);

			$row = $user->countUsers();

			$this->view('users/masterlist', ['userList'=>$userList, 'row'=>$row]);
		}
	}
}

?>