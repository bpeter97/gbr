<?php
    
    // NEED TO SET ALLOW_URL_FOPEN TO 1 IN PHP SETTINGS TO AUTO PULL LNG & LAT!
    ini_set('allow_url_fopen', 'on');

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = dirname($_SERVER["DOCUMENT_ROOT"]).'/app/core/Settings.php';

    //Variable Constants
    include($cfgurl);

    include(dirname($_SERVER["DOCUMENT_ROOT"]).'/app/core/Database.php');
    include(dirname($_SERVER["DOCUMENT_ROOT"]).'/app/core/Config.php');

    function parseToXML($htmlStr) {
        $xmlStr=str_replace('<','&lt;',$htmlStr);
        $xmlStr=str_replace('>','&gt;',$xmlStr);
        $xmlStr=str_replace('"','&quot;',$xmlStr);
        $xmlStr=str_replace("'",'&#39;',$xmlStr);
        $xmlStr=str_replace("&",'&amp;',$xmlStr);
        return $xmlStr;
    }

    //Create new database object and connect.
    $db = Database::getDBI();

    // Select all the rows in the markers table
    $query = "SELECT * FROM containers WHERE container_address <> ''";
    $db->query($query);
    $coninfo = $db->results('arr');

    header("Content-type: text/xml");

    // Start XML file, echo parent node
    echo '<markers>';


    // Iterate through the rows, printing XML nodes for each
    foreach ($coninfo as $con){

      // Google maps is not working properly..
      // $con['latitude'] = getLat($con['container_address']);
      // $con['longitude'] = getLon($con['container_address']);
      
      // ADD TO XML DOCUMENT NODE
      echo '<marker ';
      echo 'name="' . parseToXML($con['container_number']) . '" ';
      echo 'address="' . parseToXML($con['container_address']) . '" ';
      echo 'lat="' . $con['latitude'] . '" ';
      echo 'lng="' . $con['longitude'] . '" ';
      echo 'type="' . $con['type'] . '" ';
      echo '/>';
    }

    // End XML file
    echo '</markers>';

    // Get latitude of containers
    function getLat($addy){
      $address = urlencode($addy);
      if(strlen($address)>0){
        $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address;
        $xml = simplexml_load_file($request_url);
        $status = $xml->status;
        if ($status=="OK") {
          $lat = $xml->result->geometry->location->lat;
        }
      }
      return $lat;
    }

    // Get longitiude of containers.
    function getLon($addy){
      $address = urlencode($addy);
      if(strlen($address)>0){
        $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address;
        $xml = simplexml_load_file($request_url);
        $status = $xml->status;
        if ($status=="OK") {
          $lon = $xml->result->geometry->location->lng;
        }
      }
      return $lon;
    }
?>
