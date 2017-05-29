<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);
    include(BASEURL.CLASSES.'/Calendar.php');

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

    include(BASEURL.CFG.'/database.php');
    $db = new Database;
    $db->connect();

    // Container array
    $con_array = array();
    // Pull DB info.
    $db->select('modifications','mod_short_name','','item_type = "container"');
    $conQuery = $db->getResult();
    // Push to the array.
    foreach($conQuery as $x){
        array_push($con_array,$x['mod_short_name']);
    }

    // Mod array
    $mod_array = array();
    // Pull DB info.
    $db->select('modifications','mod_short_name','','item_type = "modification"');
    $modQuery = $db->getResult();
    // Push to the array.
    foreach($modQuery as $x){
        array_push($mod_array,$x['mod_short_name']);
    }

    // Delivery array
    $del_array = array();
    // Pull DB info.
    $db->select('modifications','mod_short_name','','item_type = "delivery" OR item_type = "pickup"');
    $delQuery = $db->getResult();
     // Push to the array.
    foreach($delQuery as $x){
        array_push($del_array,$x['mod_short_name']);
    }
    
    // Accessory array
    $acc_array = array();
    // Pull DB info.
    $db->select('modifications','mod_short_name','','item_type = "rent_mod"');
    $accQuery = $db->getResult();
     // Push to the array.
    foreach($accQuery as $x){
        array_push($acc_array,$x['mod_short_name']);
    }

    // Container count.
    $con_count = 0;

    $taxrate = 0.00;

    if(isset(
        $_SESSION['quote_item'],
        $_SESSION['rent_array'],
        $_SESSION['cost_before_tax'],
        $_SESSION['monthly_total'],
        $_SESSION['deduction_total'])){
            $quote_items = $_SESSION['quote_item'];
            $deduction_total = $_SESSION['deduction_total'];
            $rent_array = $_SESSION['rent_array'];
            $cbt = $_SESSION['cost_before_tax'];
            $monthly_total = $_SESSION['monthly_total'];
    } else {
        echo 'There was an error recieving the order items. Please recreate the orders and try again.';
    }

    $db->sql("SELECT * FROM customers");
    $customer_results = $db->getResult();

    foreach ($customer_results as $customer) {
        if($customer['customer_name'] == $_SESSION['customer_name']) {
            $cphone = $customer['customer_phone'];
            $cfax = $customer['customer_fax'];
            $cemail = $customer['customer_email'];
        }
    }

    $db->sql("SELECT * FROM taxrates");
    $zipcode_list = $db->getResult();

    foreach($zipcode_list as $zip){
        if($zip['zipcode'] == $_SESSION['job_zipcode']){
            $taxrate = $zip['tax_rate'];
        }
    }

    $sales_tax = $_SESSION['cost_before_tax'] * $taxrate;
    $total_cost = $sales_tax + $deduction_total + $_SESSION['cost_before_tax'];

    // ----------- Need to create the order table. -----------
    if(isset($_GET['pqid'])){
        $orderid = $_GET['pqid'];
        $db->delete('product_orders','order_id='.$orderid);
    } else {
        $orderid = $_SESSION['new_order_id'];
    }
    // This will simply add each product that was in the shopping cart into the orders table.
    foreach ($_SESSION['quote_item'] as $item) {
        if(isset($_GET['pqid'])){
            $orderid = $_GET['pqid'];
        }
        $prodName = $item['mod_name'];
        $prodQty = $item['quantity'];
        $prodCost = $item['cost'];
        $prodMsn = $item['mod_short_name'];
        $prodType = "";

        if(in_array($prodMsn, $con_array)){
            $prodType = "container";
        }

        if (in_array($prodMsn, $mod_array)){  // is modification
            $prodType = "modification";
        }

        if (in_array($prodMsn, $acc_array)){ // is delivery
            $prodType = "accessory";
        } 
        
        if (in_array($prodMsn, $del_array)){ // is accessory
            $prodType = "delivery";
        }

        // }
        $db->insert('product_orders',array('order_id'=>$orderid,'product_name'=>$prodName,'product_qty'=>$prodQty,'product_cost'=>$prodCost,'product_msn'=>$prodMsn,'product_type'=>$prodType));
    }

    // This will update the total cost and sales tax on the quote at the end of this page.
    $db->update('orders',array('total_cost'=>$total_cost,'sales_tax'=>$sales_tax,'cost_before_tax'=>$cbt, 'monthly_total'=>$monthly_total), 'order_id='.$orderid);

    $datetime = $_SESSION['order_date'].' '.$_SESSION['order_time'];
    $latertime = strtotime($datetime) + 60*60;
    $endtime = date('Y/m/d H:i:s', $latertime);

    // This will create the event.
    $calendar = new Calendar;
    $eventResult = $calendar->createEvent($_SESSION['customer_name'],$datetime,$endtime,'delivery',$db, $orderid);

    include(BASEURL.CONTROLLERS.'/cartunset.php');

    header('Location: '.HTTP.HTTPURL.VIEW.'/orders.php');

?>
