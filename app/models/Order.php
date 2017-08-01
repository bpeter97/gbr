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
	
	function __construct($id = '')
	{
		if($id != null){
			$this->id = $id;
			$this->getDetails();
		} else {
			$this->id = null;
		}
	}

	public function getDetails($id = '')
	{

		// Get the quote details.
		$this->db->select('orders','*','','order_id = ' . $this->id);
		$res = $this->db->results('arr');

		if($id != null)
		{
			$this->id = $id;
		} 
		else 
		{
			$this->id = $res[0]['order_id'];
		}
		// Assign details to attributes.
		$this->quote_id = $res[0]['quote_id'];
		$this->order_customer = $res[0]['order_customer'];
		$this->order_date = $res[0]['order_date'];
		$this->order_time = $res[0]['order_time'];
		$this->order_type = $res[0]['order_type'];
		$this->job_name = $res[0]['job_name'];
		$this->job_city = $res[0]['job_city'];
		$this->job_address = $res[0]['job_address'];
		$this->job_zipcode = $res[0]['job_zipcode'];
		$this->ordered_by = $res[0]['ordered_by'];
		$this->onsite_contact = $res[0]['onsite_contact'];
		$this->onsite_contact_phone = $res[0]['onsite_contact_phone'];
		$this->tax_rate = $res[0]['tax_rate'];
		$this->cost_before_tax = $res[0]['cost_before_tax'];
		$this->total_cost = $res[0]['total_cost'];
		$this->sales_tax = $res[0]['sales_tax'];
		$this->monthly_total = $res[0]['monthly_total'];
		$this->stage = $res[0]['stage'];
		$this->driver = $res[0]['driver'];
		$this->driver_notes = $res[0]['driver_notes'];
		$this->delivered = $res[0]['delivered'];
		$this->date_delivered = $res[0]['date_delivered'];
		$this->container = $res[0]['container'];
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

	public function getOrders($where = '',$limit = '')
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

		$this->db->query('SELECT * FROM product_orders WHERE order_id = '.$this->id);
		$res = $this->db->results('arr');
		foreach($res as $orderedProd)
		{
			$product = new Product($orderedProd['product_id']);
			$product->product_cost = $orderedProd['product_cost'];
			$product->product_qty = $orderedProd['product_qty'];
			$product->product_type = $orderedProd['product_type'];
			$product->order_id = $this->id;

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
		$this->order_date = $_POST['frmorderdate'];
		$this->order_time = $_POST['frmordertime'];
		$this->order_customer = $_POST['frmcustomername'];
		$this->ordered_by = $_POST['frmorderedby'];
		$this->order_type = $_POST['frmordertype'];
		$this->job_name = $_POST['frmjobname'];
		$this->job_address = $_POST['frmjobaddress'];
		$this->job_city = $_POST['frmjobcity'];
		$this->job_zipcode = $_POST['frmjobzipcode'];
		// Here we are going to post the tax rate and turn it into a float.
		$unformatted_tax_rate = $_POST['frmtaxrate'];
		$this->tax_rate = (float)$unformatted_tax_rate;
		$this->onsite_contact = $_POST['frmcontact'];
		$this->onsite_contact_phone = $_POST['frmcontactphone'];
		$this->total_cost = $_POST['cartTotalCost'];
		$this->sales_tax = $_POST['cartTax'];
		$this->cost_before_tax = $_POST['cartBeforeTaxCost'];
		// Assigning stage as one since it is a newly created order.
		$this->stage = 1;

		// Need to insert the new order into the database.
		$this->db->insert('orders',array(
				'order_customer'=>$this->order_customer,
				'order_date'=>$this->order_date,
				'order_time'=>$this->order_time,
				'order_type'=>$this->order_type,
				'job_name'=>$this->job_name,
				'job_city'=>$this->job_city,
				'job_address'=>$this->job_address,
				'job_zipcode'=>$this->job_zipcode,
				'ordered_by'=>$this->ordered_by,
				'onsite_contact'=>$this->onsite_contact,
				'onsite_contact_phone'=>$this->onsite_contact_phone,
				'tax_rate'=>$this->tax_rate,
				'cost_before_tax'=>$this->cost_before_tax,
				'total_cost'=>$this->total_cost,
				'sales_tax'=>$this->sales_tax,
				'stage'=>$this->stage));

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
			$new_product->product_qty = $post_product['qty'];
			$new_product->product_cost = $post_product['cost'];
			$i++;

			$this->db->insert('product_orders',array('order_id'=>$this->id,
							'product_type'=>$new_product->item_type,
							'product_msn'=>$new_product->mod_short_name,
							'product_cost'=>$new_product->product_cost,
							'product_qty'=>$new_product->product_qty,
							'product_name'=>$new_product->mod_name,
							'product_id'=>$new_product->id));

			$res = $this->db->results('arr');
		}
	}

}

?>