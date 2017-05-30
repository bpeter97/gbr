<?php

/**
* 
*/
class Customer extends Model
{
    
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

    function __construct($id = '')
    {
        if($id != null){
            $this->id = $id;
            $this->getDetails($this->id);
        } else {
            $this->id = null;
        }
    }

    // This function pulls the container details from the database and stores it in the object.
    public function getDetails($id) 
    {

        // Get the containers details.
        $this->id = $id;
        $this->db = new Database();
        $this->db->connect();
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
        $this->customer_rdp = $this->res[0]['customer_rdp'];
        $this->customer_notes = $this->res[0]['customer_notes'];
        $this->flag = $this->res[0]['flagged'];
        $this->flag_reason = $this->res[0]['flag_reason'];

        $this->db->disconnect();
        $this->resetResDb();
    }

    // This function counts the number of customers in the database to be able to set up the number of pages to view.
    public function countCustomers($where = '')
    {
        $row = '';
        $new_where = '';
        $this->db = new Database();
        $this->db->connect();
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }
        $this->db->sql('SELECT COUNT(customer_ID) FROM customers '. $new_where);
        $this->res = $this->db->getResult();

        foreach($this->res as $count){
            $row = $count['COUNT(customer_ID)'];
        }

        $this->db->disconnect();
        $this->resetResDb();
        return $row;
    }

    // Simply pulls the customers in the database depending on params.
    public function getCustomers($where = '', $limit = '', $filter = '')
    {
        $list = array();
        $this->db = new Database();

        $new_where = '';
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }

        if($filter == ''){
            $sql = 'SELECT * FROM customers ' . $new_where . $limit;
            $this->db->sql($sql);
            $this->res = $this->db->getResult();
        } else {
            $sql = 'SELECT * FROM customers WHERE customer_name LIKE "' . $filter .'%" ORDER BY customer_name ' . $limit;
            $this->db->sql($sql);
            $this->res = $this->db->getResult();
        }

        foreach ($this->res as $cus) {
            array_push($list, new Customer($cus['customer_ID']));
        }
        $this->db->disconnect();
        $this->resetResDb();
        return $list;
    }

    // Function to insert new customer into database.
    public function create()
    {
        // Returning result, so using $result instead of $this->res.
        $result = '';

        $this->postData();

        $this->db = new Database();
        $this->db->insert('customers',array(
                                        'customer_name'=>$this->customer_name,
                                        'customer_address1'=>$this->customer_address1,
                                        'customer_address2'=>$this->customer_address2,
                                        'customer_city'=>$this->customer_city,
                                        'customer_zipcode'=>$this->customer_zipcode,
                                        'customer_state'=>$this->customer_state,
                                        'customer_phone'=>$this->customer_phone,
                                        'customer_ext'=>$this->customer_ext,
                                        'customer_fax'=>$this->customer_fax,
                                        'customer_email'=>$this->customer_email,
                                        'customer_rdp'=>$this->customer_rdp,
                                        'customer_notes'=>$this->customer_notes,
                                        'flagged'=>$this->flag,
                                        'flag_reason'=>$this->flag_reason));

        $result = $this->db->getResult();
        $this->db->disconnect();
        $this->resetResDb();

        return $result;
    }

    // Simple function to post data.
    public function postData()
    {
        $this->customer_name = $_POST['frmcname'];
        $this->customer_address1 = $_POST['frmcaddy1'];
        $this->customer_address2 = $_POST['frmcaddy2'];
        $this->customer_city= $_POST['frmccity'];
        $this->customer_zipcode = $_POST['frmczipcode'];
        $this->customer_state = $_POST['frmcstate'];
        $this->customer_phone =  $_POST['frmcpnumber'];
        $this->customer_ext  = $_POST['frmcext'];
        $this->customer_fax = $_POST['frmcfnumber'];
        $this->customer_email = $_POST['frmcemail'];
        $this->customer_rdp = $_POST['frmcrdp'];
        $this->customer_notes = $_POST['frmcnotes'];
        $this->flag = $_POST['frmflaggedq'];
        $this->flag_reason = $_POST['frmflagreason'];
    }
}

?>