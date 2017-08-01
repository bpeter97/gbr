<?php

/**
* 
*/
class Calendar extends Model
{

	private $events = array();

	public function getEvents()
	{

		$this->db->query('SELECT * FROM events');
		$res = $this->db->results('arr');

		foreach ($res as $event) 
		{
			array_push($this->events, new Event($event['id']));
		}

		return $this->events;
	}

}

?>