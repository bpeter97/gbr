<?php

/**
* 
*/
class Model
{
    
    protected $res;
    protected $db;

    public function __construct()
    {
    	$this->db = Database::getDBI();
    }

    protected function resetResDb()
    {
	$this->res = '';
	$this->db = '';
    }
}


?>