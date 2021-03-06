<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * Customers are named as plural where models are singular.
 *
 * @class Customers
 */
class Customers extends Controller
{
	// Index page that references the masterlist page.
	public function index()
	{
		$this->masterlist();
	}

	// This will be the page that shows all of the current customers.
	public function masterlist()
	{
		if($this->checkLogin())
		{
			$customer = $this->model('Customer');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			if(isset($_GET['f'])){
				$page_rows = 9999;
			} else {
				$page_rows = 100;
			}

			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

			if(!isset($_GET['f'])){
				// Grab the container information with the limit.
				$custList = $customer->getCustomers('',$limit);
			} else {
				$filter_char = $_GET['f'];
				// Grab the container information with the limit.
				$custList = $customer->getCustomers('',$limit,$filter_char);
			}

			$row = $customer->countCustomers();

			$this->view('customers/masterlist', ['custList'=>$custList, 'row'=>$row, 'page_rows'=>$page_rows]);
		}

	}

	// This page will be used to create customers.
	public function create()
	{
		if(isset($_GET['action']))
		{
			if($_GET['action'] == 'create')
			{
				$customer = $this->model('Customer');
				$res = $customer->create();
				if($res)
				{
					$this->masterlist();
				}
			}
		}
		$this->view('customers/create');
	}

	public function id($id)
	{
		$customer = $this->model('Customer');
		$customer->getDetails($id);
		
		
		
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
			if($_GET['action'] == "update")
			{
				$customer->getPost();
				$customer->update();
				header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/customers').'?action=usuccess');
				exit;
			}
		} else {
			$quoteList = $customer->fetchQuoteHistory();
			$orderList = $customer->fetchOrderHistory();
			$rentalList = $customer->fetchRentalHistory();
			$this->view('customers/viewinfo', ['customer'=>$customer,'action'=>'edit','quoteList'=>$quoteList,'orderList'=>$orderList,'rentalList'=>$rentalList]);
		}
		
	}

	public function delete($id)
	{
		$customer = new Customer($id);

		try {
			$customer->delete();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		if(!$e){
			header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/customers').'?action=dsuccess');
		}
		
	}
	
	
}

?>