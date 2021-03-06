<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * Controllers are named as plural where models are singular.
 *
 * @class Containers
 */
class Containers extends Controller
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
			$container = $this->model('Container');

			// Grab the container information with the limit.
			$conList = $container->fetchContainers('','');

			$this->view('containers/masterlist', ['conList'=>$conList]);
		}

		
	}

	// This page shows all of the rental containers in the database.
	public function rentalcontainers()
	{
		if($this->checkLogin())
		{
			$container = $this->model('Container');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			}

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$conList = $container->fetchContainers('rental_resale = "Rental"',$limit);

			$row = $container->countContainers('rental_resale = "Rental"');

			$this->view('containers/rentalcontainers', ['conList'=>$conList, 'row'=>$row]);
		}
		
	}

	// This page shows all of the currently rented containers.
	public function currentrentals()
	{
		if($this->checkLogin())
		{
			$container = $this->model('Container');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$conList = $container->fetchContainers('is_rented = "TRUE"',$limit);

			$row = $container->countContainers('is_rented = "TRUE"');

			$this->view('containers/currentrentals', ['conList'=>$conList, 'row'=>$row]);
		}

	}

	public function resalecontainers()
	{
		if($this->checkLogin())
		{
			$container = $this->model('Container');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$conList = $container->fetchContainers('rental_resale = "Resale"',$limit);

			$row = $container->countContainers('rental_resale = "Resale"');

			$this->view('containers/resalecontainers', ['conList'=>$conList, 'row'=>$row]);
		}

	}

	public function create()
	{
		if(isset($_GET['action']))
		{
			if($_GET['action'] == 'create')
			{
				$container = $this->model('Container');
				$res = $container->create();
				if($res)
				{
					$this->masterlist();
				}
			}
		}
		$this->view('containers/create', []);
	}
	
	public function id($id)
	{
		$con = $this->model('Container');
		$con->getDetails($id);
		
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
			if($_GET['action'] == "update")
			{
				$con->getPost();
				$con->update();
				header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/containers').'?action=usuccess');
				exit;
			}
		} else {
			$con_sizes = $con->getSizes();

			// get the list of rental history for this container.
			$orderList = $con->fetchOrderHistory();

			$this->view('containers/viewinfo', ['container'=>$con,'action'=>'edit','sizes'=>$con_sizes,'orderList'=>$orderList]);
		}
		
	}

	public function delete($id)
	{
		$con = new Container($id);

		try {
			$con->delete();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/containers').'?action=dsuccess');
	}

}

?>