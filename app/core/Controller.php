<?php 

/**
 * This class controls where the user will go depending on the url. 
 *
 * @class Controller
 */
class Controller 
{   
    
    public function __construct()
    {
        $this->checkSession();
    }
    
    // Create the model.
    public function model($model)
    {
        require_once('../app/models/' . $model . '.php');
        return new $model();
    }

    // Create the view!
    public function view($view, $data = [])
    {
        require_once('../app/views/' . $view . '.php');
    }

    public function checkSession()
    {
        if(session_id() == '' || !isset($_SESSION)) {
            session_start();
        }
    }

    public function checkLogin()
    {
        //Check if user is logged in.
        if(!isset($_SESSION['loggedin'])) {
            $this->view('home/login', []);
        } else {
            return true;
        }
    }
}

?>