<?php

    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);
    //Include DB Connection
    include(BASEURL.CFG.'/database.php');

    // New DB object.
    $db = new Database;
    $db->connect();

    if(isset($_POST['order_id'])){

        $oid = $_POST['order_id'];

        $where = 'order_id = '.$oid.' AND product_type = "container"';
        $db->select('product_orders','*','',$where);
        $container_response = $db->getResult();

        $where = 'order_id = '.$oid.' AND product_type = "modification"';
        $db->select('product_orders','*','',$where);
        $modification_response = $db->getResult();

        $where = 'order_id = '.$oid.' AND product_type = "accessory"';
        $db->select('product_orders','*','',$where);
        $accessory_response = $db->getResult();


        if($oid != ""){

            $response = '

            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <b>Order Details</b>
                </div>
                <div class="panel-body">

                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tcontainers">Containers</a></li>
                        <li><a data-toggle="tab" href="#tmodifications">Modifications</a></li>
                        <li><a data-toggle="tab" href="#taccessories">Accessories</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="tcontainers" class="tab-pane fade in active">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Container</th>
                                    <th>Quantity</th>
                                </tr>';

                                    if($container_response){
                                        // List out each and every purchase made by the customer.
                                        foreach($container_response as $con){
                                            
                                            $response .= '

                                            <tr>
                                                <td>' . $con["product_name"] . '</td>
                                                <td>' . $con["product_qty"] . '</td>
                                            </tr>

                                            ';
                                        }
                                    } else {

                                        $response .= '

                                            <tr>
                                                <td>N/A</td>
                                                <td>N/A</td>
                                            </tr>

                                        ';
                                    }
                                $response .='
                            </table>
                        </div>

                        <div id="tmodifications" class="tab-pane fade in">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Modification</th>
                                    <th>Quantity</th>
                                </tr>';

                                    if($modification_response){
                                        // List out each and every purchase made by the customer.
                                        foreach($modification_response as $mod){
                                            
                                            $response .= '

                                            <tr>
                                                <td>' . $mod["product_name"] . '</td>
                                                <td>' . $mod["product_qty"] . '</td>
                                            </tr>

                                            ';
                                        }
                                    } else {

                                        $response .= '

                                            <tr>
                                                <td>N/A</td>
                                                <td>N/A</td>
                                            </tr>

                                        ';
                                    }
                                $response .='
                            </table>
                        </div>
                        <div id="taccessories" class="tab-pane fade in">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Accessory</th>
                                    <th>Quantity</th>
                                </tr>';

                                    if($accessory_response){
                                        // List out each and every purchase made by the customer.
                                        foreach($accessory_response as $acc){
                                            
                                            $response .= '

                                            <tr>
                                                <td>' . $acc["product_name"] . '</td>
                                                <td>' . $acc["product_qty"] . '</td>
                                            </tr>

                                            ';
                                        }
                                    } else {

                                        $response .= '

                                            <tr>
                                                <td>N/A</td>
                                                <td>N/A</td>
                                            </tr>

                                        ';
                                    }
                                $response .='
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            ';
            return print_r($response);
        } else {

            $response = "There was no order ID found.";
            return print_r($response);

        }

    } else {
        $response = "Error with POST['order_id']";
        return print_r($response);
    }


?>
