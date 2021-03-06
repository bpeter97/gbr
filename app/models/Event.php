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
		
	public function getId() { return $this->id; }
	public function getTitle() { return $this->title; }
	public function getColor() { return $this->color; }
	public function getStart() { return $this->start; }
	public function getEnd() { return $this->end; }
	public function getOrderId() { return $this->order_id; }
	public function getOrder() { return $this->order; }

	public function setId($id) { $this->id = $id; }
	public function setTitle($title) { $this->title = $title; }
	public function setColor($color) { $this->color = $color; }
	public function setStart($datetime) { $this->start = $datetime; }
	public function setEnd($datetime) { $this->end = $datetime; }
	public function setOrderId($id) { $this->order_id = $id; }
	public function setOrder($obj) { $this->order = $obj; }	
	
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

	public function getDetailsFromOrderId($id)
	{
		$sql = 'SELECT * FROM events WHERE order_id = ?';
		$this->getDB()->query($sql, array($id));
		$res = $this->getDB()->single();

		$this->setId($res->id);
		$this->setTitle($res->title);
		$this->setColor($res->color);
		$this->setStart($res->start);
		$this->setEnd($res->end);
		$this->setOrderId($res->order_id);
	}

	public function getDetails($id)
	{
		
		$this->setId($id);
		$sql = 'SELECT * FROM events WHERE id = ?';
		$this->getDB()->query($sql, array($id));
		$res = $this->getDB()->single();

		$this->setTitle($res->title);
		$this->setColor($res->color);
		$this->setStart($res->start);
		$this->setEnd($res->end);
		$this->setOrderId($res->order_id);
		
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

		$this->getDB()->insert('events',[
			'title'=>$this->getTitle(),
			'color'=>$this->getColor(),
			'start'=>$this->getStart(),
			'end'=>$this->getEnd(),
			'order_id'=>$this->getOrderId()
			]);
	
		if($this->getDB()->lastId() == null)
		{
			throw new Exception('There was an error inserting the event into the calendar!');
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

		$this->getDB()->insert('events',['title'=>$this->title,
						'color'=>$this->color,
						'start'=>$this->start,
						'order_id'=>$this->order_id,
						'end'=>$this->end]);

		if($this->getDB()->lastId() == null)
		{
			echo 'There was an error inserting the event into the calendar!';
		}

	}

	public function editEvent($color, $title, $start, $end)
	{
		if($color !== null)
			$this->setColor($color);
		
		if($title !== null)
			$this->setTitle($title);

		if($start !== null)		
		$this->setStart($start);

		if($end !== null)
			$this->setEnd($end);

		$this->update();
	}

	public function deleteEvent($id)
	{
		
		$res = $this->getDB()->delete('events',['id'=>$id]);
		if(!$res)
		{
			throw new Exception("There was an issue deleting the event.");
		}
			
	}

	public function update()
	{
		$res = $this->getDB()->update('events', ['id'=>$this->getId()], 
			[
			'color'	=>	$this->getColor(), 
			'title'		=>	$this->getTitle(),
			'start'		=>	$this->getStart(),
			'end'		=>	$this->getEnd(),
			'order_id'	=>	$this->getOrderId()
			]);

		if(!$res)
		{
			throw new Exception("There has been an issue updating the event.");
		} else {
			return $res;
		}
	}

}

?>