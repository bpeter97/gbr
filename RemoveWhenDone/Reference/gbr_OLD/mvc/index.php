<?php

  require_once('assets/database.php');
  $db = new Database;

  if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action     = $_GET['action'];
  } else {
    $controller = 'pages';
    $action     = 'home';
  }

  if (isset($_GET['pn'])){
    $pagenum = $_GET['pn'];
  }

  require_once('views/layout.php');
  
?>