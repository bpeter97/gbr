<?php

/**
* 
*/
class Pickup extends Model
{

	private $id,
            $date,
            $time,
            $scheduled,
            $completed,
            $order_id,
            $customer_id,
            $container_id;

	function __construct($id = null)
	{
		if($id != null) {
			$this->setId($id);
			$this->getDetails();
		} else {
			$this->setId(null);
		}
	}

	// Getters
    public function getId()             { return $this->id;           }
    public function getDate()           { return $this->date;         }
    public function getTime()           { return $this->time;         }
    public function getScheduled()      { return $this->scheduled;    }
    public function getCompleted()      { return $this->completed;    }
	public function getCustomerId()     { return $this->customer_id;  }
	public function getCustomerName()	{ return $this->customer_name;}
    public function getOrderId()        { return $this->order_id;     }
    public function getContainerId()    { return $this->container_id; }
	
	// Setter
    public function setId($id)          { $this->id = $id; 			  }
    public function setDate($date)      { $this->date = $date; 		  }
    public function setTime($time)      { $this->time = $time; 		  }
    public function setScheduled($bool) { $this->scheduled = $bool;   }
    public function setCompleted($bool) { $this->completed = $bool;   }
	public function setCustomerId($id)  { $this->customer_id = $id;   }
	public function setCustomerName($n)	{ $this->customer_name = $n;  }
    public function setOrderId($id)     { $this->order_id = $id; 	  }
    public function setContainerId($id) { $this->container_id = $id;  }

	public function getDetails()
	{
		// fetch the pickup details.
		$this->getDB()->select('pickups',['id'=>$this->getId()]);
		$res = $this->getDB()->single();

		// setup the object properties.
		$this->setDate($res->date);
		$this->setTime($res->time);
		$this->setScheduled($res->scheduled);
		$this->setCompleted($res->completed);
		$this->setCustomerId($res->customer_id);
		$this->setCustomerName($res->customer_name);
		$this->setOrderId($res->order_id);
		$this->setContainerId($res->container_id);

	}

	public function getPickups($where = '',$limit = '')
	{
		$list = array();
		
		$new_where = '';
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}

		$sql = 'SELECT * FROM pickups ' . $new_where . $limit;
		$this->getDB()->query($sql);
		$res = $this->getDB()->results('arr');
		
		foreach ($res as $pu) {
			array_push($list, new Pickup($pu['id']));
		}

		return $list;
	}

	public function postData($step)
	{
		switch ($step) 
		{

			case '1':

				break;

			case '2':

				break;

			case '3':

				break;

			default:

		}
	}

	public function create()
	{
		// Need to insert the new pickup into the database.
		$this->getDB()->insert('pickups', [
			'date' 			=>	$this->getDate(),
			'time' 			=>	$this->getTime(),
			'scheduled' 	=>	$this->getScheduled(),
			'completed' 	=>	$this->getCompleted(),
			'customer_id'	=>	$this->getCustomerId(),
			'customer_name'	=>	$this->getCustomerName(),
			'order_id' 		=>	$this->getOrderId(),
			'container_id'	=>	$this->getContainerId()
		]);

		// If properly inserted, grab the ID, else throw error.
		if($this->getDB()->lastId() != null)
		{
			$this->setId($this->getDB()->lastId());
		} 
		else 
		{
			throw new Exception("There was an error inserting the pickup into the database.");
		}
	}

	public function delete()
	{
		// Delete the pickup from the database.
		$res = $this->getDB()->delete('pickups',['id'=>$this->getId()]);
		
		// Check to see if the query ran properly.
		if(!$res)
		{
			throw new Exception('The pickup was not deleted from the database.');
		}
	}

	public function update()
	{
		// Need to update the pickup in the database.
		$this->getDB()->update('pickups', ['id'=>$this->getId()], [
			'date' 			=>	$this->getDate(),
			'time' 			=>	$this->getTime(),
			'scheduled' 	=>	$this->getScheduled(),
			'completed' 	=>	$this->getCompleted(),
			'customer_id'	=>	$this->getCustomerId(),
			'customer_name'	=>	$this->getCustomerName(),
			'order_id' 		=>	$this->getOrderId(),
			'container_id'	=>	$this->getContainerId()
		]);

		// Get the results of the query.
		$res = $this->getDB()->results('arr');
		
		// Check to see if the query ran properly.
		if(!$res)
		{
			throw new Exception('The pickup was not updated from the database.');
		}
	}

	public function count($where = '')
	{
		$row = '';
		$new_where = '';
				
		if($where != ''){
			$new_where = 'WHERE '. $where .' ';
		}
		$this->getDB()->query('SELECT COUNT(id) FROM pickups '. $new_where);
		$res = $this->getDB()->results('arr');

		foreach($res as $count){
			$row = $count['COUNT(id)'];
		}
		
		return $row;
	}

}

?>