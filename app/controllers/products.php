<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * @class Products
 */
class Products extends Controller
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
			$products = $this->model('Product');

			$pagenum = 1;

			if(isset($_GET['pn'])){
				$pagenum = $_GET['pn'];
			} 

			$page_rows = 100;
			$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
			// Grab the container information with the limit.
			$prodList = $products->getProducts('',$limit);

			$row = $products->countProducts();

			$this->view('products/masterlist', ['prodList'=>$prodList, 'row'=>$row]);
		}

	}

	public function edit($id)
	{
		// create product object & get product details (constructor)
		$product = new Product($id);

		// create the view
		$this->view('products/edit',['product'=>$product]);
	}

	public function update()
	{
		// create product object
		$product = new Product($_POST['productid']);

		// set attributes
		$product->setModName($_POST['frmpname']);
		$product->setModShortName($_POST['frmmsn']);
		$product->setModCost($_POST['frmpscost']);
		$product->setMonthly($_POST['frmprcost']);
		$product->setItemType($_POST['frmptype']);
		$product->setRentalType($_POST['frmprtype']);

		// update function
		try {
			$product->update();
		} catch (Exception $e) {
			echo '<script> alert('.$e->getMessage().'); </script>';
		}
		
		// Send the user back to the masterlist upon success.
		header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/products').'?action=usuccess'); 
	}

	public function delete($id)
	{
		// Create quote object,
		$product = new Product($id);

		// Delete the quote.
		$product->delete();

		// Refer back to the masterlist.
		header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/products').'?action=dsuccess'); 
	}

	public function create()
	{
		if($_POST){
			$product = new Product();
			
			$product->setModName($_POST['frmpname']);
			$product->setModShortName($_POST['frmmsn']);
			$product->setModCost($_POST['frmpscost']);
			$product->setMonthly($_POST['frmprcost']);
			$product->setItemType($_POST['frmptype']);
			$product->setRentalType($_POST['frmprtype']);
	
			try {
				$product->create();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			
			if(!$e)
			{
				header('Location: '.Config::get('site/http').Config::get('site/httpurl').Config::get('site/products').'?action=csuccess');
			}
		} else {
			$this->view('products/create',[]);
		}
	}

	public function test() 
	{
		$product = new Product();
		$rentArray = $product->rentArray();
		
		Functions::dump($rentArray);
	}
	
}

?>