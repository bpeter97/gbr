<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include DB Connection
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

    if(isset($_GET['action'])) {
        $action = $_GET['action'];
    }

    $db = new Database;
    $db->connect();

    $quote_count = 0;
    $purchase_count = 0;
    $total_amount = 0;

    // Check to see if the customer ID was sent, else return to customers page.
    if(isset($_GET['id'])) {
        $container_ID = $_GET['id'];
        if(isset($_GET['of']) ){
            $of = $_GET['of'];
        }
    } else {
        header('location: '.HTTP.HTTPURL.VIEW.'/mastercontainers.php');
    }

    // Find out what action to take.
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        if($action == "edit"){
            // If action equals edit, then set url to send to edit with edit action.
            $url = '../controller/editcontainer.php?of='.$of.'&from=viewcontainerinfo&action=edit';
            
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            // Select customer information from database based on customer ID.
            $db->sql("SELECT * FROM containers WHERE container_ID=$container_ID");
            $container = $db->getResult();

            // Assign variables to results.
            foreach($container as $con){

                $release_number = $con['release_number'];
                $container_size = $con['container_size'];
                $container_serial_number = $con['container_serial_number'];
                $container_number = $con['container_number'];
                $container_shelves = $con['container_shelves'];
                $container_paint = $con['container_paint'];
                $container_onbox_numbers = $con['container_onbox_numbers'];
                $container_signs = $con['container_signs'];
                $rental_resale = $con['rental_resale'];
                $is_rented = $con['is_rented'];
                $container_address = $con['container_address'];
                $latitude = $con['latitude'];
                $logiitude = $con['longitude'];
                $type = $con['type'];
                $flag = $con['flag'];
                $flag_reason = $con['flag_reason'];
            }

        }  elseif($action == "create") {
            // If action equals create, then set url to send to edit with create action.
            $url = '../controller/editcontainer.php?of='.$_GET['of'].'&action=create';
        }
    }



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.MODEL.'/header.php'); ?>

    <?php
        if($flag == 'Yes'){
            echo '

                <script type="text/javascript">

                    $(document).ready(function(){
                        $("#alertModal").modal("show");
                    });

                </script>

            ';
        }
    ?>
    

</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.INCLUDES.'/fixednavbar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <form action="<?php echo $url; ?>" method="post">

        <?php

        if($action == "edit"){
            echo '
            
            <input class="form-control" type="hidden" name="frmcid" required="false" value="'.$container_ID.'">

            ';
        }

        ?>
        
            <div class="container-fluid" id="webbg">

                    <!-- End of 1st Row. -->
                    <!-- 2nd Row. -->
                <div class="row">
                    
                    <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Edit Container</b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="container_number" control-label>Container Number</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="container_number" value="<?php if($action == "edit"){echo $container_number;} ?>">
                                            <p class="help-block">This is the GBR number (##-####).</p>
                                        </div>
                                        <label class="col-md-2" for="container_serial_number" control-label>Container Serial #</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="container_serial_number" value="<?php if($action == "edit"){echo $container_serial_number;} ?>">
                                            <p class="help-block">This is the serial number that is on the actual container.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="container_shelves" control-label>Shelves?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="container_shelves">
                                                <?php
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$container_shelves.'</option>';
                                                        if($container_shelves == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($container_shelves == "No"){
                                                            echo '<option>Yes</option>';
                                                        } else {
                                                            echo '
                                                                <option selected>Select One</option>
                                                                <option>Yes</option>
                                                                <option>No</option>
                                                                ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option>Yes</option>
                                                        <option>No</option>
                                                        ';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Does the container have shelves?</p>
                                        </div>
                                        <label class="col-md-2" for="container_signs" control-label>Signs?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="container_signs">
                                                <?php
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$container_signs.'</option>';
                                                        if($container_signs == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($container_signs == "No"){
                                                            echo '<option>Yes</option>';
                                                        } else {
                                                            echo '
                                                                <option selected>Select One</option>
                                                                <option>Yes</option>
                                                                <option>No</option>
                                                                ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option>Yes</option>
                                                        <option>No</option>
                                                        ';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Does the container have signs?</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="container_onbox_numbers" control-label>GBR Numbers?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="container_onbox_numbers">
                                                <?php
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$container_onbox_numbers.'</option>';
                                                        if($container_onbox_numbers == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($container_onbox_numbers == "No"){
                                                            echo '<option>Yes</option>';
                                                        } else {
                                                            echo '
                                                                <option selected>Select One</option>
                                                                <option>Yes</option>
                                                                <option>No</option>
                                                                ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option>Yes</option>
                                                        <option>No</option>
                                                        ';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Does the container have GBR numbers on it?</p>
                                        </div>
                                        <label class="col-md-2" for="container_paint" control-label>Painted?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="container_paint">
                                                <?php
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$container_paint.'</option>';
                                                        if($container_paint == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($container_paint == "No"){
                                                            echo '<option>Yes</option>';
                                                        } else {
                                                            echo '
                                                                <option selected>Select One</option>
                                                                <option>Yes</option>
                                                                <option>No</option>
                                                                ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option>Yes</option>
                                                        <option>No</option>
                                                        ';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Is the container painted green?</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="container_size" control-label>Size</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="container_size">
                                                <?php
                                                    $db->sql('SELECT DISTINCT container_size FROM containers');
                                                    $res = $db->getResult();                            

                                                    if($action == "edit"){
                                                        echo '<option selected>'.$container_size.'</option>';
                                                        foreach($res as $con){
                                                            echo '<option value="'.$con['container_size'].'">'.$con['container_size'].'</option>';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        ';
                                                        foreach($res as $con){
                                                            echo '<option value="'.$con['container_size'].'">'.$con['container_size'].'</option>';
                                                        }
                                                        
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Select the size of the container.</p>
                                        </div>
                                        <label class="col-md-2" for="release_number" control-label>Release Number</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="release_number" value="<?php if($action == "edit"){echo $release_number;} ?>">
                                            <p class="help-block">This is the release number for the container.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="rental_resale" control-label>Rental or Resale?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="rental_resale">
                                                <?php
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$rental_resale.'</option>';
                                                        if($rental_resale == "Rental"){
                                                            echo '<option>Resale</option>';
                                                        } elseif($rental_resale =="Resale") {
                                                            echo '<option>Rental</option>';
                                                        } else {
                                                            echo '
                                                                <option selected>Select One</option>
                                                                <option>Rental</option>
                                                                <option>Resale</option>
                                                                ';
                                                        } 
                                                    } else {
                                                        echo '<option selected>Select One</option>';
                                                        echo '<option>Rental</option>';
                                                        echo '<option>Resale</option>';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Select whether or not the container is a rental or resale container.</p>
                                        </div>
                                        <label class="col-md-2" for="release_number" control-label>Is it rented?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="is_rented">
                                                <?php

                                                    if($is_rented == "TRUE"){
                                                        $is_rented = "Yes";
                                                        $is_rented_value = "TRUE";
                                                    } elseif ($is_rented == "FALSE"){
                                                        $is_rented = "No";
                                                        $is_rented_value = "FALSE";
                                                    }

                                                    if($action == "edit"){
                                                        echo '<option selected value="'.$is_rented_value.'">'.$is_rented.'</option>';
                                                        if($is_rented == "Yes"){
                                                            echo '<option value="FALSE">No</option>';
                                                        } elseif($is_rented == "No") {
                                                            echo '<option value="TRUE">Yes</option>';   
                                                        } else {
                                                            echo '<option selected>Select One</option>';
                                                            echo '<option value="TRUE">Yes</option>';
                                                            echo '<option value="FALSE">No</option>';
                                                        }
                                                    } else {
                                                        echo '<option selected>Select One</option>';
                                                        echo '<option value="TRUE">Yes</option>';
                                                        echo '<option value="FALSE">No</option>';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Is the container currently rented?</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="container_address" control-label>Current Address:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="container_address" value="<?php if($action == "edit"){echo $container_address;} ?>">
                                            <p class="help-block">This is the current address the container is located.</p>
                                        </div>
                                        <label class="col-md-2" for="flag" control-label>Flagged?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="flag">
                                                <?php
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$flag.'</option>';
                                                        if($flag == "Yes"){
                                                            echo '<option value="No">No</option>';
                                                        } elseif ($flag == "No") {
                                                            echo '<option value="Yes">Yes</option>';
                                                        } else {
                                                            echo '<option selected>Select One</option>';
                                                            echo '<option>Yes</option>';
                                                            echo '<option>No</option>';
                                                        }
                                                    } else {
                                                        echo '<option selected>Select One</option>';
                                                        echo '<option>Yes</option>';
                                                        echo '<option>No</option>';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Is the container currently flagged?</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="flag_reason" control-label>Reason of Flag:</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="flag_reason"><?php echo $flag_reason; ?></textarea>
                                            <p class="help-block">This field tells us why the customer is flagged or not.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-default form-button">Submit</button>
                                    <button type="button" onclick="history.go(-1);" class="btn btn-default form-button" style="margin-top: 7px;">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->

                <!-- 3rd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Rental History</b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-hover">
                                            <tr>
                                                <th>#</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Total Time</th>
                                                <th>Customer</th>
                                                <th>Amount Earned</th>
                                            </tr>
                                        <?php

                                        // Grabbing information for the purchase history.
                                        $db->sql("SELECT * FROM orders WHERE container='$container_ID'");
                                        $purchase_response = $db->getResult();

                                        if($purchase_response){
                                            // List out each and every purchase made by the customer.
                                            foreach($purchase_response as $order){
                                                $purchase_count += 1;
                                                $total_amount += $order['monthly_total'];
                                                echo '

                                               <tr>
                                                    <td>' . $purchase_count . '</td>
                                                    <td>Start Date</td>
                                                    <td>End Date</td>
                                                    <td>Total Time (Months)</td>
                                                    <td>' . $order["order_customer"] . '</td>
                                                    <td>'. $order['monthly_total'] .'</td>
                                                </tr>

                                                ';
                                            }
                                            echo "
                                                <tr>
                                                    <td><strong>Total:</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>" . $total_amount . "</strong></td>
                                                    <td></td>
                                                </tr>
                                            ";
                                        } else {

                                            echo "

                                                <tr>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                </tr>

                                            ";
                                        }
                                        ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 3rd Row. -->
           
                <!-- If the customer is flagged this modal will appear. -->
                <div class="modal fade" id="alertModal" role="dialog">                
                    <div class="modal-dialog"> 
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">!!! ALERT !!!</h4>
                            </div>
                            <div class="modal-body">
                                <p>The container (<?php echo $container_number; ?>) has a current flag on his account!</p>
                                <p>Flag Reason: <?php echo $flag_reason; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>

            </form>    
        </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
