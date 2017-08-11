<?php

/**
* 
*/
class Quote extends Model
{

	private $id,
		$quote_customer,
		$quote_type,
		$quote_date,
		$quote_status,
		$job_name,
		$job_address,
		$job_city,
		$job_zipcode,
		$attn,
		$cost_before_tax,
		$total_cost,
		$sales_tax,
		$monthly_total;
	
	function __construct($id = '')
	{
		$this->db = Database::getDBI();
		if($id != null){
			$this->setId($id);
			$this->getDetails();
		} else {
			$this->id = null;
		}
	}
	
	public function getId() { return $this->id; }
	public function getCustomer() { return $this->quote_customer; }
	public function getType() { return $this->quote_type; }
	public function getDate() { return $this->quote_date; }
	public function getStatus() { return $this->quote_status; }
	public function getJobName() { return $this->job_name; }
	public function getJobAddress() { return $this->job_address; }
	public function getJobCity() { return $this->job_city; }
	public function getJobZipcode() { return $this->job_zipcode; }
	public function getAttention() { return $this->attn; }
	public function getCostBeforeTax() { return $this->cost_before_tax; }
	public function getTotalCost() { return $this->total_cost; }
	public function getSalesTax() { return $this->sales_tax; }
	public function getMonthlyTotal() { return $this->monthly_total; }
	
	public function setId($id) { return $this->id = $id; }
	public function setCustomer($customer) { return $this->quote_customer = $customer; }
	public function setType($type) { return $this->quote_type = $type; }
	public function setDate($date) { return $this->quote_date = $date; }
	public function setStatus($status) { return $this->quote_status = $status; }
	public function setJobName($name) { return $this->job_name = $name; }
	public function setJobAddress($address) { return $this->job_address = $address; }
	public function setJobCity($city) { return $this->job_city = $city; }
	public function setJobZipcode($zipcode) { return $this->job_zipcode = $zipcode; }
	public function setAttention($attn) { return $this->attn = $attn; }
	public function setCostBeforeTax($before) { return $this->cost_before_tax = $before; }
	public function setTotalCost($total) { return $this->total_cost = $total; }
	public function setSalesTax($tax) { return $this->sales_tax = $tax; }
	public function setMonthlyTotal($total) { return $this->monthly_total = $total; }

	public function getDetails($id = null)
	{
		
		if($id != null)
		{
			$this->setId($id);
		}
		
		$sql = 'SELECT * FROM quotes WHERE quote_id = ?';
		$this->db->query($sql, $this->getId());
		$res = $this->db->single();
	   
		// Assign details to attributes.
		$this->setCustomer($res->quote_customer);
		$this->setType($res->quote_type);
		$this->setDate($res->quote_date);
		$this->setStatus($res->quote_status);
		$this->setJobName($res->job_name);
		$this->setJobAddress($res->job_address);
		$this->setJobCity($res->job_city);
		$this->setJobZipcode($res->job_zipcode);
		$this->setAttention($res->attn);
		$this->setCostBeforeTax($res->cost_before_tax);
		$this->setTotalCost($res->total_cost);
		$this->setSalesTax($res->sales_tax);
		$this->setMonthlyTotal($res->monthly_total);
	
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

}

?>