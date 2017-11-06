<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * Controllers are named as plural where models are singular.
 *
 * @class Search
 */
class Search extends Controller
{
	// Index page that references the masterlist page.
	public function index()
	{
        $containers = null;
        $customers = null;
        $quotes = null;
        $orders = null;
        $products = null;
        $selected = null;

        try {
            switch ($_POST['category']) {
                case 'containers':
                        
                        $container = new Container();
                        $containers = $container->search();
                        $selected = "containers";

                    break;
    
                case 'customers':
                        
                        $customer = new Customer();
                        $customers = $customer->search();
                        $selected = "customers";

                    break;

                case 'quotes':

                        $quote = new Quote();
                        $quotes = $quote->search();
                        $selected = "quotes";

                    break;

                case 'orders':
                
                        $order = new Order();
                        $orders = $order->search();
                        $selected = "orders";

                     break;
                    
                case 'products':

                        $product = new Product();
                        $products = $product->search();
                        $selected = "products";
                
                    break;

                default:
                    throw new Exception('There was no cateogory selected!');
            }
        } catch (Exception $e) {
            // Send to the home page with a search error alert.
            header('Location: '.Config::get('site/http').Config::get('site/httpurl').'?action=searchError');
        }
        
        $this->view('search/results',['containers'  =>  $containers, 
                                      'customers'   =>  $customers, 
                                      'quotes'      =>  $quotes, 
                                      'orders'      =>  $orders, 
                                      'products'    =>  $products,
                                      'selected'    =>  $selected
                                      ]);
	}

}

?>