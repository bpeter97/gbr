<?php

class Home extends Controller
{

    public function index()
    {
        $loggedin = '';

        $this->checkSession();

        if(isset($_POST['username'])){
            $user = $this->model('User');
            $user->username = $_POST['username'];
            $user->password = $_POST['password'];
            $loggedin = $user->login();
        }

        if($loggedin){
            $chart = $this->model('Chart');
            $this->view('home/dashboard', ['chart'=>$chart]);
        }

        $this->checkLogin();
        $chart = $this->model('Chart');
        $this->view('home/dashboard', ['chart'=>$chart]);
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