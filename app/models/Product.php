<?php

/**
* 
*/
class Product extends Model
{

	private $id,
		$mod_name,
		$mod_cost,
		$mod_short_name,
		$monthly,
		$item_type,
		$rental_type,
		$order_id,
		$product_qty,
		$product_cost,
		$product_type;

	function __construct($id = '')
	{
		$this->db = Database::getDBI();
		if($id != null){
			$this->id = $id;
			$this->getDetails();
		} else {
			$this->id = null;
		}
	}

	public function getId() { return $this->id; }
	public function getModName() { return $this->mod_name; }
	public function getModCost() { return $this->mod_cost; }
	public function getModShortName() { return $this->mod_short_name; }
	public function getMonthly() { return $this->monthly; }
	public function getItemType() { return $this->item_type; }
	public function getRentalType() { return $this->rental_type; }
	public function getOrderId() { return $this->order_id; }
	public function getProductQuantity() { return $this->product_qty; }
	public function getProductCost() { return $this->product_cost; }
	public function getProductType() { return $this->product_type; }
	
	public function setId($id) { $this->id = $id; }
	public function setModName($name) { $this->mod_name = $name; }
	public function setModCost($cost) { $this->mod_cost = $cost; }
	public function setModShortName($name) { $this->mod_short_name = $name; }
	public function setMonthly($cost) { $this->monthly = $cost; }
	public function setItemType($type) { $this->item_type = $type; }
	public function setRentalType($type) { $this->rental_type = $type; }
	public function setOrderId($id) { $this->order_id = $id; }
	public function setProductQuantity($qty) { $this->product_qty = $qty; }
	public function setProductCost($cost) { $this->product_cost = $cost; }
	public function setProductType($type) { $this->product_type = $type; }

	public function getDetails()
	{
		// Get the product details.
		$sql = 'SELECT * FROM modifications WHERE mod_ID = '.$this->id;
		$this->db->query($sql);
		$res = $this->db->single();

		// Assign details to attributes.
		$this->setModName($res->mod_name);
		$this->setModCost($res->mod_cost);
		$this->setModShortName($res->mod_short_name);
		$this->setMonthly($res->monthly);
		$this->setItemType($res->item_type);
		$this->setRentalType($res->rental_type);
	}

	public function countProducts($where = '')
	{
		$row = '';
		$new_where = '';
		
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}
		$this->db->query('SELECT COUNT(mod_ID) FROM modifications '. $new_where);
		$res = $this->db->results('arr');

		foreach($res as $count){
			$row = $count['COUNT(mod_ID)'];
		}
		
		return $row;
	}    

	public function getProducts($where = '',$limit = '')
	{
		$list = array();

		$new_where = '';
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}

		$sql = 'SELECT * FROM modifications ' . $new_where . $limit;
		$this->db->query($sql);
		$res = $this->db->results('arr');
		
		foreach ($res as $con) {
			array_push($list, new Product($con['mod_ID']));
		}
		
		return $list;
	}

	public function deleteRequestedProduct($quoteId, $type)
	{
		if($type == 'order'){
			// Delete the ordered/quoted product from the database.
			$res = $this->db->delete('product_orders',['order_id'=>$quoteId]);
		} elseif ($type == 'quote') {
			// Delete the ordered/quoted product from the database.
			$res = $this->db->delete('product_orders',['quote_id'=>$quoteId]);
		}

		// Check to see if the query ran properly.
		if(!$res)
		{
			throw new Exception('The product was not deleted from the quote/order.');
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

		$this->db->query("SELECT * FROM modifications WHERE
						mod_name LIKE '%". $clean_query ."%' OR
						mod_short_name LIKE '%". $clean_query ."%'
						");

		$results = $this->db->results('arr');
		
		return $results;
	}

	public function update()
	{
		// Need to update the quote in the database.
		$this->db->update('modifications', ['mod_ID'=>$this->getId()],[
			'mod_name' 			=> $this->getModName(),
			'mod_cost' 			=> $this->getModCost(),
			'mod_short_name' 	=> $this->getModShortName(),
			'monthly' 			=> $this->getMonthly(),
			'item_type' 		=> $this->getItemType(),
			'rental_type' 		=> $this->getRentalType()
		]);

		// Get the results of the query.
		$res = $this->db->results('arr');
		
		// Return the results of the query.
		return $res;
	}

	public function delete()
	{
		// Delete the ordered/quoted product from the database.
		$res = $this->db->delete('modifications',['mod_ID'=>$this->getId()]);
		
		// Check to see if the query ran properly.
		if(!$res)
		{
			throw new Exception('The order was not deleted.');
		}
	}

	public function create()
	{
		$res = $this->db->insert('modifications',[
			'mod_name'=>$this->getModName(),
			'mod_cost'=>$this->getModCost(),
			'mod_short_name'=>$this->getModShortName(),
			'monthly'=>$this->getMonthly(),
			'item_type'=>$this->getItemType(),
			'rental_type'=>$this->getRentalType()
			]);

		if(!$res)
		{
			throw new Exception('There was an error inserting the product into the database!');
		}
	}

}

?>