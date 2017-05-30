<?php

// Still Used
if(!defined('DBHOST')){
	define("DBHOST",		"localhost");
}
if(!defined('DBUSER')){
	define("DBUSER",		"root");
}
if(!defined('DBPASS')){
	define("DBPASS",		"");
}
if(!defined('DBNAME')){
	define("DBNAME",		"gbr");
}
if(!defined('BASEURL')){
	define("BASEURL",		$_SERVER['DOCUMENT_ROOT']);
}
if(!defined('HTTPURL')){
	define("HTTPURL",		$_SERVER['HTTP_HOST']);
}
if(!defined('SELFURL')){
	define("SELFURL",		$_SERVER['REQUEST_URI']);
}
if(!defined('HTTP')){
	define("HTTP",			"http://"); // Add www. after the // in HTTP if www. is required!
}
if(!defined('IMG')){
	define("IMG",			"/img");
}
if(!defined('CSS')){
	define("CSS",			"/css");
}
if(!defined('JS')){
	define("JS",			"/js");
}

// MVC Architecture Folders
if(!defined('PUB')){
	define("PUB", 			'/public');
}
if(!defined('APP')){
	define('APP', 			'/app');
}
if(!defined('ASSETS')){
	define('ASSETS', 		'/assets');
}
if(!defined('CORE')){
	define('CORE', 			'/core');
}
if(!defined('CONTROLLERS')){
	define("CONTROLLERS",	"/controller");
}

// View Folders
if(!defined('CONTAINERS')){
    define('CONTAINERS',    '/containers');
}
if(!defined('CUSTOMERS')){
    define('CUSTOMERS',     '/customers');
}
if(!defined('QUOTES')){
    define('QUOTES',        '/quotes');
}
if(!defined('ORDERS')){
    define('ORDERS',        '/orders');
}
if(!defined('ORDERS')){
    define('ORDERS',        '/orders');
}
if(!defined('PRODUCTS')){
    define('PRODUCTS',        '/products');
}
if(!defined('CALENDAR')){
    define('CALENDAR',        '/calendar');
}

// Not sure if still used.
if(!defined('CFG')){
	define("CFG",			"/cfg");
}
if(!defined('CLASSES')){
	define("CLASSES",		"/classes");
}
if(!defined('VIEW')){
	define("VIEW",			"/view");
}
if(!defined('MAP')){
	define("MAP",			"/map");
}
if(!defined('MODEL')){
	define("MODEL",			"/model");
}
if(!defined('INCLUDES')){
	define("INCLUDES",		"/includes");
}


?>
