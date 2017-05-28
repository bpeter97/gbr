<?php

// ************CONTROLLER************

/**
 * This is the controller page that tells which 
 * views to pull and what models to interact with.
 *
 * Controllers are named as plural where models are singular.
 *
 * @class Containers
 */
class Containers extends Controller
{
    // Index page that references the masterlist page.
    public function index()
    {
        $this->masterlist();
    }

    // This will be the page that shows all of the current containers.
    public function masterlist()
    {
        $this->checkSession();
        $this->checkLogin();

        $container = $this->model('Container');

        $pagenum = 1;

        if(isset($_GET['pn'])){
            $pagenum = $_GET['pn'];
        } 

        $page_rows = 100;
        $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
        // Grab the container information with the limit.
        $conList = $container->fetchContainers('',$limit);

        $row = $container->countContainers();

        $this->view('containers/masterlist', ['conList'=>$conList, 'row'=>$row]);
    }

    public function rentalcontainers()
    {
        $this->checkSession();
        $this->checkLogin();

        $container = $this->model('Container');

        $pagenum = 1;

        if(isset($_GET['pn'])){
            $pagenum = $_GET['pn'];
        } 

        $page_rows = 100;
        $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
        // Grab the container information with the limit.
        $conList = $container->fetchContainers('rental_resale = "Rental"',$limit);

        $row = $container->countContainers('rental_resale = "Rental"');

        $this->view('containers/rentalcontainers', ['conList'=>$conList, 'row'=>$row]);
    }

    
}

?>