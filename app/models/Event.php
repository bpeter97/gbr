<?php

/**
* 
*/
class Event extends Model
{
	
	public $id,
		$title,
		$color,
		$start,
		$end,
		$order_id,
		$order;
		
	public getId() { return $this->id; }
	public getTitle() { return $this->title; }
	public getColor() { return $this->color; }
	public getStart() { return $this->start; }
	public getEnd() { return $this->end; }
	public getOrderId() { return $this->order_id; }
	public getOrder() { return $this->order; }

	public setId($id) { $this->id = $id; }
	public setTitle($title) { $this->title = $title; }
	public setColor($color) { $this->color = $color; }
	public setStart($datetime) { $this->start = $datetime; }
	public setEnd($datetime) { $this->end = $datetime; }
	public setOrderId($id) { $this->order_id = $id; }
	public setOrder($obj) { $this->order = $obj; }	
	
	function __construct($id = '')
	{
	
		if($id != null){
			$this->getDetails($id);
			if($this->getOrderId() != 0)
			{
				$this->getOrderInfo();
			}
		} else {
			$this->setId(null);
		}
		
	}

	public function getDetails($id)
	{
		
		$this->setId($id);
		$this->db->select('events',['id'=>$this->getId()]);
		$res = $this->db->results('arr');

		$this->setTitle($res[0]['title']);
		$this->setColor($res[0]['color']);
		$this->setStart($res[0]['start']);
		$this->setEnd($res[0]['end']);
		$this->setOrderId($res[0]['order_id']);
		
	}

	public function getOrderInfo()
	{   
		if($this->getOrderId() != '')
		{
			$this->setOrder(new Order($this->getOrderId()));
		} else {
			$this->setOrder(null);
		}
	}

	public function addEvent($id = null)
	{
		
		if($id != null)
		{
			$this->setOrderId($id);
		}
		$this->getOrderInfo();

		$this->setTitle($this->order->order_customer);
		$this->setColor('#FF1493');
		$this->setStart($this->order->order_date.' '.$this->order->order_time);
		$latertime = strtotime($this->getStart()) + 60*60;
		$this->setEnd(date('Y/m/d H:i:s', $latertime));

		$this->db->insert('events',[
			'title'=>$this->getTitle(),
			'color'=>$this->getColor(),
			'start'=>$this->getStart(),
			'end'=>$this->getEnd(),
			'order_id'=>$this->getOrderId()
			]);
		$res = $this->db->getResult();
		if(!res)
		{
			echo 'There was an error inserting the event into the calendar!';
		}
		
	}

	public function addCustomEvent()
	{

		// Post details.
		$this->setTitle($_POST['title']);
		$this->setColor($_POST['color']);
		$this->setStart($_POST['start']);
		$this->setEnd($_POST['end']);
		$this->setOrderId('');

		$this->db->insert('events',['title'=>$this->title,
						'color'=>$this->color,
						'start'=>$this->start,
						'order_id'=>$this->order_id,
						'end'=>$this->end]);

		$res = $this->db->getResult();

	}

	public function editEvent()
	{

	}

	public function deleteEvent()
	{

	}

}

?>