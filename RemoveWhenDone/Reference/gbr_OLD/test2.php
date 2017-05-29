<?php 

// Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);
    //Include DB Connection
    include(BASEURL.CFG.'/database.php');

    // New DB object.
    $db = new Database;
    $db->connect();


    // Perform some db queries, etc here
    $db->select('events','*','','');
    $event = $db->getResult();


  // Place this at the top of your file
  if (isset($_POST['id'])) {
    $newID = $_POST['id'];  // You need to sanitize this before using in a query

    $customer = $_POST['customer'];

    // Perform some db queries, etc here
    $db->select('orders','*','','order_customer = "Angelo Gilbert"');
    $objRes = $db->getResult();
    // Format a desired response (text, html, etc)
    $response = '

    '.$objRes[0]['job_address'].'


    ';

    // This will return your formatted response to the $.post() call in jQuery 
    return print_r($response);
  }
?>

<?php include(BASEURL.MODEL.'/header.php'); ?>

<script type='text/javascript'>
  $(document).ready(function() {
    $('#viewdetails').click(function() {
      $.post(location.href, { id: $(this).attr('id'), customer: 'Angelo Gilbert' }, function(response) {
        // Inserts your chosen response into the page in 'response-content' DIV
        $('#response-content').html(response); // Can also use .text(), .append(), etc
      });
    });
  });
</script>

<span id="1" class="myElement">1</span>
<span id="2" class="myElement"></span>
<button type="button" id="viewdetails" class="myElement" data-dismiss="modal">open</button>


<div id='response-content'></div>