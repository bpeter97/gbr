 <?php

 // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include database connections
    include(BASEURL.CFG.'/database.php');

    $db = new Database();
    $db->connect();

    $db->sql('SELECT DISTINCT container_size, container_size_code FROM containers');
    $res = $db->getResult();
    foreach($res as $con){
    	echo $con['container_size'];
    	echo ' - ';
    	echo $con['container_size_code'];
    	echo '</br>';
    }

 ?>



                    $latitude = $con['latitude'];
                    $logiitude = $con['logiitude'];
                    $type = $con['type'];
                    $flag = $con['flagged'];
                    $flag_reason = $con['flag_reason'];