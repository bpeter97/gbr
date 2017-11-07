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

				// create a customer object to use in the quote.
				$c = new Customer($_POST['frmcustomername']);

				$order->setCustomerId($c->getId());

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

				// Send the user back to the masterlist upon success.
				header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/orders').'?action=csuccess');
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

	public function edit($id)
	{
		// create the order & get the order products
		$order = new Order($id);

		// create the customer object from the order's customer_id
		$customer = new Customer($order->getCustomer(), TRUE);

		// create the product object
		$products = new Product();

		// get the list of products based on the order's type
		if($order->getType() == 'rental' || $order->getType() == 'Rental') 
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

		// create the view
		$this->view('orders/edit',['customer'=>$customer, 
						'order'=>$order, 
						'orderedProducts'=>$order->getProducts(), 
						'shippingProducts'=>$shippingProducts,  
						'containerProducts'=>$containerProducts,  
						'modificationProducts'=>$modificationProducts,  
						'orderType'=>$order->getType() 
						]);
	}

	public function update()
	{
		// Create the order object
		$order = new Order($_POST['orderid']);

		// Delete the order's previously ordered products
		$order->fetchOrderProducts();
		$oldProducts = $order->getProducts();

		foreach ($oldProducts as $oProd) {
			$oProd->deleteRequestedProduct($order->getId(), 'order');
		}

		// Update the order
		// Post the data from the order form.
		$order->setStage($_POST['frmstage']);
		$order->setOrderDate($_POST['frmorderdate']);
		$order->setOrderTime($_POST['frmordertime']);
		$order->setOrderedBy($_POST['frmorderedby']);
		$order->setJobName($_POST['frmjobname']);
		$order->setJobAddress($_POST['frmjobaddress']);
		$order->setJobCity($_POST['frmjobcity']);
		$order->setJobZipcode($_POST['frmjobzipcode']);
		// Here we are going to post the tax rate and turn it into a float.
		$unformatted_tax_rate = $_POST['frmtaxrate'];
		$order->setTaxRate((float)$unformatted_tax_rate);
		$order->setOnsiteContact($_POST['frmcontact']);
		$order->setOnsiteContactPhone($_POST['frmcontactphone']);
		$order->setTotalCost($_POST['cartTotalCost']);
		$order->setSalesTax($_POST['cartTax']);
		$order->setCostBeforeTax($_POST['cartBeforeTaxCost']);
		$order->setMonthlyTotal($_POST['cartMonthlyTotal']);
		$order->setDeliveryTotal($_POST['cartDeliveryTotal']);

		// Update the product_orders table with the new products.
		try {
			$order->update();
			$order->insertOrderedProducts();
		} catch (Exception $e) {
			echo '<script> alert('.$e->getMessage().'); </script>';
		}
		
		// Send the user back to the masterlist upon success.
		if(!$e)
		{
			header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/orders').'?action=usuccess'); 
		}
	}

	public function delete($id)
	{
		// Create quote object,
		$order = new Order($id);

		// Create event object
		$event = new Event();
	
		try {

			// Get the event details using order_id and delete the event.
			$event->getDetailsFromOrderId($id);
			$event->deleteEvent($event->getId());

			// Delete the quote.
			$order->delete();
		
		} catch (Exception $e) {
			echo '<script> alert('. $e->getMessage() .'); </script>';
		}
		

		// Refer back to the masterlist.
		if(!$e)
		{
			header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/orders').'?action=dsuccess'); 
		}
	}
	
}

?>