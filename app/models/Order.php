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
    public $job_name;
    public $job_city;
    public $job_address;
    public $job_zipcode;
    public $ordered_by;
    public $onsite_contact;
    public $onsite_contact_phone;
    public $tax_rate;
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

    public function getDetails($id = '')
    {
        // Database auto connects in the constructor.
        $this->db = new Database();

        // Get the quote details.
        $this->db->select('orders','*','','order_id = ' . $this->id);
        $this->res = $this->db->getResult();
        $this->db->disconnect();

        if($id != null)
        {
            $this->id = $id;
        } 
        else 
        {
            $this->id = $this->res[0]['order_id'];
        }
        // Assign details to attributes.
        $this->quote_id = $this->res[0]['quote_id'];
        $this->order_customer = $this->res[0]['order_customer'];
        $this->order_date = $this->res[0]['order_date'];
        $this->order_time = $this->res[0]['order_time'];
        $this->order_type = $this->res[0]['order_type'];
        $this->job_name = $this->res[0]['job_name'];
        $this->job_city = $this->res[0]['job_city'];
        $this->job_address = $this->res[0]['job_address'];
        $this->job_zipcode = $this->res[0]['job_zipcode'];
        $this->ordered_by = $this->res[0]['ordered_by'];
        $this->onsite_contact = $this->res[0]['onsite_contact'];
        $this->onsite_contact_phone = $this->res[0]['onsite_contact_phone'];
        $this->tax_rate = $this->res[0]['tax_rate'];
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

    public function getOrders($where = '',$limit = '')
    {
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

    // Retrieves the ordered products belonging to this order 
    // and then stores the event in the products array.
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
            $product->order_id = $this->id;

            array_push($this->products, $product);
        }

        $this->db->disconnect();
        $this->resetResDb();
    }

    /*
     * TODO Implement the conversion of a quote to an order.
     * TODO Implement capability for rental orders.
     */
    public function createOrder()
    {
        // Create and connect the database object.
        $this->db = new Database();

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

        $this->res = $this->db->getResult();

        // If properly inserted, grab the ID, else throw error.
        if($this->res == true)
        {
            $this->id = $this->db->grabID();
        } 
        else 
        {
            echo "There was an error inserting the order into the database.";
        }

        // Disconnect / reset res and db.
        $this->db->disconnect();
        $this->resetResDb();
    }

    /*
     * TODO implement this with quotes.
     */
    public function insertOrderedProducts()
    {
        $this->db = new Database();

        $i = 0;
        while ($i < $_POST['itemCount'])
        {
            $post_product = json_decode($_POST['product'.$i], true);
            $new_product = new Product($post_product['id']);
            $new_product->product_qty = $post_product['qty'];
            $new_product->product_cost = $post_product['cost'];
            $i++;

            $this->db->insert('product_orders',array(
                'order_id'=>$this->id,
                'product_type'=>$new_product->item_type,
                'product_msn'=>$new_product->mod_short_name,
                'product_cost'=>$new_product->product_cost,
                'product_qty'=>$new_product->product_qty,
                'product_name'=>$new_product->mod_name,
                'product_id'=>$new_product->id));

            $this->res = $this->db->getResult();
            if(!$this->res)
            {
                echo 'There was an error inserting products into product_orders table.';
            }
        }

        $this->db->disconnect();
        $this->resetResDb();
    }

}

?>