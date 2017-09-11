<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * @class Quotes
 */
class Quotes extends Controller
{
	// Index page that references the masterlist page.
	public function index()
	{
		$this->masterlist();
	}

	// This will be the page that shows all quotes.
	public function masterlist()
	{
		
		if($this->checkLogin())
		{
			$quote = $this->model('Quote');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$quoteList = $quote->getQuotes('',$limit);

			$row = $quote->countQuotes();

			$this->view('quotes/masterlist', ['quoteList'=>$quoteList, 'row'=>$row]);
		}
		
	}

	public function create($type, $action = '')
	{
		$this->checkSession();
		
		if($this->checkLogin())
		{
			if($action == 'create')
			{
				// Create the order
				$order = $this->model('Order');
				$order->createOrder();
				// create the products and insert them in the ordered products.
				$order->insertOrderedProducts();
				// create the event for the new order.
				$event = $this->model('Event');
				$event->addEvent($order->id);
				// direct user to the orders view page.
				$this->masterlist();
			} 

			$customer = $this->model('Customer');
			$custList = $customer->getCustomers();

			$products = $this->model('Product');

			if($type == 'rental')
			{
				$shipSQL = "item_type = 'pickup' OR item_type = 'delivery'";
				$containerSQL = "item_type = 'container' AND monthly <> 0";
				$modSQL = "monthly <> 0 AND item_type <> 'container' AND item_type <> 'pickup' AND item_type <> 'delivery'";
			} 
			else
			{
				$shipSQL = "item_type = 'pickup' OR item_type = 'delivery'";
				$containerSQL = "item_type = 'container' AND monthly = 0";
				$modSQL = "monthly = 0 AND item_type <> 'container' AND item_type <> 'pickup' AND item_type <> 'delivery'";
			}
				
			$shippingProducts = $products->getProducts($shipSQL);
			$containerProducts = $products->getProducts($containerSQL);
			$modificationProducts = $products->getProducts($modSQL);

			$this->view('orders/create', ['custList'=>$custList, 'shippingProducts'=>$shippingProducts, 'containerProducts'=>$containerProducts, 'modificationProducts'=>$modificationProducts]);
			
		}

	}
	
}

?>