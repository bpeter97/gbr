<?php

/**
* 
*/
class Order extends Model
{

	public $id,
		$quote_id,
		$order_customer,
		$order_customer_id,
		$order_date,
		$order_time,
		$order_type,
		$job_name,
		$job_city,
		$job_address,
		$job_zipcode,
		$ordered_by,
		$onsite_contact,
		$onsite_contact_phone,
		$tax_rate,
		$cost_before_tax,
		$total_cost,
		$sales_tax,
		$monthly_total,
		$stage,
		$driver,
		$driver_notes,
		$delivered,
		$date_delivered,
		$container,
		$delivery_total;
	public $products = array();
		
	public function getId() { return $this->id; }
	public function getQuoteId() { return $this->quote_id; }
	public function getCustomer() { return $this->order_customer; }
	public function getCustomerId() { return $this->order_customer_id; }
	public function getDate() { return $this->order_date; }
	public function getTime() { return $this->order_time; }
	public function getType() { return $this->order_type; }
	public function getJobName() { return $this->job_name; }
	public function getJobCity() { return $this->job_city; }
	public function getJobAddress() { return $this->job_address; }
	public function getJobZipcode() { return $this->job_zipcode; }
	public function getOrderedBy() { return $this->ordered_by; }
	public function getOnsiteContact() { return $this->onsite_contact; }
	public function getOnsiteContactPhone() { return $this->onsite_contact_phone; }
	public function getTaxRate() { return $this->tax_rate; }
	public function getCostBeforeTax() { return $this->cost_before_tax; }
	public function getTotalCost() { return $this->total_cost; }
	public function getSalesTax() { return $this->sales_tax; }
	public function getMonthlyTotal() { return $this->monthly_total; }
	public function getStage() { return $this->stage; }
	public function getDriver() { return $this->driver; }
	public function getDriverNotes() { return $this->driver_notes; }
	public function getDelivered() { return $this->delivered; }
	public function getDateDelivered() { return $this->date_delivered; }
	public function getContainer() { return $this->container; }
	public function getDeliveryTotal() { return $this->delivery_total; }
	public function getProducts() { return $this->products; }
	
	public function setId($id) { $this->id = $id; }
	public function setQuoteId($id) { $this->quote_id = $id; }
	public function setCustomer($name) { $this->order_customer = $name; }
	public function setCustomerId($id) { $this->order_customer_id = $id; }
	public function setOrderDate($datetime) { $this->order_date = $datetime; }
	public function setOrderTime($datetime) { $this->order_time = $datetime; }
	public function setOrderType($type) { $this->order_type = $type; }
	public function setJobName($name) { $this->job_name = $name; }
	public function setJobCity($city) { $this->job_city = $city; }
	public function setJobAddress($address) { $this->job_address = $address; }
	public function setJobZipcode($zipcode) { $this->job_zipcode = $zipcode; }
	public function setOrderedBy($name) { $this->ordered_by = $name; }
	public function setOnsiteContact($name) { $this->onsite_contact = $name; }
	public function setOnsiteContactPhone($phone) { $this->onsite_contact_phone = $phone; }
	public function setTaxRate($tax_rate) { $this->tax_rate = $tax_rate; }
	public function setCostBeforeTax($cost_before_tax) { $this->cost_before_tax = $cost_before_tax; }
	public function setTotalCost($cost) { $this->total_cost = $cost; }
	public function setSalesTax($cost) { $this->sales_tax = $cost; }
	public function setMonthlyTotal($cost) { $this->monthly_total = $cost; }
	public function setStage($stage) { $this->stage = $stage; }
	public function setDriver($name) { $this->driver = $name; }
	public function setDriverNotes($notes) { $this->driver_notes = $notes; }
	public function setDelivered($bool) { $this->delivered = $bool; }
	public function setDateDelivered($datetime) { $this->date_delivered = $datetime; }
	public function setContainer($container) { $this->container = $container; }
	public function setDeliveryTotal($total) { $this->delivery_total = $total; }

	function __construct($id = '')
	{
		if($id != null){
			$this->setId($id);
			$this->getDetails();
		} else {
			$this->setId(null);
		}
	}

	public function getDetails($id = '')
	{
		// Get the quote details.
		$this->getDB()->select('orders',['order_id'=>$this->getId()]);
		$res = $this->getDB()->single();

		if($id != null)
		{
			$this->setId($id);
		} 
		else 
		{
			$this->setId($res->order_id);
		}

		// Assign details to attributes.
		$this->setQuoteId($res->quote_id);
		$this->setCustomer($res->order_customer);
		$this->setCustomerId($res->order_customer_id);
		$this->setOrderDate($res->order_date);
		$this->setOrderTime($res->order_time);
		$this->setOrderType($res->order_type);
		$this->setJobName($res->job_name);
		$this->setJobCity($res->job_city);
		$this->setJobAddress($res->job_address);
		$this->setJobZipcode($res->job_zipcode);
		$this->setOrderedBy($res->ordered_by);
		$this->setOnsiteContact($res->onsite_contact);
		$this->setOnsiteContactPhone($res->onsite_contact_phone);
		$this->setTaxRate($res->tax_rate);
		$this->setCostBeforeTax($res->cost_before_tax);
		$this->setTotalCost($res->total_cost);
		$this->setSalesTax($res->sales_tax);
		$this->setMonthlyTotal($res->monthly_total);
		$this->setStage($res->stage);
		$this->setDriver($res->driver);
		$this->setDriverNotes($res->driver_notes);
		$this->setDelivered($res->delivered);
		$this->setDateDelivered($res->date_delivered);
		$this->setContainer($res->container);
		$this->setDeliveryTotal($res->delivery_total);
		$this->fetchOrderProducts();
		
	}

	public function countOrders($where = '')
	{		
		$row = '';
		$new_where = '';
		
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}
		$this->getDB()->query('SELECT COUNT(order_id) FROM orders '. $new_where);
		$res = $this->getDB()->results('arr');

		foreach($res as $count){
			$row = $count['COUNT(order_id)'];
		}

		return $row;
		
	}    

	public function fetchOrders($where = '',$limit = '')
	{
		$list = array();

		$new_where = '';
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}

		$sql = 'SELECT * FROM orders ' . $new_where . $limit;
		$this->getDB()->query($sql);
		$res = $this->getDB()->results('arr');
		
		foreach ($res as $con) {
			array_push($list, new Order($con['order_id']));
		}

		return $list;
	}

	// Retrieves the ordered products belonging to this order 
	// and then stores the event in the products array.
	public function fetchOrderProducts()
	{
		$this->getDB()->query('SELECT * FROM product_orders WHERE order_id = '.$this->getId());
		$res = $this->getDB()->results('arr');
		foreach($res as $orderedProd)
		{
			$product = new Product($orderedProd['product_id']);
			$product->setProductCost($orderedProd['product_cost']);
			$product->setProductQuantity($orderedProd['product_qty']);
			$product->setProductType($orderedProd['product_type']);
			$product->setOrderId($this->getId());

			array_push($this->products, $product);
		}

	}

	/*
	 * TODO Implement the conversion of a quote to an order.
	 * TODO Implement capability for rental orders.
	 */
	public function createOrder()
	{
		// Post the data from the order form.
		$this->setOrderDate($_POST['frmorderdate']);
		$this->setOrderTime($_POST['frmordertime']);
		$this->setCustomer($_POST['frmcustomername']);
		$this->setOrderedBy($_POST['frmorderedby']);
		$this->setOrderType($_POST['frmordertype']);
		$this->setJobName($_POST['frmjobname']);
		$this->setJobAddress($_POST['frmjobaddress']);
		$this->setJobCity($_POST['frmjobcity']);
		$this->setJobZipcode($_POST['frmjobzipcode']);
		// Here we are going to post the tax rate and turn it into a float.
		$unformatted_tax_rate = $_POST['frmtaxrate'];
		$this->setTaxRate((float)$unformatted_tax_rate);
		$this->setOnsiteContact($_POST['frmcontact']);
		$this->setOnsiteContactPhone($_POST['frmcontactphone']);
		$this->setTotalCost($_POST['cartTotalCost']);
		$this->setSalesTax($_POST['cartTax']);
		$this->setCostBeforeTax($_POST['cartBeforeTaxCost']);
		$this->setMonthlyTotal($_POST['cartMonthlyTotal']);
		$this->setDeliveryTotal($_POST['cartDeliveryTotal']);

		// Assigning stage as one since it is a newly created order.
		$this->setStage(1);

		

		// Need to insert the new order into the database.
		$this->getDB()->insert('orders',[
				'order_customer'			=>	$this->getCustomer(),
				'order_customer_id'			=>	$this->getCustomerId(),
				'order_date'				=>	$this->getDate(),
				'order_time'				=>	$this->getTime(),
				'order_type'				=>	$this->getType(),
				'job_name'					=>	$this->getJobName(),
				'job_city'					=>	$this->getJobCity(),
				'job_address'				=>	$this->getJobAddress(),
				'job_zipcode'				=>	$this->getJobZipcode(),
				'ordered_by'				=>	$this->getOrderedBy(),
				'onsite_contact'			=>	$this->getOnsiteContact(),
				'onsite_contact_phone'		=>	$this->getOnsiteContactPhone(),
				'tax_rate'					=>	$this->getTaxRate(),
				'cost_before_tax'			=>	$this->getCostBeforeTax(),
				'total_cost'				=>	$this->getTotalCost(),
				'monthly_total'				=>	$this->getMonthlyTotal(),
				'sales_tax'					=>	$this->getSalesTax(),
				'delivery_total'			=>	$this->getDeliveryTotal(),
				'stage'						=>	$this->getStage()]);

		// If properly inserted, grab the ID, else throw error.
		if($this->getDB()->lastId() != null)
		{
			$this->id = $this->getDB()->lastId();
		} 
		else 
		{
			throw new Exception("There was an error inserting the order into the database.");
		}
		
	}

	public function insertOrderedProducts($id = null)
	{
		if($id !== null)
		{
			$i = 0;
			while ($i < $_POST['itemCount'])
			{
				$post_product = json_decode($_POST['product'.$i], true);
				$new_product = new Product($post_product['id']);
				$new_product->setProductQuantity($post_product['qty']);
				$new_product->setProductCost($post_product['cost']);
				$i++;
	
				$this->getDB()->insert('product_orders',array('quote_id'=>$id,
														 'order_id'=>$this->id,
														 'product_type'=>$new_product->getItemType(),
														 'product_msn'=>$new_product->getModShortName(),
														 'product_cost'=>$new_product->getProductCost(),
														 'product_qty'=>$new_product->getProductQuantity(),
														 'product_name'=>$new_product->getModName(),
														 'product_id'=>$new_product->getId()));
	
				// Check to see if the data was inserted into the db properly, else throw exception.
				if($this->getDB()->lastId() == null)
				{
					throw new Exception('The database did not insert products properly.');
				}
			}
		} else {

			$i = 0;
			while ($i < $_POST['itemCount'])
			{
				$post_product = json_decode($_POST['product'.$i], true);
				$new_product = new Product($post_product['id']);
				$new_product->setProductQuantity($post_product['qty']);
				$new_product->setProductCost($post_product['cost']);
				$i++;

				$this->getDB()->insert('product_orders',array('order_id'=>$this->id,
								'product_type'=>$new_product->getItemType(),
								'product_msn'=>$new_product->getModShortName(),
								'product_cost'=>$new_product->getProductCost(),
								'product_qty'=>$new_product->getProductQuantity(),
								'product_name'=>$new_product->getModName(),
								'product_id'=>$new_product->getId()));

				// Check to see if the data was inserted into the db properly, else throw exception.
				if($this->getDB()->lastId() == null)
				{
					throw new Exception('The database did not insert products properly.');
				}
			}
		}
	}

	public function search()
	{

		$query = $_POST['query'];

		if(is_string($query)){
			$clean_query = filter_var($query, FILTER_SANITIZE_STRING);
		} elseif(is_int($query)){
			$clean_query = filter_var($query, FILTER_SANITIZE_NUMBER_INT);
		} elseif(is_float($query)){
			$clean_query = filter_var($query, FILTER_SANITIZE_NUMBER_FLOAT);
		} else {
			$clean_query = "";
		}

		if(is_numeric($clean_query) && strlen($clean_query) == 6){
			$clean_query = substr_replace($clean_query, '-', 2, 0);
		} elseif (is_numeric($clean_query) && strlen($clean_query) == 7){
			$clean_query = substr_replace($clean_query, '-', 6, 0);
		}

		$this->getDB()->query("SELECT * FROM orders WHERE
						order_customer LIKE '%". $clean_query ."%' OR
						order_date LIKE '%". $clean_query ."%' OR
						order_type LIKE '%". $clean_query ."%' OR
						order_status LIKE '%". $clean_query ."%' OR
						job_name LIKE '%". $clean_query ."%' OR
						job_address LIKE '%". $clean_query ."%' OR
						job_city LIKE '%". $clean_query ."%' OR
						job_zipcode LIKE '%". $clean_query ."%' OR
						attn LIKE '%". $clean_query ."%' OR
						cost_before_tax LIKE '%". $clean_query ."%' OR
						total_cost LIKE '%". $clean_query ."%' OR
						sales_tax LIKE '%". $clean_query ."%' OR
						monthly_total LIKE '%". $clean_query ."%'
						");

		$results = $this->getDB()->results('arr');
		
		return $results;
	}

	public function update()
	{
		// Need to update the quote in the database.
		$this->getDB()->update('orders',['order_ID'=>$this->getID()],[
			'order_date'				=>	$this->getDate(),
			'order_time'				=>	$this->getTime(),
			'order_type'				=>	$this->getType(),
			'job_name'					=>	$this->getJobName(),
			'job_city'					=>	$this->getJobCity(),
			'job_address'				=>	$this->getJobAddress(),
			'job_zipcode'				=>	$this->getJobZipcode(),
			'ordered_by'				=>	$this->getOrderedBy(),
			'onsite_contact'			=>	$this->getOnsiteContact(),
			'onsite_contact_phone'		=>	$this->getOnsiteContactPhone(),
			'tax_rate'					=>	$this->getTaxRate(),
			'cost_before_tax'			=>	$this->getCostBeforeTax(),
			'total_cost'				=>	$this->getTotalCost(),
			'monthly_total'				=>	$this->getMonthlyTotal(),
			'sales_tax'					=>	$this->getSalesTax(),
			'delivery_total'			=>	$this->getDeliveryTotal(),
			'stage'						=>	$this->getStage(),
			'driver'					=>	$this->getDriver(),
			'driver_notes'				=>	$this->getDriverNotes(),
			'delivered'					=>	$this->getDelivered(),
			'date_delivered'			=>	$this->getDateDelivered()
		]);

		// Get the results of the query.
		$res = $this->getDB()->results('arr');
		
		// Return the results of the query.
		return $res;
	}

	public function delete()
	{
		// Delete the ordered/quoted product from the database.
		$res = $this->getDB()->delete('orders',['order_id'=>$this->getId()]);
		
		// Check to see if the query ran properly.
		if(!$res)
		{
			throw new Exception('The order was not deleted.');
		}
	}

	public function rentalHistoryEntry($con_id)
	{
		$itemCost = 0;

		$container = new Container($con_id);
		
		foreach($this->getProducts() as $product)
		{
			if($product->getModShortName() == $container->getShortName())
			{
				$itemCost = $product->getProductCost();
			}
		}

		$this->getDB()->insert('rental_history',[
						'container_id'=>$con_id,
						'start_date'=>$this->getDate(),
						'customer'=>$this->getCustomer(),
						'order_id'=>$this->getId(),
						'cost'=>$itemCost
						]);

		// Check to see if the data was inserted into the db properly, else throw exception.
		if($this->getDB()->lastId() == null)
		{
			throw new Exception('The database did not insert the rental history entry properly.');
		}
	}

}

?>