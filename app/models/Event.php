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
    public $order_info;

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

    public function addEvent()
    {

    }

    public function editEvent()
    {

    }

    public function deleteEvent()
    {

    }

}

?>