<?php

/**
* 
*/
class Product extends Model
{

    public $id;
    public $mod_name;
    public $mod_cost;
    public $mod_short_name;
    public $monthly;
    public $item_type;
    public $rental_type;

    public $product_qty;
    public $product_cost;
    public $product_type;

    
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

        // Get the product details.
        $this->db->select('modifications','*','','mod_ID = ' . $this->id);
        $this->res = $this->db->getResult();

        // Assign details to attributes.
        $this->mod_name = $this->res[0]['mod_name'];
        $this->mod_cost = $this->res[0]['mod_cost'];
        $this->mod_short_name = $this->res[0]['mod_short_name'];
        $this->monthly = $this->res[0]['monthly'];
        $this->item_type = $this->res[0]['item_type'];
        $this->rental_type = $this->res[0]['rental_type'];

        // Don't forget to disconnect the DB connection!
        $this->db->disconnect();
        $this->resetResDb();
    }

    public function countProducts($where = ''){
        $row = '';
        $new_where = '';
        $this->db = new Database();
        $this->db->connect();
        
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }
        $this->db->sql('SELECT COUNT(mod_ID) FROM modifications '. $new_where);
        $this->res = $this->db->getResult();

        foreach($this->res as $count){
            $row = $count['COUNT(mod_ID)'];
        }

        $this->db->disconnect();
        $this->resetResDb();
        return $row;
    }    

    public function getProducts($where = '',$limit = ''){
        $list = array();
        $this->db = new Database();

        $new_where = '';
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }

        $sql = 'SELECT * FROM modifications ' . $new_where . $limit;
        $this->db->sql($sql);
        $this->res = $this->db->getResult();
        
        foreach ($this->res as $con) {
            array_push($list, new Product($con['mod_ID']));
        }

        $this->db->disconnect();
        $this->resetResDb();
        return $list;
    }

    public function getOrderedProduct()
    {
        $this->db->disconnect();
        $this->resetResDb();
    }

}

?>