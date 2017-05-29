<?php

/**
* 
*/
class Quote
{

    public $id;
    public $quote_customer;
    public $quote_type;
    public $quote_date;
    public $quote_status;
    public $job_name;
    public $job_address;
    public $job_city;
    public $job_zipcode;
    public $attn;
    public $cost_before_tax;
    public $total_cost;
    public $sales_tax;
    public $monthly_total;
    public $db;
    
    function __construct($id = '')
    {
        if($id != null){
            $this->id = $id;
            $this->getDetails();
        } else {
            $this->id = null;
        }
    }

    public function getDetails()
    {
        // Database auto connects in the constructor.
        $this->db = new Database();

        // Get the quote details.
        $this->db->select('quotes','*','','quote_id = ' . $this->id);
        $this->res = $this->db->getResult();

        // Assign details to attributes.
        $this->quote_customer = $this->res[0]['quote_customer'];
        $this->quote_date = $this->res[0]['quote_date'];
        $this->quote_status = $this->res[0]['quote_status'];
        $this->job_name = $this->res[0]['job_name'];
        $this->job_address = $this->res[0]['job_address'];
        $this->job_city = $this->res[0]['job_city'];
        $this->job_zipcode = $this->res[0]['job_zipcode'];
        $this->attn = $this->res[0]['attn'];
        $this->cost_before_tax = $this->res[0]['cost_before_tax'];
        $this->total_cost = $this->res[0]['total_cost'];
        $this->sales_tax = $this->res[0]['sales_tax'];
        $this->monthly_total = $this->res[0]['monthly_total'];
        $this->quote_type = $this->res[0]['quote_type'];
        $this->db->disconnect();
    }

    public function countQuotes($where = ''){
        $row = '';
        $new_where = '';
        $this->db->connect();
        
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }
        $this->db->sql('SELECT COUNT(quote_id) FROM quotes '. $new_where);
        $this->res = $this->db->getResult();

        foreach($this->res as $count){
            $row = $count['COUNT(quote_id)'];
        }

        $this->db->disconnect();
        return $row;
    }    

    public function getQuotes($where = '',$limit = ''){
        $list = array();
        $this->db = new Database();

        $new_where = '';
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }

        $sql = 'SELECT * FROM quotes ' . $new_where . $limit;
        $this->db->sql($sql);
        $this->res = $this->db->getResult();
        
        foreach ($this->res as $con) {
            array_push($list, new Quote($con['quote_id']));
        }

        $this->db->disconnect();
        return $list;
    }

}

?>