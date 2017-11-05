<?php

/**
* 
*/
class Calendar extends Model
{

	private $events = array();
	
	public function __construct()
	{
		$this->db = Database::getDBI();
	}

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

	public function deleteEvent($id)
	{

		$event = new Event();
		$event->deleteEvent($id);

	}

	public function editEvent($id, $color, $title)
	{
		$event = new Event($id);
		$event->editEvent($color, $title);
	}

}

?>