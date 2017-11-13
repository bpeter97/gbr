<?php

/**
* 
*/
class Calendar extends Model
{

	private $events = array();
	
	public function __construct()
	{
		
	}

	public function getEvents()
	{

		$this->getDB()->query('SELECT * FROM events');
		$res = $this->getDB()->results('arr');

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

	public function editEvent($id, $color = null, $title = null, $start = null, $end = null)
	{
		$event = new Event($id);
		$event->editEvent($color, $title, $start, $end);
	}

}

?>