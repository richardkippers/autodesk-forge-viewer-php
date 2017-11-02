<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	error_reporting(E_ALL);
	
	require __DIR__.'/vendor/autoload.php';	
	
	use PHPRouter\RouteCollection;
	use PHPRouter\Router;
	use PHPRouter\Route;
	
	
	foreach(parse_ini_file('environment.ini') as $key=>$value){
		define($key, $value);
	}

	/**
	 * Load controllers
	 **/
	 
	require_once('controllers/Controller.php');
	require_once('controllers/Home.php');
	require_once('controllers/View.php');
	require_once('controllers/Process.php');
		
	/**
	 * Load classess
	 **/
	 
	require_once('Class/Autodesk/oAuth.php');
	require_once('Class/Autodesk/Buckets.php');
	require_once('Class/Autodesk/ModelDerivative.php');
	require_once('Class/Autodesk/File.php');
	require_once('Class/Model.php');
	
	
	/**
     * Routing
     **/  
	
    $collection = new RouteCollection();
    
	$collection->attachRoute(new Route('/', array(
		'_controller' 	=> 'Home::index',
		'methods' 		=> 'GET'
	)));
	
	$collection->attachRoute(new Route('/process/upload', array(
		'_controller' 	=> 'Process::Upload',
		'methods' 		=> 'POST'
	)));
	
	$collection->attachRoute(new Route('/process/designDataToSvf', array(
		'_controller' 	=> 'Process::designDataToSvf',
		'methods' 		=> 'GET'
	)));
	
	$collection->attachRoute(new Route('/process/checkDesignToSvfState', array(
		'_controller' 	=> 'Process::checkDesignToSvfState',
		'methods' 		=> 'GET'
	)));
	
	$collection->attachRoute(new Route('/view', array(
		'_controller' 	=> 'View::index',
		'methods' 		=> 'GET'
	)));
	
	$router = new Router($collection);

	$router->setBasePath('/');
	$route = $router->matchCurrentRequest();
