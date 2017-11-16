<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * @class Pickups
 */
class Pickups extends Controller
{
	// The index page.
	public function index()
	{
		if($this->checkLogin())
		{
			$pickup = new Pickup();

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the pickup information with the limit.
			$pickupList = $pickup->getPickups('',$limit);

			$row = $pickup->count();

			$this->view('pickups/index', ['pickupList'=>$pickupList, 'row'=>$row]);
		}
	}

}

?>