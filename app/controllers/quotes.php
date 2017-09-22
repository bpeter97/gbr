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

	public function viewinfo($id)
	{
		$this->checkSession();

		$quote = new Quote($id);
		$quote->fetchQuoteProducts();

		$customer = new Customer($quote->getCustomerId());

		$type = $quote->getType();
		$customerName = $quote->getCustomer();
		$attn = $quote->getAttention();
		$jobName = $quote->getJobName();
		$customerPhone = $customer->getCustomerPhone();
		$customerFax = $customer->getCustomerFax();
		$customerEmail = $customer->getCustomerEmail();

		$products = $quote->getQuoteProducts();


		$this->view('quotes/view',['quote'=>$quote,
								 'type'=>$type, 
								 'customer_name'=>$customerName,
								 'job_name'=>$jobName,
								 'attn'=>$attn,
								 'customer_phone'=>$customerPhone,
								 'customer_fax'=>$customerFax,
								 'customer_email'=>$customerEmail,
								 'products'=>$products]);
	}

	public function create($type, $action = null)
	{
		
		if($this->checkLogin())
		{
			if($action == 'create')
			{
				$c = new Customer($_POST['frmcustomername']);

				// Create the order
				$quote = $this->model('Quote');
				$quote->setCustomer($c->getCustomerName());
				$quote->setCustomerId($c->getId());
				$quote->createQuote();
				// create the products and insert them in the ordered products.
				$quote->insertQuotedProducts();
				// direct user to the orders view page. THIS IS NOT WORKING, NEED TO USE REDIRECT.
				$this->masterlist();
			} 

			$customer = $this->model('Customer');
			$custList = $customer->getCustomers();

			if(isset($_GET['cust']))
			{
				$customer->getDetails($_GET['cust']);
			}

			$products = $this->model('Product');

			if($type == 'rental')
			{
				$shipSQL = "item_type = 'pickup' OR item_type = 'delivery'";
				$containerSQL = "item_type = 'container' AND monthly <> 0";
				$modSQL = "monthly <> 0 AND item_type <> 'container' AND item_type <> 'pickup' AND item_type <> 'delivery'";
			} 
			else
			{
				$shipSQL = "item_type = 'pickup' OR item_type = 'delivery'";
				$containerSQL = "item_type = 'container' AND monthly = 0";
				$modSQL = "monthly = 0 AND item_type <> 'container' AND item_type <> 'pickup' AND item_type <> 'delivery'";
			}
				
			$shippingProducts = $products->getProducts($shipSQL);
			$containerProducts = $products->getProducts($containerSQL);
			$modificationProducts = $products->getProducts($modSQL);

			$this->view('quotes/create', ['custList'=>$custList, 'customer'=>$customer, 'shippingProducts'=>$shippingProducts, 'containerProducts'=>$containerProducts, 'modificationProducts'=>$modificationProducts, 'quote_type'=>$type]);
			
		}

	}
	
}

?>