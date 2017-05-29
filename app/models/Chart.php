<?php

/**
* 
*/
class Chart
{
    public $db;
    public $curmonth = 1;
    public $begmonth = 1;
    public $quotes = array();
    public $orders = array();
    public $con_array = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
    public $rentals = array();
    public $resales = array();

    public function __construct()
    {
        $this->genChart();
    }

    public function genChart()
    {
        $this->db = new Database();
        $this->db->connect();

        $this->getQuotes();
        $this->getOrders();
        $this->getRentalContainers();
        $this->getResaleContainers();
    }

    public function getQuotes()
    {

        while ($this->curmonth != 13) {

            // Query to see how many quotes there are for the current year.
            $this->db->sql("SELECT COUNT(quote_id) FROM quotes WHERE MONTH(quote_date) = $this->begmonth AND YEAR(quote_date) = ".date('Y'));
            $pag_response = $this->db->getResult();
            foreach($pag_response as $rowcount){
                $row = $rowcount["COUNT(quote_id)"];
            }
            array_push($this->quotes, $row);

            $this->begmonth += 1;
            $this->curmonth += 1;

        }

        $this->resetMonths();
    }

    public function getOrders()
    {

        while ($this->curmonth != 13) {

            // Query to see how many orders there are for the current year.
            $this->db->sql("SELECT COUNT(order_id) FROM orders WHERE MONTH(order_date) = $this->begmonth AND YEAR(order_date) = ".date('Y'));
            $pag_response = $this->db->getResult();
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
        for($x=0;$x<16;$x++)
        {
            $this->db->sql("SELECT COUNT(container_ID) FROM containers WHERE container_size_code = ".$this->con_array[$x]." AND rental_resale = 'Rental' AND is_rented = 'FALSE'");
            $first_res = $this->db->getResult();
            foreach($first_res as $rowcount){
                $row = $rowcount["COUNT(container_ID)"];
            }
            array_push($this->rentals, $row);
        }
    }

    public function getResaleContainers()
    {
        for($x=0;$x<16;$x++)
        {
            $this->db->sql("SELECT COUNT(container_ID) FROM containers WHERE container_size_code = ".$this->con_array[$x]." AND rental_resale = 'Resale' AND is_rented = 'FALSE'");
            $first_res = $this->db->getResult();
            foreach($first_res as $rowcount){
                $row = $rowcount["COUNT(container_ID)"];
            }
            array_push($this->resales, $row);
        }
    }

    public function resetMonths()
    {
        $this->begmonth = 1;
        $this->curmonth = 1;
    }

}

?>