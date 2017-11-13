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
		$prod = new Product();
		$rentArray = $prod->rentArray();
		


		$this->view('quotes/view',['quote'=>$quote,
								 'type'=>$type, 
								 'customer_name'=>$customerName,
								 'job_name'=>$jobName,
								 'attn'=>$attn,
								 'customer_phone'=>$customerPhone,
								 'customer_fax'=>$customerFax,
								 'customer_email'=>$customerEmail,
								 'rentArray'=>$rentArray,
								 'products'=>$products]);
	}

	public function create($type)
	{
		
		if($this->checkLogin())
		{

			$customer = $this->model('Customer');
			$custList = $customer->getCustomers();

			if(isset($_GET['cust']))
			{
				$customer->getDetails($_GET['cust']);
			}

			$products = $this->model('Product');
			$rentArray = $products->rentArray();
			$pudArray = $products->pudArray();

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

			$this->view('quotes/create', ['custList'=>$custList, 
										  'customer'=>$customer, 
										  'shippingProducts'=>$shippingProducts, 
										  'containerProducts'=>$containerProducts, 
										  'modificationProducts'=>$modificationProducts, 
										  'quote_type'=>$type, 
										  'rentArray'=>$rentArray, 
										  'pudArray'=>$pudArray]);
			
		}

	}

	public function submitQuote()
	{
		// create a customer object to use in the quote.
		$c = new Customer($_POST['frmcustomername']);

		// Create the quote
		$quote = new Quote();
		$quote->setCustomer($c->getCustomerName());
		$quote->setCustomerId($c->getId());

		// Inser the quote & quoted products in the database.
		try {

			// create the quote in the database.
			$quote->createQuote();
			
			// create the products and insert them in the quoted products.
			$quote->insertQuotedProducts();

		} catch (Exception $e) {

			echo $e->getMessage();

		}
		
		// direct user to the QUOTES view page.
		// header('Location: '.Config::get('site/siteurl').Config::get('site/quotes'));
		if(!$e)
		{
			header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/quotes').'?action=usuccess');
		}
	}

	public function edit($id)
	{
		
		// create the quote object & fetch quote details.
		$quote = new Quote($id);

		// fetch the products that are linked with the quote.
		$quote->fetchQuoteProducts();

		// create the customer object from the quote's customer id.
		$customer = new Customer($quote->getCustomerId());

		// fetch the list of products based on the type of quote.
		$products = $this->model('Product');
		$rentArray = $products->rentArray();
		$pudArray = $products->pudArray();

		if($quote->getType() == 'rental' || $quote->getType() == 'Rental')
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

		// create the view.
		$this->view('quotes/edit',['customer'=>$customer,
									 'quote'=>$quote,
									 'quotedProducts'=>$quote->getQuoteProducts(),
									 'shippingProducts'=>$shippingProducts, 
									 'containerProducts'=>$containerProducts, 
									 'modificationProducts'=>$modificationProducts, 
									 'quoteType'=>$quote->getType(),
									 'rentArray'=>$rentArray,
									 'pudArray'=>$pudArray
									 ]);
	}
	
	public function update()
	{

		// Create the quote object
		$quote = new Quote($_POST['quoteid']);

		// Delete previous products related to old quote
		$quote->fetchQuoteProducts();
		$oldProducts = $quote->getQuoteProducts();

		foreach($oldProducts as $oProd)
		{
			// Delete the product from the product_orders table.
			$oProd->deleteRequestedProduct($quote->getId(), 'quote');
		}

		// Update the the quote
			// Set the quotes attributes
		$quote->setDate($_POST['frmquotedate']);
		$quote->setAttention($_POST['frmattn']);
		$quote->setJobName($_POST['frmjobname']);
		$quote->setJobAddress($_POST['frmjobaddress']);
		$quote->setJobCity($_POST['frmjobcity']);
		$quote->setJobZipcode($_POST['frmjobzipcode']);
		$quote->setTaxRate($_POST['frmtaxrate']);
		$quote->setTotalCost($_POST['cartTotalCost']);
		$quote->setMonthlyTotal($_POST['cartMonthlyTotal']);
		$quote->setDeliveryTotal($_POST['cartDeliveryTotal']);
		$quote->setSalesTax($_POST['cartTax']);
		$quote->setCostBeforeTax($_POST['cartBeforeTaxCost']);
		$quote->update();

		// Update the product_orders table with the new products.
		$quote->insertQuotedProducts();
		
		// Send user back to masterlist upon success.
		header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/quotes').'?action=usuccess');
	}

	public function delete($id)
	{
		// Create quote object,
		$quote = new Quote($id);

		// Delete the quote.
		$quote->delete();

		// Refer back to the masterlist.
		header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/quotes').'?action=dsuccess');
	}

	public function convert($id)
	{

		// create the quote object to supply the view
		$quote = new Quote($id);

		// fetch the products that are linked with the quote.
		$quote->fetchQuoteProducts();

		// create the customer object from the quote's customer id.
		$customer = new Customer($quote->getCustomerId());

		// get the list of customers in case of customer change.
		$custList = $customer->getCustomers();

		// create the product to get the list of rental products and pickup/delivery products.
		$products = new Product();
		$rentArray = $products->rentArray();
		$pudArray = $products->pudArray();

		// Get the product lists
		if($quote->getType() == 'rental' || $quote->getType() == 'Rental')
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

		// Move to order form (including products)
		// fill in rest of required fields (date, time, etc)
		// ensure there is a hidden field posting data that it is coming form a quote
			// if it is coming from quote, set status to quote as closed quote and create the order as normal.
		$this->view('orders/create',['quote'=>$quote,
									 'quotedProducts'=>$quote->getQuoteProducts(),
									 'quoteType'=>$quote->getType(),
									 'customer'=>$customer,
									 'custList'=>$custList,
									 'shippingProducts'=>$shippingProducts, 
									 'containerProducts'=>$containerProducts, 
									 'modificationProducts'=>$modificationProducts,
									 'rentArray'=>$rentArray, 
									 'pudArray'=>$pudArray
									 ]);


	}

	public function hide($id)
	{
		// create the quote.
		$quote = new Quote($id);

		// Set hidden to 1 to hide it.
		$quote->setHidden(1);

		// Update the quote.
		$quote->update();

		// Refer back to the masterlist.
		header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/quotes').'?action=hsuccess');
	}

}

?>