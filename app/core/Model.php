<?php

/**
* 
*/
class Model
{
    protected function getDB()
    {
        // get database instance
        return Database::getDBI();
    }
}


?>