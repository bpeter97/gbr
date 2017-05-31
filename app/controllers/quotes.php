<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * @class Quotes
 */
class Quotes extends Controller
{
	// Index page that references the masterlist page.
	public function index()
	{
		$this->masterlist();
	}

	// This will be the page that shows all quotes.
	public function masterlist()
	{
		$this->checkSession();
		if($this->checkLogin())
		{
			$quote = $this->model('Quote');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$quoteList = $quote->getQuotes('',$limit);

			$row = $quote->countQuotes();

			$this->view('quotes/masterlist', ['quoteList'=>$quoteList, 'row'=>$row]);
		}
		
	}
	
}

?>