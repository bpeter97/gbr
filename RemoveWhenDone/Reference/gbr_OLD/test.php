<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);
    include(BASEURL.CFG.'/database.php');
   

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

    $db = new Database;
    $db->connect();

    $var = '40PEXTINT';

    // Container array
    $con_array = array();
    // Pull DB info.
    $db->select('modifications','mod_short_name','','item_type = "container"');
    $conQuery = $db->getResult();
    // Push to the array.
    foreach($conQuery as $x){
        array_push($con_array,$x['mod_short_name']);
    }

    echo '<pre>';
    var_dump($con_array);
    echo '</pre>';
    echo '---------------------------------------------------------------------';


    // Mod array
    $mod_array = array();
    // Pull DB info.
    $db->select('modifications','mod_short_name','','item_type = "modification"');
    $modQuery = $db->getResult();
    // Push to the array.
    foreach($modQuery as $x){
        array_push($mod_array,$x['mod_short_name']);
    }

    echo '<pre>';
    var_dump($mod_array);
    echo '</pre>';
    echo '---------------------------------------------------------------------';

    // Delivery array
    $del_array = array();
    // Pull DB info.
    $db->select('modifications','mod_short_name','','item_type = "delivery" OR item_type = "pickup"');
    $delQuery = $db->getResult();
     // Push to the array.
    foreach($delQuery as $x){
        array_push($del_array,$x['mod_short_name']);
    }

    echo '<pre>';
    var_dump($del_array);
    echo '</pre>';
    echo '---------------------------------------------------------------------';

    if(in_array($var, $con_array)){
        echo 'container';
    } elseif(in_array($var, $mod_array)){
        echo 'modification';
    } elseif(in_array($var, $del_array)){
        echo 'delivery';
    }

?>

