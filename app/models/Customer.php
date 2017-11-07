<?php

/**
* 
*/
class Customer extends Model
{
	
	private $id,
		$customer_name,
		$customer_address1,
		$customer_address2,
		$customer_city,
		$customer_zipcode,
		$customer_state,
		$customer_phone,
		$customer_ext,
		$customer_fax,
		$customer_email,
		$customer_rdp,
		$customer_notes,
		$flag,
		$flag_reason;
		
	public function getId() { return $this->id; }
	public function getCustomerName() { return $this->customer_name; }
	public function getCustomerAddress1() { return $this->customer_address1; }
	public function getCustomerAddress2() { return $this->customer_address2; }
	public function getCustomerCity() { return $this->customer_city; }
	public function getCustomerZipcode() { return $this->customer_zipcode; }
	public function getCustomerState() { return $this->customer_state; }
	public function getCustomerPhone() { return $this->customer_phone; }
	public function getCustomerExt() { return $this->customer_ext; }
	public function getCustomerFax() { return $this->customer_fax; }
	public function getCustomerEmail() { return $this->customer_email; }
	public function getCustomerRdp() { return $this->customer_rdp; }
	public function getCustomerNotes() { return $this->customer_notes; }
	public function getFlag() { return $this->flag; }
	public function getFlagReason() { return $this->flag_reason; }
	
	public function setId($id) { $this->id = $id; }
	public function setCustomerName($name) { $this->customer_name = $name; }
	public function setCustomerAddress1($address) { $this->customer_address1 = $address; }
	public function setCustomerAddress2($address) { $this->customer_address2 = $address; }
	public function setCustomerCity($city) { $this->customer_city = $city; }
	public function setCustomerZipcode($zipcode) { $this->customer_zipcode = $zipcode; }
	public function setCustomerState($state) { $this->customer_state = $state; }
	public function setCustomerPhone($phone) { $this->customer_phone = $phone; }
	public function setCustomerExt($ext) { $this->customer_ext = $ext; }
	public function setCustomerFax($fax) { $this->customer_fax = $fax; }
	public function setCustomerEmail($email) { $this->customer_email = $email; }
	public function setCustomerRdp($rdp) { $this->customer_rdp = $rdp; }
	public function setCustomerNotes($notes) { $this->customer_notes = $notes; }
	public function setFlag($flag) { $this->flag = $flag; }
	public function setFlagReason($flag_reason) { $this->flag_reason = $flag_reason; }

	function __construct($id = null)
	{
		$this->db = Database::getDBI();
		
		if($id != null){
			if(is_int($id)){
				$this->setId($id);
				$this->getDetails($this->getId());
			}elseif(is_string($id)){
				$this->setCustomerName($id);
				$this->getDetails();
			}
		}
	}

	// This function pulls the container details from the database and stores it in the object.
	public function getDetails($id = null) 
	{

		// Get the containers details.
		if($id != null){
			$this->setId($id);
			$this->db->query('SELECT * FROM customers WHERE customer_ID = ' . $this->getId());
			$res = $this->db->single();
		} else {
			$this->db->query('SELECT * FROM customers WHERE customer_name = "' . $this->getCustomerName() . '"');
			$res = $this->db->single();
		}
		
		// Assign details to attributes.
		$this->setId($res->customer_ID);
		$this->setCustomerName($res->customer_name);
		$this->setCustomerAddress1($res->customer_address1);
		$this->setCustomerAddress2($res->customer_address2);
		$this->setCustomerCity($res->customer_city);
		$this->setCustomerZipcode($res->customer_zipcode);
		$this->setCustomerState($res->customer_state);
		$this->setCustomerPhone($res->customer_phone);
		$this->setCustomerExt($res->customer_ext);
		$this->setCustomerFax($res->customer_fax);
		$this->setCustomerEmail($res->customer_email);
		$this->setCustomerRdp($res->customer_rdp);
		$this->setCustomerNotes($res->customer_notes);
		$this->setFlag($res->flagged);
		$this->setFlagReason($res->flag_reason);
		
	}

	// This function counts the number of customers in the database to be able to set up the number of pages to view.
	public function countCustomers($where = '')
	{
		$row = '';
		$new_where = '';

		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}
		$this->db->query('SELECT COUNT(customer_ID) FROM customers '. $new_where);
		$res = $this->db->results('arr');

		foreach($res as $count){
			$row = $count['COUNT(customer_ID)'];
		}
		
		return $row;
	}

	// Simply pulls the customers in the database depending on params.
	public function getCustomers($where = '', $limit = '', $filter = '')
	{
		$list = array();

		$new_where = '';
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}

		if($filter == ''){
			$sql = 'SELECT * FROM customers ' . $new_where . $limit;
			$this->db->query($sql);
			$res = $this->db->results('arr');
		} else {
			$sql = 'SELECT * FROM customers WHERE customer_name LIKE "' . $filter .'%" ORDER BY customer_name ' . $limit;
			$this->db->query($sql);
			$res = $this->db->results('arr');
		}

		foreach ($res as $cus) {
			array_push($list, new Customer($cus['customer_ID']));
		}

		return $list;
	}

	// Function to insert new customer into database.
	public function create()
	{
		// Returning result, so using $result instead of $this->res.
		$result = '';

		$this->postData();

		$this->db->insert('customers',[
				'customer_name'=>$this->getCustomerName(),
				'customer_address1'=>$this->getCustomerAddress1(),
				'customer_address2'=>$this->getCustomerAddress2(),
				'customer_city'=>$this->getCustomerCity(),
				'customer_zipcode'=>$this->getCustomerZipcode(),
				'customer_state'=>$this->getCustomerState(),
				'customer_phone'=>$this->getCustomerPhone(),
				'customer_ext'=>$this->getCustomerExt(),
				'customer_fax'=>$this->getCustomerFax(),
				'customer_email'=>$this->getCustomerEmail(),
				'customer_rdp'=>$this->getCustomerRdp(),
				'customer_notes'=>$this->getCustomerNotes(),
				'flagged'=>$this->getFlag(),
				'flag_reason'=>$this->getFlagReason()]);

		if($this->db->lastId() == null)
		{
			echo 'There was an error inserting the event into the calendar!';
		} else {
			$result = true;
		}

		return $result;
	}

	// Simple function to post data.
	public function postData()
	{
		$this->setCustomerName($_POST['frmcname']);
		$this->setCustomerAddress1($_POST['frmcaddy1']);
		$this->setCustomerAddress2($_POST['frmcaddy2']);
		$this->setCustomerCity($_POST['frmccity']);
		$this->setCustomerZipcode($_POST['frmczipcode']);
		$this->setCustomerState($_POST['frmcstate']);
		$this->setCustomerPhone( $_POST['frmcpnumber']);
		$this->setCustomerExt($_POST['frmcext']);
		$this->setCustomerFax($_POST['frmcfnumber']);
		$this->setCustomerEmail($_POST['frmcemail']);
		$this->setCustomerRdp($_POST['frmcrdp']);
		$this->setCustomerNotes($_POST['frmcnotes']);
		$this->setFlag($_POST['frmflaggedq']);
		$this->setFlagReason($_POST['frmflagreason']);
	}
	
	public function fetchQuoteHistory()
	{
		$sql = "SELECT * FROM quotes WHERE quote_customer = '".$this->getCustomerName()."'";
		$this->db->query($sql);
		$res = $this->db->results('arr');

		$quoteList = array();

		if($res){
			foreach ($res as $quo) {
				$quote = new Quote($quo['quote_id']);
				array_push($quoteList, $quote);
			}
		}

		return $quoteList;
	}
	
	public function fetchOrderHistory()
	{
		$sql = 'SELECT * FROM orders WHERE order_customer = "'.$this->getCustomerName().'" AND order_type = "Sales" OR order_customer = "'.$this->getCustomerName().'" AND order_type = "Resale"';
		$this->db->query($sql);
		$res = $this->db->results('arr');

		$orderList = array();

		if($res){
			foreach ($res as $ord) {
				$order = new Order($ord['order_id']);
				array_push($orderList, $order);
			}
		}

		return $orderList;
	}
	
	public function fetchRentalHistory()
	{
		$sql = "SELECT * FROM orders WHERE order_customer = '".$this->getCustomerName()."' AND order_type = 'Rental'";
		$this->db->query($sql);
		$res = $this->db->results('arr');

		$orderList = array();

		if($res){
			foreach ($res as $ord) {
				$order = new Order($ord['order_id']);
				array_push($orderList, $order);
			}
		}

		return $orderList;
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

		$this->db->query("SELECT * FROM customers WHERE
						customer_name LIKE '%". $clean_query ."%' OR
						customer_address1 LIKE '%". $clean_query ."%' OR
						customer_address2 LIKE '%". $clean_query ."%' OR
						customer_city LIKE '%". $clean_query ."%' OR
						customer_state LIKE '%". $clean_query ."%' OR
						customer_zipcode LIKE '%". $clean_query ."%' OR
						customer_phone LIKE '%". $clean_query ."%' OR
						customer_ext LIKE '%". $clean_query ."%' OR
						customer_fax LIKE '%". $clean_query ."%' OR
						customer_email LIKE '%". $clean_query ."%' OR
						customer_rdp LIKE '%". $clean_query ."%' OR
						customer_notes LIKE '%". $clean_query ."%'
						");

		$results = $this->db->results('arr');
		
		return $results;
	}
}

?>