<?php

class Home extends Controller
{

    public function index()
    {
        $this->checkSession();

        if(!isset($_SESSION['loggedin'])){
            if(isset($_POST['username'])){
                $user = $this->model('User');
                $user->username = $_POST['username'];
                $user->password = $_POST['password'];
                $loggedin = $user->login();
            }
        }
        
        if(isset($_SESSION['loggedin'])){
            $chart = $this->model('Chart');
            $this->view('home/dashboard', ['chart'=>$chart]);
        }

        $this->checkLogin();
    }

    public function logout()
    {
        $this->checkSession();
        
        session_destroy();

        $this->view('home/login', []);
    }

    public function getChartInfo()
    {
        
    }

}


?>