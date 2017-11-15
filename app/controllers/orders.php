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
				// If quoteid is posted, we are converting a quote.
				if($_POST['quoteid'])
				{
					$quote = new Quote($_POST['quoteid']);
					$quote->fetchQuoteProducts();

					// create a customer object to use in the quote.
					$c = new Customer($quote->getCustomerId());
				} else {

					// create a customer object to use in the order.
					$c = new Customer($_POST['frmcustomername'], TRUE);
				}

				// create the order object
				$order = $this->model('Order');

				$order->setCustomerId($c->getId());

				try {

					// create the order
					$order->createOrder();

					// create the products and insert them in the ordered products.
					if($_POST['quoteid']){
						foreach ($quote->getQuoteProducts() as $product) {
							// Delete the old products
							$product->deleteRequestedProduct($quote->getId(), 'quote');
						}
						// Insert the new products with a quote_id as well.
						$order->insertOrderedProducts($quote->getId());
					} else {
						$order->insertOrderedProducts();
					}

					// Update the old quotes status to closed!
					if($_POST['quoteid']){
						$quote->setStatus('Closed');
						$quote->setHidden(null);
						$quote->update();
					}

					// create the event for the new order.
					$event = new Event();
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

			$products = new Product();
			$rentArray = $products->rentArray();
			$pudArray = $products->pudArray();

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

			$this->view('orders/create', ['custList'				=>	$custList, 
										  'customer'				=>	$customer, 
										  'shippingProducts'		=>	$shippingProducts, 
										  'containerProducts'		=>	$containerProducts, 
										  'modificationProducts'	=>	$modificationProducts, 
										  'order_type'				=>	$type, 
										  'rentArray'				=>	$rentArray, 
										  'pudArray'				=>	$pudArray,
										  'quote'					=>	null
										  ]);
			
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
		$rentArray = $products->rentArray();
		$pudArray = $products->pudArray();

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
		$this->view('orders/edit',['customer'				=>	$customer, 
								   'order'					=>	$order, 
								   'orderedProducts'		=>	$order->getProducts(), 
								   'shippingProducts'		=>	$shippingProducts,  
								   'containerProducts'		=>	$containerProducts,  
								   'modificationProducts'	=>	$modificationProducts,  
								   'orderType'				=>	$order->getType(),
								   'rentArray'				=>	$rentArray, 
								   'pudArray'				=>	$pudArray
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

	public function upgrade($id, $stage)
	{
		// Create the order based off of the id passed in.
		$order = new Order($id);

		// Switch cases based on the stage we are upgrading too.
		switch ($stage) 
		{

			case '2':

				// If orderedContainer has been posted, 
				// then the form has been filled out.
				if(isset($_POST['productcount']))
				{
					// Create the customer from the orderId.
					$customer = new Customer($order->getCustomerId());
					
					// Double check that we may need to add the full address in before 
					// delivering the container.
					if($_POST['productcount'] == 1) {

						// Create the container off of the orderedContainer.
						$container = new Container($_POST['frmcontainer1']);
						// Deliver the container.
						$container->deliver($order->getJobAddress(), 'TRUE');
						// Update the container
						$container->update();

						// Create a rental history entry.
						$order->rentalHistoryEntry($container->getId());

					} else {

						$count = 1;
						while ($count < $_POST['productcount']+1)	{
							// Create the container off of the orderedContainer.
							$container = new Container($_POST['frmcontainer'.$count]);
							// Deliver the container.
							$container->deliver($order->getJobAddress(), 'TRUE');
							// Update the container
							$container->update();

							// Create a rental history entry.
							$order->rentalHistoryEntry($container->getId());

							$count++;

						}
					}

					// Set the order to stage 2.
					$order->setStage(2);

					// Update the rest of the order's details.
					/*
					 *	set it delivered
					 *	date delivered
					 *	driver
					 *	driver notes
					 */
					$order->setDelivered('TRUE');
					$order->setDateDelivered($_POST['frmdatedelivered']);
					$order->setDriver($_POST['frmdriver']);
					$order->setDriverNotes($_POST['frmdrivernotes']);

					// Update the order.
					$order->update();

					// Need to delete the previous delivery event.
					$event = new Event();
					$event->getDetailsFromOrderId($id);
					$event->deleteEvent($event->getId());

					header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/orders').'?action=usuccess'); 

				} else {

					// Create a user object to get the list of employees.
					$user_object = new User();
					$con_object = new Container();
					$order = new Order($id);
					$containers = array();
					
					foreach($order->getProducts() as $product)
					{
						if(in_array($product->getModShortName(), $product->conArray()))
						{
							$sql = 'is_rented = "FALSE" AND rental_resale = "Rental" AND container_short_name = "' . $product->getModShortName() . '"';
							$new_containers = $con_object->fetchContainers($sql);

							foreach ($new_containers as $con)
							{
								array_push($containers, $con);
							}
						}
					}

					// get the list of drivers.
					$user_object->fetchEmployees('Driver');
					$drivers = $user_object->getEmployees();

					// create the form.
					$this->view('orders/deliver',['order'=>$order,
												'drivers'=>$drivers,
												'containers'=>$containers
												]);

				}

				break;

			case '3':
				break;
			
			default:
				# code...
				break;
		
		}

	}

	public function downgrade($stage)
	{

	}

	public function viewinfo($id)
	{
		// create the order & get the order products
		$order = new Order($id);
		
		// create the customer object from the order's customer_id
		$customer = new Customer($order->getCustomer(), TRUE);

		if($order->getDelivered() == TRUE)
		{
			$driver = new User($order->getDriver());
		} else {
			$driver = null;
		}

		$products = new Product();
		$rentArray = $products->rentArray();
		$pudArray = $products->pudArray();

		if($order->getType() == 'rental')
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

		$this->view('orders/viewinfo', ['customer'				=>	$customer, 
										'order'					=>	$order, 
										'orderedProducts'		=>	$order->getProducts(), 
										'shippingProducts'		=>	$shippingProducts,  
										'containerProducts'		=>	$containerProducts,  
										'modificationProducts'	=>	$modificationProducts,  
										'orderType'				=>	$order->getType(),
										'rentArray'				=>	$rentArray, 
										'pudArray'				=>	$pudArray,
										'driver'				=>	$driver
									   ]);
	}

	public function rentalorder($id)
	{
		$order = new Order($id);

		$customer = new Customer($order->getCustomerId());

		$prod = new Product();
		$conArray = $prod->conArray();
		$pudArray = $prod->pudArray();

		$this->view('orders/rentalorder',['order'=>$order, 'conArray'=>$conArray, 'pudArray'=>$pudArray, 'customer'=>$customer]);
	}

	public function rentalagreement($id)
	{
		$order = new Order($id);
		
		$customer = new Customer($order->getCustomerId());

		$prod = new Product();
		$conArray = $prod->conArray();
		$pudArray = $prod->pudArray();

		$this->view('orders/rentalagreement',['order'=>$order, 'conArray'=>$conArray, 'pudArray'=>$pudArray, 'customer'=>$customer]);
	}
	
}

?>