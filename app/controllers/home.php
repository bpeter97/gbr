<?php

class Home extends Controller
{

    public function index()
    {
        $loggedin = '';

        $this->checkSession();

        if(isset($_POST['username'])){
            $user = new User();
            $user->username = $_POST['username'];
            $user->password = $_POST['password'];
            $loggedin = $user->login();
        }

        if($loggedin){
            $this->view('home/dashboard', []);
        }

        $this->checkLogin();
        $this->view('home/dashboard', []);
    }

    public function logout()
    {
        $this->checkSession();
        
        session_destroy();

        $this->view('home/login', []);
    }

}


?>