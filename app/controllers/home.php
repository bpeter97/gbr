<?php

class Home extends Controller
{

	public function index()
	{
		if(!isset($_SESSION['loggedin'])){
			if(isset($_POST['username'])){
				$user = $this->model('User');
				$user->setUsername($_POST['username']);
				$user->setPassword($_POST['password']);
				$loggedin = $user->login();
			}
		}
		
		if(isset($_SESSION['loggedin'])){
			
			// Create a calendar and grab events.
			$calendar = $this->model('Calendar');
			$events = $calendar->getEvents();

			// Create the charts for the bottom of the page.
			$chart = $this->model('Chart');
			
			$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
			// Need to come up with a better way to create this array.
			$con_list = [
			            "10' Containers",
			            "20' Containers",
			            "20' Combos",
			            "20' Full Offices",
			            "20' Double Door",
			            "20' Containers w/ Shelves",
			            "20' High Cube",
			            "22' DD/HC",
			            "22' High Cube",
			            "24' Containers",
			            "24' High Cube",
			            "40' Containers",
			            "40' Combos",
			            "40' Double Doors",
			            "40' Full Offices",
			            "40' High Cubes"
			            ];

			// Go to the dashboard.
			$this->view('home/dashboard', ['chart'=>$chart, 'events'=>$events, 'months'=>$months, 'con_list'=>$con_list]);
		}

		$this->checkLogin();
	}

	public function addCustomEvent()
	{
		$event = $this->model('Event');
		$event->addCustomEvent();

		$this->index();
	}

	public function modifyEvent()
	{
		
		$calendar = $this->model('Calendar');

		if (isset($_POST['delete']) && isset($_POST['id'])){

			$id = $_POST['id'];

			$calendar->deleteEvent($id);

		} elseif (isset($_POST['title']) && isset($_POST['color']) && isset($_POST['id'])){
			
			$id = $_POST['id'];
			$title = $_POST['title'];
			$color = $_POST['color'];

			$calendar->editEvent($id, $color, $title, null, null);

		} elseif (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])) {

			$id = $_POST['Event'][0];
			$start = $_POST['Event'][1];
			$end = $_POST['Event'][2];

			$calendar->editEvent($id, null, null, $start, $end);
		}

		$this->index();
	}

	public function logout()
	{	
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

	public function testpage()
	{
		$this->view('home/test',[]);
	}

}


?>