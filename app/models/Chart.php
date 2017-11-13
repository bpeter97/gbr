<?php

/**
* 
*/
class Chart extends Model
{
	
	private $curmonth = 1,
		$begmonth = 1;
	public $quotes = array(),
		$orders = array(),
		$rentals = array(),
		$resales = array();
	public $con_array = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
	
	public function getCurMonth() { return $this->curmonth; }
	public function getBegMonth() { return $this->begmonth; }
	
	public function setCurMonth($date) { $this->curmonth = $date; }
	public function setBegMonth($date) { $this->begmonth = $date; }

	public function __construct()
	{
		$this->fetchAllQuotes();
		$this->fetchAllOrders();
		$this->getRentalContainers();
		$this->getResaleContainers();
	}

	public function fetchAllQuotes()
	{

		while ($this->getCurMonth() != 13) {

			// Query to see how many quotes there are for the current year.
			$this->getDB()->query("SELECT COUNT(quote_id) FROM quotes WHERE MONTH(quote_date) = $this->begmonth AND YEAR(quote_date) = ".date('Y'));
			$pag_response = $this->getDB()->results('arr');
			foreach($pag_response as $rowcount){
				$row = $rowcount["COUNT(quote_id)"];
			}
			array_push($this->quotes, $row);

			$this->begmonth += 1;
			$this->curmonth += 1;

		}

		$this->resetMonths();
	}

	public function fetchAllOrders()
	{

		while ($this->getCurMonth() != 13) {

			// Query to see how many orders there are for the current year.
			$this->getDB()->query("SELECT COUNT(order_id) FROM orders WHERE MONTH(order_date) = $this->begmonth AND YEAR(order_date) = ".date('Y'));
			$pag_response = $this->getDB()->results('arr');
			foreach($pag_response as $rowcount){
				$row = $rowcount["COUNT(order_id)"];
			}
			array_push($this->orders, $row);
			
			$this->begmonth += 1;
			$this->curmonth += 1;

		}

		$this->resetMonths();
	}

	public function getRentalContainers()
	{
		for($x=0; $x<16; $x++)
		{
			$this->getDB()->query("SELECT COUNT(container_ID) FROM containers WHERE container_size_code = ".$this->con_array[$x]." AND rental_resale = 'Rental' AND is_rented = 'FALSE'");
			$first_res = $this->getDB()->results('arr');
			foreach($first_res as $rowcount){
				$row = $rowcount["COUNT(container_ID)"];
			}
			array_push($this->rentals, $row);
		}
	}

	public function getResaleContainers()
	{
		for($x=0; $x<16; $x++)
		{
			$this->getDB()->query("SELECT COUNT(container_ID) FROM containers WHERE container_size_code = ".$this->con_array[$x]." AND rental_resale = 'Resale' AND is_rented = 'FALSE'");
			$first_res = $this->getDB()->results('arr');
			foreach($first_res as $rowcount){
				$row = $rowcount["COUNT(container_ID)"];
			}
			array_push($this->resales, $row);
		}
	}

	public function resetMonths()
	{
		$this->setBegMonth(1);
		$this->setCurMonth(1);
	}

}

?>