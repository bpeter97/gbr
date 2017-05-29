<?php 

/**
 * @class Calendar
 * @description This is the calendar class.
 */
class Calendar {

	// Variable Declaration
	public $color; // This will be the color that is used in createEvent.
	public $res; // This will be used to get the results in a function.

	/**
	 * This is called by using object->createEvent.
	 * eventName = string (just a name of the event)
	 * startDateTime = format yyyy-mm-dd hh:mm:ss
	 * endDateTime = format yyyy-mm-dd hh:mm:ss
	 * type = string
	 */
	public function createEvent($eventName, $startDateTime, $endDateTime, $type, $db, $order_id){
		if($type == 'delivery'){
			$this->color = '#FF1493';
		}
		$db->insert('events',array('title'=>$eventName,'color'=>$this->color,'start'=>$startDateTime,'end'=>$endDateTime,'order_id'=>$order_id));
		$this->res = $db->getResult();
		if($this->res){
			return true;
		} else {
			return false;
		}
	}
}
?>