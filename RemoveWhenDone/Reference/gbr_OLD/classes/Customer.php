<?php
/**
 * @class Customer
 * @description This is the customer class.
 */
class Customer {

	public $res;
	public $id;
	public $customer_name;
	public $customer_address1;
	public $customer_address2;
	public $customer_city;
	public $customer_zipcode;
	public $customer_state;
	public $customer_phone;
	public $customer_ext;
	public $customer_fax;
	public $customer_email;
	public $customer_rdp;
	public $customer_notes;
	public $flag;
	public $flag_reason;
	public $db;
    
	// Assign the id property the ID and the db property that was passed when the object was created.
	function __construct($db) {
		$this->db = $db;
	}

	// This function pulls the container details from the database and stores it in the object.
	public function getDetails($id) {

		// Get the containers details.
		$this->id = $id;
		$this->db->select('customers','*','','customer_ID = ' . $this->id);
		$this->res = $this->db->getResult();

		// Assign details to attributes.
		$this->customer_name = $this->res[0]['customer_name'];
		$this->customer_address1 = $this->res[0]['customer_address1'];
		$this->customer_address2 = $this->res[0]['customer_address2'];
		$this->customer_city = $this->res[0]['customer_city'];
		$this->customer_zipcode = $this->res[0]['customer_zipcode'];
		$this->customer_state = $this->res[0]['customer_state'];
		$this->customer_phone = $this->res[0]['customer_phone'];
		$this->customer_ext = $this->res[0]['customer_ext'];
		$this->customer_fax = $this->res[0]['customer_fax'];
		$this->customer_email = $this->res[0]['customer_email'];
		$this->container_address = $this->res[0]['container_address'];
		$this->customer_rdp = $this->res[0]['customer_rdp'];
		$this->customer_notes = $this->res[0]['customer_notes'];
		$this->flag = $this->res[0]['flag'];
		$this->flag_reason = $this->res[0]['flag_reason'];

	}

}
?>