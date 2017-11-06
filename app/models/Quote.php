<?php

/**
* 
*/
class Quote extends Model
{

	private $id,
		$quote_customer,
		$quote_customer_id,
		$quote_type,
		$quote_date,
		$quote_status,
		$job_name,
		$job_address,
		$job_city,
		$job_zipcode,
		$attn,
		$tax_rate,
		$cost_before_tax,
		$total_cost,
		$sales_tax,
		$monthly_total,
		$delivery_total;
	private $products = array();
	
	function __construct($id = null)
	{
		$this->db = Database::getDBI();
		if($id !== null){
			$this->setId($id);
			$this->getDetails();
		} else {
			$this->id = null;
		}
	}
	
	public function getId() { return $this->id; }
	public function getCustomer() { return $this->quote_customer; }
	public function getCustomerId() {return $this->quote_customer_id; }
	public function getType() { return $this->quote_type; }
	public function getDate() { return $this->quote_date; }
	public function getStatus() { return $this->quote_status; }
	public function getJobName() { return $this->job_name; }
	public function getJobAddress() { return $this->job_address; }
	public function getJobCity() { return $this->job_city; }
	public function getJobZipcode() { return $this->job_zipcode; }
	public function getAttention() { return $this->attn; }
	public function getTaxRate() { return $this->tax_rate; }
	public function getCostBeforeTax() { return $this->cost_before_tax; }
	public function getTotalCost() { return $this->total_cost; }
	public function getSalesTax() { return $this->sales_tax; }
	public function getMonthlyTotal() { return $this->monthly_total; }
	public function getQuoteProducts() { return $this->products; }
	public function getDeliveryTotal() { return $this->delivery_total; }
	
	public function setId($id) { $this->id = $id; }
	public function setCustomer($customer) { $this->quote_customer = $customer; }
	public function setCustomerId($id) { $this->quote_customer_id = $id; }
	public function setType($type) { $this->quote_type = $type; }
	public function setDate($date) { $this->quote_date = $date; }
	public function setStatus($status) { $this->quote_status = $status; }
	public function setJobName($name) { $this->job_name = $name; }
	public function setJobAddress($address) { $this->job_address = $address; }
	public function setJobCity($city) { $this->job_city = $city; }
	public function setJobZipcode($zipcode) { $this->job_zipcode = $zipcode; }
	public function setAttention($attn) { $this->attn = $attn; }
	public function setTaxRate($taxrate) { $this->tax_rate = $taxrate; }
	public function setCostBeforeTax($before) { $this->cost_before_tax = $before; }
	public function setTotalCost($total) { $this->total_cost = $total; }
	public function setSalesTax($tax) { $this->sales_tax = $tax; }
	public function setMonthlyTotal($total) { $this->monthly_total = $total; }
	public function setDeliveryTotal($total) { $this->delivery_total = $total; }

	public function getDetails($id = null)
	{

		$this->db->select('quotes',['quote_id'=>$this->getId()]);
		$res = $this->db->single();
	
		if($id != null)
		{
			$this->setId($id);
		}

		// Assign details to attributes.
		$this->setCustomer($res->quote_customer);
		$this->setCustomerId($res->quote_customer_id);
		$this->setType($res->quote_type);
		$this->setDate($res->quote_date);
		$this->setStatus($res->quote_status);
		$this->setJobName($res->job_name);
		$this->setJobAddress($res->job_address);
		$this->setJobCity($res->job_city);
		$this->setJobZipcode($res->job_zipcode);
		$this->setAttention($res->attn);
		$this->setTaxRate($res->tax_rate);
		$this->setCostBeforeTax($res->cost_before_tax);
		$this->setTotalCost($res->total_cost);
		$this->setSalesTax($res->sales_tax);
		$this->setMonthlyTotal($res->monthly_total);
		$this->setDeliveryTotal($res->delivery_total);
	
	}

	public function countQuotes($where = '')
	{
		$row = '';
		$new_where = '';
				
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}
		$this->db->query('SELECT COUNT(quote_id) FROM quotes '. $new_where);
		$res = $this->db->results('arr');

		foreach($res as $count){
			$row = $count['COUNT(quote_id)'];
		}
		
		return $row;
	}    

	public function getQuotes($where = '',$limit = '')
	{
		$list = array();

		$new_where = '';
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}

		$sql = 'SELECT * FROM quotes ' . $new_where . $limit;
		$this->db->query($sql);
		$res = $this->db->results('arr');
		
		foreach ($res as $con) {
			array_push($list, new Quote($con['quote_id']));
		}

		return $list;
	}

	// Retrieves the quoted products belonging to this quote 
	public function fetchQuoteProducts()
	{

		$this->db->query('SELECT * FROM product_orders WHERE quote_id = '.$this->getId());
		$res = $this->db->results('arr');
		foreach($res as $quotedProd)
		{
			$product = new Product($quotedProd['product_id']);
			$product->setProductCost($quotedProd['product_cost']);
			$product->setProductQuantity($quotedProd['product_qty']);
			$product->setProductType($quotedProd['product_type']);

			array_push($this->products, $product);
		}
	}

	public function createQuote()
	{

		// Post the data from the quote form.
		$this->setType($_POST['frmquotetype']);
		$this->setDate($_POST['frmquotedate']);
		$this->setStatus('Open');
		$this->setJobName($_POST['frmjobname']);
		$this->setJobAddress($_POST['frmjobaddress']);
		$this->setJobCity($_POST['frmjobcity']);
		$this->setJobZipcode($_POST['frmjobzipcode']);
		$this->setAttention($_POST['frmattn']);
		// Here we are going to post the tax rate and turn it into a float.
		$unformatted_tax_rate = $_POST['frmtaxrate'];
		$this->setTaxRate((float)$unformatted_tax_rate);
		$this->setCostBeforeTax($_POST['cartBeforeTaxCost']);
		$this->setTotalCost($_POST['cartTotalCost']);
		$this->setSalesTax($_POST['cartTax']);
		$this->setMonthlyTotal($_POST['cartMonthlyTotal']);
		$this->setDeliveryTotal($_POST['cartDeliveryTotal']);

		// Need to insert the new order into the database.
		$this->db->insert('quotes', [
				'quote_customer' 		=> $this->getCustomer(),
				'quote_customer_id' 	=> $this->getCustomerId(),
				'quote_date' 			=> $this->getDate(),
				'quote_status' 			=> $this->getStatus(),
				'quote_type' 			=> $this->getType(),
				'job_name' 				=> $this->getJobName(),
				'job_city' 				=> $this->getJobCity(),
				'job_address' 			=> $this->getJobAddress(),
				'job_zipcode' 			=> $this->getJobZipcode(),
				'attn' 					=> $this->getAttention(),
				'tax_rate' 				=> $this->getTaxRate(),
				'cost_before_tax' 		=> $this->getCostBeforeTax(),
				'total_cost' 			=> $this->getTotalCost(),
				'sales_tax' 			=> $this->getSalesTax(),
				'monthly_total' 		=> $this->getMonthlyTotal(),
				'delivery_total'		=> $this->getDeliveryTotal()
			]);

		// If properly inserted, grab the ID, else throw error.
		if($this->db->lastId() != null)
		{
			$this->id = $this->db->lastId();
		} 
		else 
		{
			throw new Exception("There was an error inserting the quote into the database.");
		}
		
	}

	/*
	 * TODO implement this with quotes.
	 */
	public function insertQuotedProducts()
	{
		// Insert each product into the database.
		$i = 0;
		while ($i < $_POST['itemCount'])
		{
			/*
			 * Here we will decode the json and create each product and insert it into the
			 * product orders database.
			 */
			$post_product = json_decode($_POST['product'.$i], true);
			$new_product = new Product($post_product['id']);
			$new_product->setProductQuantity($post_product['qty']);
			$new_product->setProductCost($post_product['cost']);
			$i++;

			// Insert the data into the database.
			$this->db->insert('product_orders',array('quote_id'=>$this->id,
							'product_type'=>$new_product->getProductType(),
							'product_msn'=>$new_product->getModShortName(),
							'product_cost'=>$new_product->getProductCost(),
							'product_qty'=>$new_product->getProductQuantity(),
							'product_name'=>$new_product->getModName(),
							'product_id'=>$new_product->getId()));

			// Check to see if the data was inserted into the db properly, else throw exception.
			if($this->db->lastId() == null)
			{
				throw new Exception('The database did not insert products properly.');
			}
		}
	}

	public function update()
	{
		// Need to update the quote in the database.
		$this->db->update('quotes', ['quote_id'=>$this->getId()],[
			'quote_customer' 		=> $this->getCustomer(),
			'quote_customer_id' 	=> $this->getCustomerId(),
			'quote_date' 			=> $this->getDate(),
			'quote_status' 			=> $this->getStatus(),
			'quote_type' 			=> $this->getType(),
			'job_name' 				=> $this->getJobName(),
			'job_city' 				=> $this->getJobCity(),
			'job_address' 			=> $this->getJobAddress(),
			'job_zipcode' 			=> $this->getJobZipcode(),
			'attn' 					=> $this->getAttention(),
			'tax_rate' 				=> $this->getTaxRate(),
			'cost_before_tax' 		=> $this->getCostBeforeTax(),
			'total_cost' 			=> $this->getTotalCost(),
			'sales_tax' 			=> $this->getSalesTax(),
			'monthly_total' 		=> $this->getMonthlyTotal(),
			'delivery_total'		=> $this->getDeliveryTotal()
		]);

		// Get the results of the query.
		$res = $this->db->results('arr');
		
		// Return the results of the query.
		return $res;
	}

	public function delete()
	{
		// Delete the ordered/quoted product from the database.
		$this->db->delete('quotes',['quote_id'=>$this->getId()]);
		
		// Get the results of the deletion.
		$res = $this->db->results('arr');

		// Check to see if the query ran properly.
		if(!$res)
		{
			throw new Exception('The product was not deleted from the quote/order.');
		}
	}

}

?>