<?php
  class ContainerController {
    public function masterlist() {

      // Pagination
      if(isset($_GET['pn'])){
        $pagenum = $_GET['pn'];
        $page_rows = 100;
        $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
        // Grab the container information with the limit.
        $container = Container::fetchContainers('',$limit);
      } else {
        // Grab a list of the containers.
        $container = Container::fetchContainers();
      }

      // Grab the number of containers in DB.
      $row = Container::countContainers();

      // Require the view.
      require_once('views/containers/masterlist.php');
    }

    public function error() {
      require_once('views/pages/error.php');
    }
  }
?>