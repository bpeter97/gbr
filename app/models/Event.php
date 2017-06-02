<?php

/**
* 
*/
class Event extends Model
{
    
    public $id;
    public $title;
    public $color;
    public $start;
    public $end;
    public $order_id;
    public $order;

    function __construct($id = '')
    {
    
        if($id != null){
            $this->getDetails($id);
            if($this->order_id != '')
            {
                $this->getOrderInfo();
            }
        } else {
            $this->id = null;
        }
        
    }

    public function getDetails($id)
    {
        $this->id = $id;
        $this->db = new Database();
        $this->db->select('events','*','','id = ' . $this->id);
        $this->res = $this->db->getResult();
        $this->db->disconnect();

        $this->title = $this->res[0]['title'];
        $this->color = $this->res[0]['color'];
        $this->start = $this->res[0]['start'];
        $this->end = $this->res[0]['end'];
        $this->order_id = $this->res[0]['order_id'];

        $this->resetResDb();
    }

    public function getOrderInfo()
    {   
        $this->order = new Order($this->order_id);
    }

    public function addEvent($id = null)
    {
        $this->db = new Database();

        if($id != null)
        {
            $this->order_id = $id;
        }
        $this->getOrderInfo();

        $this->title = $this->order->order_customer;
        $this->color = '#FF1493';
        $this->start = $this->order->order_date.' '.$this->order->order_time;
        $latertime = strtotime($this->start) + 60*60;
        $this->end = date('Y/m/d H:i:s', $latertime);

        $this->db->insert('events',array(
            'title'=>$this->title,
            'color'=>$this->color,
            'start'=>$this->start,
            'end'=>$this->end,
            'order_id'=>$this->order_id
            ));
        $this->res = $this->db->getResult();
        if(!$this->res)
        {
            echo 'There was an error inserting the event into the calendar!';
        }

        $this->db->disconnect();
        $this->resetResDb();
    }

    public function editEvent()
    {

    }

    public function deleteEvent()
    {

    }

}

?>