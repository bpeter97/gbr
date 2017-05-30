<?php

/**
* 
*/
class Calendar extends Model
{

    public $events = array();
    
    function __construct()
    {

    }

    public function getEvents()
    {
        // Connects upon creation.
        $this->db = new Database();

        $this->db->sql('SELECT * FROM events');
        $this->res = $this->db->getResult();
        $this->db->disconnect();

        foreach ($this->res as $event) 
        {
            array_push($this->events, new Event($event['id']));
        }

        $this->resetResDb();
        return $this->events;
    }


}

?>