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
			$orderList = $order->fetchOrders('',$limit);

			$row = $order->countOrders();

			$this->view('orders/masterlist', ['orderList'=>$orderList, 'row'=>$row]);
		}

	}

	public function create($type, $action = '')
	{
		
		if($this->checkLogin())
		{
			if($action == 'create')
			{
				// create the order object
				$order = $this->model('Order');

				try {

					// create the order
					$order->createOrder();
					// create the products and insert them in the ordered products.
					$order->insertOrderedProducts();

					// create the event for the new order.
					$event = $this->model('Event');
					$event->addEvent($order->id);

				} catch (Exception $e) {

					echo $e->getMessage();

				}

				// direct user to the orders view page.
				$this->masterlist();
			} 

			$customer = $this->model('Customer');
			$custList = $customer->getCustomers();

			if(isset($_GET['cust']))
			{
				$customer->getDetails($_GET['cust']);
			}

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

			$this->view('orders/create', ['custList'=>$custList, 'customer'=>$customer, 'shippingProducts'=>$shippingProducts, 'containerProducts'=>$containerProducts, 'modificationProducts'=>$modificationProducts, 'order_type'=>$type]);
			
		}

	}
	
}

?>