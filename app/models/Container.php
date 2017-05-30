<?php

// ************MODEL************

/**
 * This is the container model, it will interact with the controller 
 * to provide data for the container views.
 *
 * @class Container
 */
class Container extends Model
{
    
    public $release_number;
    public $container_size;
    public $container_serial_number;
    public $container_number;
    public $container_shelves;
    public $container_paint;
    public $container_onbox_numbers;
    public $container_signs;
    public $rental_resale;
    public $is_rented;
    public $container_address;
    public $latitude;
    public $longitude;
    public $type;
    public $flag;
    public $flag_reason;
    public $id;
    public $container_size_code;
    
    // Assign the id property the ID and the db property that was passed when the object was created.
    function __construct($id = '') {

        if($id != null){
            $this->id = $id;
            $this->getDetails($this->id);
        } else {
            $this->id = null;
        }
        
    }

    // This function pulls the container details from the database and stores it in the object.
    public function getDetails($new_id) {

        // Database auto connects in the constructor.
        $this->db = new Database();

        // Get the containers details.
        $this->id = $new_id;
        $this->db->select('containers','*','','container_ID = ' . $this->id);
        $this->res = $this->db->getResult();
        $this->db->disconnect();

        // Assign details to attributes.
        $this->release_number = $this->res[0]['release_number'];
        $this->container_size = $this->res[0]['container_size'];
        $this->container_serial_number = $this->res[0]['container_serial_number'];
        $this->container_number = $this->res[0]['container_number'];
        $this->container_shelves = $this->res[0]['container_shelves'];
        $this->container_paint = $this->res[0]['container_paint'];
        $this->container_onbox_numbers = $this->res[0]['container_onbox_numbers'];
        $this->container_signs = $this->res[0]['container_signs'];
        $this->rental_resale = $this->res[0]['rental_resale'];
        $this->is_rented = $this->res[0]['is_rented'];
        $this->container_address = $this->res[0]['container_address'];
        $this->latitude = $this->res[0]['latitude'];
        $this->longitude = $this->res[0]['longitude'];
        $this->type = $this->res[0]['type'];
        $this->flag = $this->res[0]['flag'];
        $this->flag_reason = $this->res[0]['flag_reason'];
        $this->container_size_code = $this->res[0]['container_size_code'];

    }

    public function update(){

        $this->getLatLon($this->container_address);
        // Update the contianers table using the object attributes.
        $this->db->update('containers',array(
            'container_size'=>$this->container_size,
            'container_size_code'=>$this->container_size_code,
            'container_serial_number'=>$this->container_serial_number,
            'container_number'=>$this->container_number,
            'container_shelves'=>$this->container_shelves,
            'container_paint'=>$this->container_paint,
            'container_onbox_numbers'=>$this->container_onbox_numbers,
            'container_signs'=>$this->container_signs,
            'rental_resale'=>$this->rental_resale,
            'is_rented'=>$this->is_rented,
            'container_address'=>$this->container_address,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'type'=>$this->type,
            'flag'=>$this->flag,
            'flag_reason'=>$this->flag_reason),'container_ID = '.$this->id);

        // Get the results of the query.
        $this->res = $this->db->getResult();

        // Return the results of the query.
        return $this->res;
    }

    // This function will remove the container from the database.
    public function delete(){

        $this->db = new Database();

        // Delete the container from the database.
        $this->db->delete('containers','container_ID='.$this->id);

        $this->db->disconnect();

        // Get the results of the deletion.
        $this->res = $this->db->getResult();

        // Return the results of the query.
        return $this->res;
    }

    // This will insert a new container into the database.
    public function create(){

        // Insert the new container into the database.
        $this->db->insert('containers',array(
            'release_number'=>$this->release_number,
            'container_size'=>$this->container_size,
            'container_size_code'=>$this->container_size_code,
            'container_serial_number'=>$this->container_serial_number,
            'container_number'=>$this->container_number,
            'container_shelves'=>$this->container_shelves,
            'container_paint'=>$this->container_paint,
            'container_onbox_numbers'=>$this->container_onbox_numbers,
            'container_signs'=>$this->container_signs,
            'rental_resale'=>$this->rental_resale,
            'is_rented'=>$this->is_rented,
            'container_address'=>$this->container_address,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'type'=>$this->type));

        // Get the results of the query.
        $this->res = $this->db->getResult();

        // Return the results of the query.
        return $this->res;
    }


    // This function updates the containers table to include the 
    // new address and update the is_rented attribute to true.
    public function deliver($address, $isRented) {

        // Assign variables.
        $this->container_address = $address;
        $this->is_rented = $isRented;

        if($this->rental_resale = "Resale"){
            $this->rental_resale = "Sold";
        }

        // Perform db update.
        $this->db->update('containers',array('rental_resale'=>$this->rental_resale,'container_address'=>$this->container_address,'is_rented'=>$this->is_rented),'container_ID=' . $this->id);
        $this->res = $this->db->getResult();

        // Return the results.
        return $this->res;
    }

    // This function gets the latitude and longitude of the containers address.
    public function getLatLon($addy){
      
        $address = urlencode($addy);
        
        if(strlen($address)>0){
            $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&sensor=true";
            $xml = simplexml_load_file($request_url);
            $status = $xml->status;
            if ($status=="OK") {
                $this->longitude = $xml->result->geometry->location->lng;
                $this->latitude = $xml->result->geometry->location->lat;
            }
        }
    }

    public function countContainers($where = ''){
        $row = '';
        $new_where = '';
        $this->db = new Database();
        $this->db->connect();

        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }
        $this->db->sql('SELECT COUNT(container_ID) FROM containers '. $new_where);
        $this->res = $this->db->getResult();

        foreach($this->res as $count){
            $row = $count['COUNT(container_ID)'];
        }

        $this->db->disconnect();
        $this->resetResDB();
        return $row;
    }

    public function fetchContainers($where = '',$limit = ''){
        $list = array();
        $this->db = new Database();

        $new_where = '';
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }

        $sql = 'SELECT * FROM containers ' . $new_where . $limit;
        $this->db->sql($sql);
        $this->res = $this->db->getResult();
        
        foreach ($this->res as $con) {
            array_push($list, new Container($con['container_ID']));
        }
        $this->db->disconnect();
        $this->resetResDB();
        return $list;
    }

}

?>