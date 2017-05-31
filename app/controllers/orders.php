<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * @class Orders
 */
class Orders extends Controller
{
	// Index page that references the masterlist page.
	public function index()
	{
		$this->masterlist();
	}

	// This will be the page that shows all quotes.
	public function masterlist()
	{
		$this->checkSession();

		if($this->checkLogin())
		{
			$order = $this->model('Order');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$orderList = $order->getOrders('',$limit);

			$row = $order->countOrders();

			$this->view('orders/masterlist', ['orderList'=>$orderList, 'row'=>$row]);
		}

	}

	public function create($type)
	{
		$this->checkSession();
		
		if($this->checkLogin())
		{
			if($type == "sales")
			{
				$customer = $this->model('Customer');
				$custList = $customer->getCustomers();

				$products = $this->model('Product');
				$shippingProducts = $products->getProducts("item_type = 'pickup' OR item_type = 'delivery'");
				$containerProducts = $products->getProducts("item_type = 'container' AND monthly = 0");
				$modificationProducts = $products->getProducts("monthly = 0 AND item_type <> 'container' AND item_type <> 'pickup' AND item_type <> 'delivery'");

				$this->view('orders/create', ['custList'=>$custList, 'shippingProducts'=>$shippingProducts, 'containerProducts'=>$containerProducts, 'modificationProducts'=>$modificationProducts]);
			} elseif ($type == "rental")
			{
				echo 'The type is: '.$type;
			}
			
		}

	}
	
}

?>