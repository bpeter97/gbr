<?php

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' 			=> 'localhost',
		'user' 			=> 'root',
		'pass' 			=> '',
		'dbname' 		=> 'gbr',
		'charset' 		=> 'UTF8'
	),
	'company' => array(
		'name' 			=>	'Green Box Rentals, Inc.',
		'address1' 		=>	'6988 Avenue 304',
		'address2'		=>	'Visalia, CA 93291',
		'phone'			=>	'559-733-5345',
		'fax'			=>	'559-651-4288'
	),
	'site' => array(
		'siteurl' 		=>	'http://'.$_SERVER['HTTP_HOST'],
		'baseurl' 		=>	$_SERVER['DOCUMENT_ROOT'],
		'httpurl' 		=>	$_SERVER['HTTP_HOST'],
		'selfurl' 		=>	$_SERVER['REQUEST_URI'],
		'http' 			=>	'http://',
		'DS' 			=>	DIRECTORY_SEPARATOR,
		'root' 			=>	dirname(dirname(__FILE__)),
		'datetime' 		=>	'm/d/Y H:i:s',
		'timezone' 		=>	-8,
		'assets' 		=>	'/assets',
		'resources' 	=>	array(
			'img' 		=>	'/img',
			'css' 		=>	'/css',
			'js' 		=>	'/js'
		),
		'quotes' 		=>	'/quotes',
		'customers'		=>	'/customers',
		'containers'	=>	'/containers',
		'orders' 		=>	'/orders',
		'products' 		=>	'/products',
		'calendar' 		=>	'/calendars',
		'users'			=>	'/users',
		'search'		=>	'/search',
		'pickups'		=>	'/pickups'
		
	),
	'remember' => array(
		'cookie_name' 	=> 'hash',
		'cookie_expiry' => 604800
  	),
	'session' => array(
		'user_session' 	=> 'user',
		'token_name' 	=> 'csrf_token'
	  ),
	'security' => array(
		'salt' 			=> 'YouWillWantToChangeMe'
	),
	'steam' => array(
		'apikey' 		=> 'YouWillWantToChangeMe',
		'redirecturl' 	=> 'localhost/user/profile'
	)
);
