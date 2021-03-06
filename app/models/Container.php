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
	
	// Unfold to see properties.
	private $id,
		$release_number,
		$container_size,
		$container_serial_number,
		$container_number,
		$container_shelves,
		$container_paint,
		$container_onbox_numbers,
		$container_signs,
		$rental_resale,
		$is_rented,
		$container_address,
		$latitude,
		$longitude,
		$type,
		$flag,
		$flag_reason,
		$container_size_code,
		$container_short_name;
		
	public function getId() { return $this->id; }
	public function getReleaseNumber() { return $this->release_number; }
	public function getContainerSize() { return $this->container_size; }
	public function getContainerSerialNumber() { return $this->container_serial_number; }
	public function getContainerNumber() { return $this->container_number; }
	public function getContainerShelves() { return $this->container_shelves; }
	public function getContainerPaint() { return $this->container_paint; }
	public function getContainerOnboxNumbers() { return $this->container_onbox_numbers; }
	public function getContainerSigns() { return $this->container_signs; }
	public function getRentalResale() { return $this->rental_resale; }
	public function getIsRented() { return $this->is_rented; }
	public function getContainerAddress() { return $this->container_address; }
	public function getLatitude() { return $this->latitude; }
	public function getLongitude() { return $this->longitude; }
	public function getType() { return $this->type; }
	public function getFlag() { return $this->flag; }
	public function getFlagReason() { return $this->flag_reason; }
	public function getContainerSizeCode() { return $this->container_size_code; }
	public function getShortName() { return $this->container_short_name; }
	
	public function setId($id) { $this->id = $id; }
	public function setReleaseNumber($num) { $this->release_number = $num; }
	public function setContainerSize($size) { $this->container_size = $size ; }
	public function setContainerSerialNumber($num) { $this->container_serial_number = $num; }
	public function setContainerNumber($num) { $this->container_number = $num; }
	public function setContainerShelves($bool) { $this->container_shelves = $bool; }
	public function setContainerPaint($bool) { $this->container_paint = $bool; }
	public function setContainerOnboxNumbers($num) { $this->container_onbox_numbers = $num; }
	public function setContainerSigns($bool) { $this->container_signs = $bool; }
	public function setRentalResale($bool) { $this->rental_resale = $bool; }
	public function setIsRented($bool) { $this->is_rented = $bool; }
	public function setContainerAddress($address) { $this->container_address = $address; }
	public function setLatitude($lat) { $this->latitude = $lat; }
	public function setLongitude($long) { $this->longitude = $long; }
	public function setType($type) { $this->type = $type; }
	public function setFlag($bool) { $this->flag = $bool; }
	public function setFlagReason($reason) { $this->flag_reason = $reason; }
	public function setContainerSizeCode($code) { $this->container_size_code = $code; }
	public function setShortName($shortName) { $this->container_short_name = $shortName; }
	
	// Assign the id property the ID and the db property that was passed when the object was created.
	function __construct($id = '') 
	{		
		if($id != null){
			$this->setId($id);
			$this->getDetails($this->getId());
		} else {
			$this->setId(null);
		}
		
	}

	// This function pulls the container details from the database and stores it in the object.
	public function getDetails($new_id) 
	{

		// Get the containers details.
		$this->setId($new_id);
		$this->getDB()->query('SELECT * FROM containers WHERE container_ID = ' . $this->getId());
		$res = $this->getDB()->single();

		// Assign details to attributes.
		$this->setReleaseNumber($res->release_number);
		$this->setContainerSize($res->container_size);
		$this->setContainerSerialNumber($res->container_serial_number);
		$this->setContainerNumber($res->container_number);
		$this->setContainerShelves($res->container_shelves);
		$this->setContainerPaint($res->container_paint);
		$this->setContainerOnboxNumbers($res->container_onbox_numbers);
		$this->setContainerSigns($res->container_signs);
		$this->setRentalResale($res->rental_resale);
		$this->setIsRented($res->is_rented);
		$this->setContainerAddress($res->container_address);
		$this->setLatitude($res->latitude);
		$this->setLongitude($res->longitude);
		$this->setType($res->type);
		$this->setFlag($res->flag);
		$this->setFlagReason($res->flag_reason);
		$this->setContainerSizeCode($res->container_size_code);
		$this->setShortName($res->container_short_name);

	}
	
	public function getPost()
	{
		$this->setId($_POST['frmcid']);
		$this->setReleaseNumber($_POST['release_number']);
		$this->setContainerSize($_POST['container_size']);
		$this->setContainerSerialNumber($_POST['container_serial_number']);
		$this->setContainerNumber($_POST['container_number']);
		$this->setContainerShelves($_POST['container_shelves']);
		$this->setContainerPaint($_POST['container_paint']);
		$this->setContainerOnboxNumbers($_POST['container_onbox_numbers']);
		$this->setContainerSigns($_POST['container_signs']);
		$this->setRentalResale($_POST['rental_resale']);
		$this->setIsRented($_POST['is_rented']);
		$this->setContainerAddress($_POST['container_address']);
		$this->setFlag($_POST['flag']);
		$this->setFlagReason($_POST['flag_reason']);	
	}

	public function getSizes()
	{

		$this->getDB()->query('SELECT DISTINCT container_size FROM containers');
		$res = $this->getDB()->results('arr');
		
		return $res;
	}

	// Simple update function when a container is edited. -- NOT TESTED YET
	public function update()
	{

		$this->getLatLon($this->getContainerAddress());
		// Update the contianers table using the object attributes.
		$this->getDB()->update('containers',['container_ID'=>$this->getId()],[
			'container_size'=>$this->getContainerSize(),
			'container_size_code'=>$this->getContainerSizeCode(),
			'container_short_name'=>$this->getShortName(),
			'container_serial_number'=>$this->getContainerSerialNumber(),
			'container_number'=>$this->getContainerNumber(),
			'container_shelves'=>$this->getContainerShelves(),
			'container_paint'=>$this->getContainerPaint(),
			'container_onbox_numbers'=>$this->getContainerOnboxNumbers(),
			'container_signs'=>$this->getContainerSigns(),
			'rental_resale'=>$this->getRentalResale(),
			'is_rented'=>$this->getIsRented(),
			'container_address'=>$this->getContainerAddress(),
			'latitude'=>$this->getLatitude(),
			'longitude'=>$this->getLongitude(),
			'type'=>$this->getType(),
			'flag'=>$this->getFlag(),
			'flag_reason'=>$this->getFlagReason()]);

		// Get the results of the query.
		$res = $this->getDB()->results('arr');

		// Return the results of the query.
		return $res;
	}

	// This function will remove the container from the database. -- NOT TESTED YET
	public function delete()
	{

		// Delete the container from the database.
		$this->getDB()->delete('containers',['container_ID'=>$this->getId()]);

		// Get the results of the deletion.
		$res = $this->getDB()->results('arr');

		// Check to see if the query ran properly.
		if(!$res)
		{
			throw new Exception('The container was not deleted from the database.');
		}
	}

	// This will insert a new container into the database.
	public function create()
	{

		$result = '';

		// Post data
		$this->postData(true);

		// Insert the new container into the database.
		$this->getDB()->insert('containers',[
			'release_number'=>$this->getReleaseNumber(),
			'container_size'=>$this->getContainerSize(),
			'container_size_code'=>$this->getContainerSizeCode(),
			'container_short_name'=>$this->getShortName(),
			'container_serial_number'=>$this->getContainerSerialNumber(),
			'container_number'=>$this->getContainerNumber(),
			'container_shelves'=>$this->getContainerShelves(),
			'container_paint'=>$this->getContainerPaint(),
			'container_onbox_numbers'=>$this->getContainerOnboxNumbers(),
			'container_signs'=>$this->getContainerSigns(),
			'rental_resale'=>$this->getRentalResale(),
			'is_rented'=>$this->getIsRented(),
			'container_address'=>$this->getContainerAddress(),
			'latitude'=>$this->getLatitude(),
			'longitude'=>$this->getLongitude(),
			'type'=>$this->getType()]);

		// Get the results of the query.
		if($this->getDB()->lastId() == null)
		{
			echo 'There was an error inserting the event into the calendar!';
		} else {
			$result = true;
		}

		// Return the results of the query.
		return $result;
	}

	// This function updates the containers table to include the  -- NOT TESTED YET
	// new address and update the is_rented attribute to true. -- NOT TESTED YET
	public function deliver($address, $isRented) 
	{

		// Assign variables.
		$this->setContainerAddress($address);
		$this->setIsRented($isRented);

		if($this->getRentalResale() == "Resale"){
			$this->setRentalResale("Sold");
		}

		// Perform db update.
		$this->getDB()->update('containers',['container_ID'=>$this->getId()],[
				'rental_resale'=>$this->getRentalResale(),
				'container_address'=>$this->getContainerAddress(),
				'is_rented'=>"TRUE"]);
		$res = $this->getDB()->results('arr');

		// Return the results.
		return $res;
	}

	// This function gets the latitude and longitude of the containers address.
	public function getLatLon($addy)
	{
	
		$address = urlencode($addy);
		
		if(strlen($address)>0){
			$request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&sensor=true";
			$xml = simplexml_load_file($request_url);
			$status = $xml->status;
			if ($status=="OK") {
				$this->setLongitude($xml->result->geometry->location->lng);
				$this->setLatitude($xml->result->geometry->location->lat);
			}
		}
	}

	// Function to count the containers for pagination.
	public function countContainers($where = '')
	{
		$row = '';
		$new_where = '';

		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}
		$this->getDB()->query('SELECT COUNT(container_ID) FROM containers '. $new_where);
		$res = $this->getDB()->results('arr');

		foreach($res as $count){
			$row = $count['COUNT(container_ID)'];
		}

		return $row;
	}

	// Function to grab containers depending on params.
	public function fetchContainers($where = '',$limit = '')
	{
		$list = array();

		$new_where = '';
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}

		$sql = 'SELECT * FROM containers ' . $new_where . $limit;
		$this->getDB()->query($sql);
		$res = $this->getDB()->results('arr');
		
		foreach ($res as $con) {
			array_push($list, new Container($con['container_ID']));
		}

		return $list;
	}
	
	public function fetchOrderHistory()
	{
		// Get the history of rental's for this container.
		$sql = "SELECT * FROM rental_history WHERE container_id = ?";
		$this->getDB()->query($sql, array($this->getId()));
		$res = $this->getDB()->results('arr');
                              
        $orderList = $res;
                              
		return $res;
	}

	// Simple function to set checkbox values.
	public function checkboxes($check){
		if($check == 1){
			$checkvalue = "Yes";
		} else {
			$checkvalue = "No";
		}
		return $checkvalue;
	}

	// Function to post data. This is used by the create function and the edit function.
	public function postData($checkboxes = false)
	{

		// Check if the container need's to be created or not.
		if ($checkboxes == false){
	
			$this->setReleaseNumber($_POST['release_number']);
			$this->setContainerSize($_POST['container_size']);
			$this->setContainerSerialNumber($_POST['container_serial_number']);
			$this->setContainerNumber($_POST['container_number']);
			$this->setRentalResale($_POST['rental_resale']);
			$this->setIsRented($_POST['is_rented']);
			$this->setContainerAddress($_POST['container_address']);
			$this->setType("container");
			$this->setFlag($_POST['flag']);
			$this->setFlagReason($_POST['flag_reason']);
			$this->getLatLon($_POST['container_address']);
			$this->setContainerShelves($_POST['container_shelves']);
			$this->setContainerPaint($_POST['container_paint']);
			$this->setContainerOnboxNumbers($_POST['container_onbox_numbers']);
			$this->setContainerSigns($_POST['container_signs']);
			
			$this->getDB()->query('SELECT DISTINCT container_size, container_size_code, container_short_name FROM containers');
			$res = $this->getDB()->results('arr');
			
			foreach ($res as $r){
				if($this->getContainerSize() == $r['container_size']){
					$this->setContainerSizeCode($r['container_size_code']);
					$this->setShortName($r['container_short_name']);
				}
			}

		// Else if this is a container that is being created.
		} elseif ($checkboxes == true) {

			$this->setRentalResale($_POST['frmrentalresale']);
			$this->setContainerSize($_POST['frmcontainersize']);
			$this->setReleaseNumber($_POST['frmcontainerrelease']);
			$this->setContainerShelves($this->checkboxes($_POST['containershelves']));
			$this->setContainerPaint($this->checkboxes($_POST['containerpainted']));
			$this->setContainerOnboxNumbers($this->checkboxes($_POST['containergbrnumbers']));
			$this->setContainerSigns($this->checkboxes($_POST['containersigns']));
			$this->setContainerSerialNumber($_POST['frmcontainerserial']);
			$this->setContainerNumber($_POST['frmcontainernumber']);
			$this->setIsRented('FALSE');
			$this->setContainerAddress("6988 Ave 304, Visalia, CA 93291");
			$this->getLatLon($this->getContainerAddress());  
			
			$this->getDB()->query('SELECT DISTINCT container_size, container_size_code, container_short_name FROM containers');
			$res = $this->getDB()->results('arr');
			foreach ($res as $r){
				if($this->getContainerSize() == $r['container_size']){
					$this->setContainerSizeCode($r['container_size_code']);
					$this->setShortName($r['container_short_name']);
				}
			} 
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

		$this->getDB()->query("SELECT * FROM containers WHERE
						release_number LIKE '%". $clean_query ."%' OR container_size LIKE '%". $clean_query ."%' OR
						container_serial_number LIKE '%". $clean_query ."%' OR
						container_number LIKE '%". $clean_query ."%' OR
						container_shelves LIKE '%". $clean_query ."%' OR
						container_paint LIKE '%". $clean_query ."%' OR
						container_onbox_numbers LIKE '%". $clean_query ."%' OR
						container_signs LIKE '%". $clean_query ."%' OR
						rental_resale LIKE '%". $clean_query ."%' OR
						container_address LIKE '%". $clean_query ."%' OR
						container_short_name '%". $clean_query ."%' OR
						type LIKE '%". $clean_query ."%'
						");

		$results = $this->getDB()->results('arr');
		
		return $results;
	}
}

?>