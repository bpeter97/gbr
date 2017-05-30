<?php

/**
* 
*/
class Order extends Model
{

    public $id;
    public $quote_id;
    public $order_customer;
    public $order_date;
    public $order_time;
    public $order_type;
    public $order_status;
    public $job_name;
    public $job_city;
    public $job_address;
    public $job_zipcode;
    public $ordered_by;
    public $onsite_contact;
    public $onsite_contact_phone;
    public $cost_before_tax;
    public $total_cost;
    public $sales_tax;
    public $monthly_total;
    public $stage;
    public $driver;
    public $driver_notes;
    public $delivered;
    public $date_delivered;
    public $container;
    public $products = array();
    
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
        $this->db->select('orders','*','','order_id = ' . $this->id);
        $this->res = $this->db->getResult();
        $this->db->disconnect();

        // Assign details to attributes.
        $this->id = $this->res[0]['order_id'];
        $this->quote_id = $this->res[0]['quote_id'];
        $this->order_customer = $this->res[0]['order_customer'];
        $this->order_date = $this->res[0]['order_date'];
        $this->order_time = $this->res[0]['order_time'];
        $this->order_type = $this->res[0]['order_type'];
        $this->order_status = $this->res[0]['order_status'];
        $this->job_name = $this->res[0]['job_name'];
        $this->job_city = $this->res[0]['job_city'];
        $this->job_address = $this->res[0]['job_address'];
        $this->job_zipcode = $this->res[0]['job_zipcode'];
        $this->ordered_by = $this->res[0]['ordered_by'];
        $this->onsite_contact = $this->res[0]['onsite_contact'];
        $this->onsite_contact_phone = $this->res[0]['onsite_contact_phone'];
        $this->cost_before_tax = $this->res[0]['cost_before_tax'];
        $this->total_cost = $this->res[0]['total_cost'];
        $this->sales_tax = $this->res[0]['sales_tax'];
        $this->monthly_total = $this->res[0]['monthly_total'];
        $this->stage = $this->res[0]['stage'];
        $this->driver = $this->res[0]['driver'];
        $this->driver_notes = $this->res[0]['driver_notes'];
        $this->delivered = $this->res[0]['delivered'];
        $this->date_delivered = $this->res[0]['date_delivered'];
        $this->container = $this->res[0]['container'];
        $this->getOrderProducts();

        $this->resetResDb();
        
    }

    public function countOrders($where = ''){
        $row = '';
        $new_where = '';
        $this->db = new Database();
        $this->db->connect();
        
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }
        $this->db->sql('SELECT COUNT(order_id) FROM orders '. $new_where);
        $this->res = $this->db->getResult();

        foreach($this->res as $count){
            $row = $count['COUNT(order_id)'];
        }

        $this->db->disconnect();
        $this->resetResDb();
        return $row;
    }    

    public function getOrders($where = '',$limit = ''){
        $list = array();
        $this->db = new Database();

        $new_where = '';
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }

        $sql = 'SELECT * FROM orders ' . $new_where . $limit;
        $this->db->sql($sql);
        $this->res = $this->db->getResult();
        
        foreach ($this->res as $con) {
            array_push($list, new Order($con['order_id']));
        }

        $this->db->disconnect();
        $this->resetResDb();
        return $list;
    }

    public function getOrderProducts()
    {

        $this->db = new Database();

        $this->db->sql('SELECT * FROM product_orders WHERE order_id = '.$this->id);
        $this->res = $this->db->getResult();
        foreach($this->res as $orderedProd)
        {
            $product = new Product($orderedProd['product_id']);
            $product->product_cost = $orderedProd['product_cost'];
            $product->product_qty = $orderedProd['product_qty'];
            $product->product_type = $orderedProd['product_type'];

            array_push($this->products, $product);
        }

        $this->db->disconnect();
        $this->resetResDb();
    }

}

?>