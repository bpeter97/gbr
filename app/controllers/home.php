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
			
			// Create a calendar and grab events.
			$calendar = $this->model('Calendar');
			$events = $calendar->getEvents();

			// Create the charts for the bottom of the page.
			$chart = $this->model('Chart');

			// Go to the dashboard.
			$this->view('home/dashboard', ['chart'=>$chart, 'events'=>$events]);
		}

		$this->checkLogin();
	}

	public function addCustomEvent()
	{
		$event = $this->model('Event');
		$event->addCustomEvent();

		$this->index();
	}

	public function logout()
	{
		$this->checkSession();
		
		session_destroy();

		$this->view('home/login', []);
	}

	public function getOrderInfo()
	{
		if(isset($_POST['order_id']))
		{
			$order = new Order($_POST['order_id']);
		}
		return ($order->products);
	}

}


?>