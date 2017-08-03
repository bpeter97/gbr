<?php

/**
* 
*/
class Order extends Model
{

	public $id,
		$quote_id,
		$order_customer,
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
		$products = array();
		
	public getId() { return $this->id; }
	public getQuoteId() { return $this->quote_id; }
	public getOrderCustomer() { return $this->order_customer; }
	public getOrderDate() { return $this->order_date; }
	public getOrderTime() { return $this->order_time; }
	public getOrderType() { return $this->order_type; }
	public getJobName() { return $this->job_name; }
	public getJobCity() { return $this->job_city; }
	public getJobAddress() { return $this->job_address; }
	public getJobZipcode() { return $this->job_zipcode; }
	public getOrderedBy() { return $this->ordered_by; }
	public getOnsiteContact() { return $this->onsite_contact; }
	public getOnsiteContactPhone() { return $this->onsite_contact_phone; }
	public getTaxRate() { return $this->tax_rate; }
	public getCostBeforeTax() { return $this->cost_before_tax; }
	public getTotalCost() { return $this->total_cost; }
	public getSalesTax() { return $this->sales_tax; }
	public getMonthlyTotal() { return $this->monthly_total; }
	public getStage() { return $this->stage; }
	public getDriver() { return $this->driver; }
	public getDriverNotes() { return $this->driver_notes; }
	public getDelivered() { return $this->delivered; }
	public getDateDelivered() { return $this->date_delivered; }
	public getContainer() { return $this->container; }
	
	public setId($id) { $this->id = $id; }
	public setQuoteId($id) { $this->quote_id = $id; }
	public setOrderCustomer($name) { $this->order_customer = $name; }
	public setOrderDate($datetime) { $this->order_date = $datetime; }
	public setOrderTime($datetime) { $this->order_time = $datetime; }
	public setOrderType($type) { $this->order_type = $type; }
	public setJobName($name) { $this->job_name = $name; }
	public setJobCity($city) { $this->job_city = $city; }
	public setJobAddress($address) { $this->job_address = $address; }
	public setJobZipcode($zipcode) { $this->job_zipcode = $zipcode; }
	public setOrderedBy($name) { $this->ordered_by = $name; }
	public setOnsiteContact($name) { $this->onsite_contact = $name; }
	public setOnsiteContactPhone($phone) { $this->onsite_contact_phone = $phone; }
	public setTaxRate($tax_rate) { $this->tax_rate = $tax_rate; }
	public setCostBeforeTax($cost_before_tax) { $this->cost_before_tax = $cost_before_tax; }
	public setTotalCost($cost) { $this->total_cost = $cost; }
	public setSalesTax($cost) { $this->sales_tax = $cost; }
	public setMonthlyTotal($cost) { $this->monthly_total = $cost; }
	public setStage($stage) { $this->stage = $stage; }
	public setDriver($name) { $this->driver = $name; }
	public setDriverNotes($notes) { $this->driver_notes = $notes; }
	public setDelivered($bool) { $this->delivered = $bool; }
	public setDateDelivered($datetime) { $this->date_delivered = $datetime; }
	public setContainer($container) { $this->container = $container; }

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
		$this->db->select('orders',['order_id'=>$this->getId()]);
		$res = $this->db->single();

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
		$this->setOrderCustomer($res->order_customer);
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
		$this->getOrderProducts();
		
	}

	public function countOrders($where = '')
	{
		
		$row = '';
		$new_where = '';
		
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}
		$this->db->query('SELECT COUNT(order_id) FROM orders '. $new_where);
		$res = $this->db->results('arr');

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
		$this->db->query($sql);
		$res = $this->db->results('arr');
		
		foreach ($res as $con) {
			array_push($list, new Order($con['order_id']));
		}

		return $list;
	}

	// Retrieves the ordered products belonging to this order 
	// and then stores the event in the products array.
	public function getOrderProducts()
	{

		$this->db->query('SELECT * FROM product_orders WHERE order_id = '.$this->getId());
		$res = $this->db->results('arr');
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
		$this->setOrderCustomer($_POST['frmcustomername']);
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
		// Assigning stage as one since it is a newly created order.
		$this->stage = 1;

		// Need to insert the new order into the database.
		$this->db->insert('orders',[
				'order_customer'=>$this->getOrderCustomer(),
				'order_date'=>$this->getOrderDate(),
				'order_time'=>$this->getOrderTime(),
				'order_type'=>$this->getOrderType(),
				'job_name'=>$this->getJobName(),
				'job_city'=>$this->getJobCity(),
				'job_address'=>$this->getJobAddress(),
				'job_zipcode'=>$this->getJobZipcode(),
				'ordered_by'=>$this->getOrderedBy(),
				'onsite_contact'=>$this->getOnsiteContact(),
				'onsite_contact_phone'=>$this->getOnsiteContactPhone(),
				'tax_rate'=>$this->getTaxRate(),
				'cost_before_tax'=>$this->getCostBeforeTax(),
				'total_cost'=>$this->getTotalCost(),
				'sales_tax'=>$this->getSalesTax(),
				'stage'=>$this->getStage()]);

		$res = $this->db->results('arr');

		// If properly inserted, grab the ID, else throw error.
		if($res == true)
		{
			$this->id = $this->db->grabID();
		} 
		else 
		{
			echo "There was an error inserting the order into the database.";
		}
		
	}

	/*
	 * TODO implement this with quotes.
	 */
	public function insertOrderedProducts()
	{
		$i = 0;
		while ($i < $_POST['itemCount'])
		{
			$post_product = json_decode($_POST['product'.$i], true);
			$new_product = new Product($post_product['id']);
			$new_product->setProductQuantity($post_product['qty']);
			$new_product->setProductCost($post_product['cost']);
			$i++;

			$this->db->insert('product_orders',array('order_id'=>$this->id,
							'product_type'=>$new_product->getProductType(),
							'product_msn'=>$new_product->getModShortName(),
							'product_cost'=>$new_product->getProductCost(),
							'product_qty'=>$new_product->getProductQuantity(),
							'product_name'=>$new_product->getModName(),
							'product_id'=>$new_product->getId()));

			$res = $this->db->results('arr');
		}
	}

}

?>