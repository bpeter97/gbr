<?php
    $action = $data['action'];
    $con = $data['container'];
    $sizes = $data['sizes'];
    $orders = $data['orderList'];
    $url = Config::get('site/selfurl').'?action=update';
?>

<DOCTYPE html>

<html>
    <head>
        <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>
    </head>

    <body>
        
        <div id="wrapper">

            <?php include(Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'); ?>
            
            <?php
            if($con->getFlag() == 'Yes'){
                echo '

                    <script type="text/javascript">

                        $(document).ready(function(){
                            $("#alertModal").modal("show");
                        });

                    </script>

                ';
            }
            ?>

            <!-- Page Content -->
    <div id="page-content-wrapper">
        <form action="<?php echo $url; ?>" method="post">

        <?php

        if($action == "edit"){
            echo '
            
            <input class="form-control" type="hidden" name="frmcid" required="false" value="'.$con->getId().'">

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
                                            <input class="form-control" type="text" name="container_number" value="<?php if($action == "edit"){echo $con->getContainerNumber();} ?>">
                                            <p class="help-block">This is the GBR number (##-####).</p>
                                        </div>
                                        <label class="col-md-2" for="container_serial_number" control-label>Container Serial #</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="container_serial_number" value="<?php if($action == "edit"){echo $con->getContainerSerialNumber();} ?>">
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
                                                        echo '<option selected>'.$con->getContainerShelves().'</option>';
                                                        if($con->getContainerShelves() == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($con->getContainerShelves() == "No"){
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
                                                        echo '<option selected>'.$con->getContainerSigns().'</option>';
                                                        if($con->getContainerSigns() == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($con->getContainerSigns() == "No"){
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
                                                        echo '<option selected>'.$con->getContainerOnboxNumbers().'</option>';
                                                        if($con->getContainerOnboxNumbers() == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($con->getContainerOnboxNumbers() == "No"){
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
                                                        echo '<option selected>'.$con->getContainerPaint().'</option>';
                                                        if($con->getContainerPaint() == "Yes"){
                                                            echo '<option>No</option>';
                                                        } elseif ($con->getContainerPaint() == "No"){
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
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$con->getContainerSize().'</option>';
                                                        foreach($sizes as $size){
                                                            echo '<option value="'.$size['container_size'].'">'.$size['container_size'].'</option>';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        ';
                                                        foreach($sizes as $size){
                                                            echo '<option value="'.$size['container_size'].'">'.$size['container_size'].'</option>';
                                                        }
                                                        
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Select the size of the container.</p>
                                        </div>
                                        <label class="col-md-2" for="release_number" control-label>Release Number</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="release_number" value="<?php if($action == "edit"){echo $con->getReleaseNumber();} ?>">
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
                                                        echo '<option selected>'.$con->getRentalResale().'</option>';
                                                        if($con->getRentalResale() == "Rental"){
                                                            echo '<option>Resale</option>';
                                                        } elseif($con->getRentalResale() =="Resale") {
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
                                        <label class="col-md-2" for="is_rented" control-label>Is it rented?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="is_rented">
                                                <?php

                                                    if($con->getIsRented() == "TRUE"){
                                                        $con->setIsRented("Yes");
                                                        $is_rented_value = "TRUE";
                                                    } elseif ($con->getIsRented() == "FALSE"){
                                                        $con->setIsRented("No");
                                                        $is_rented_value = "FALSE";
                                                    }

                                                    if($action == "edit"){
                                                        echo '<option selected value="'.$is_rented_value.'">'.$con->getIsRented().'</option>';
                                                        if($con->getIsRented() == "Yes"){
                                                            echo '<option value="FALSE">No</option>';
                                                        } elseif($con->getIsRented() == "No") {
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
                                            <input class="form-control" type="text" name="container_address" value="<?php if($action == "edit"){echo $con->getContainerAddress();} ?>">
                                            <p class="help-block">This is the current address the container is located.</p>
                                        </div>
                                        <label class="col-md-2" for="flag" control-label>Flagged?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="flag">
                                                <?php
                                                    if($action == "edit"){
                                                        echo '<option selected>'.$con->getFlag().'</option>';
                                                        if($con->getFlag() == "Yes"){
                                                            echo '<option value="No">No</option>';
                                                        } elseif ($con->getFlag() == "No") {
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
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="flag_reason"><?php echo $con->getFlagReason(); ?></textarea>
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
            
                                        if(!empty($orders)){
                                            $purchase_count = 0;
                                            $total_amount = 0;
                                            // List out each and every purchase made by the customer.
                                            foreach($orders as $order){
                                                $purchase_count += 1;
                                                $total_amount += $order->getMonthlyTotal();
                                                echo '

                                               <tr>
                                                    <td>' . $purchase_count . '</td>
                                                    <td>Start Date</td>
                                                    <td>End Date</td>
                                                    <td>Total Time (Months)</td>
                                                    <td>' . $order->getCustomer() . '</td>
                                                    <td>'. $order->getMonthlyTotal() .'</td>
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
                                <p>The container (<?php echo $con->getContainerNumber(); ?>) has a current flag on his account!</p>
                                <p>Flag Reason: <?php echo $con->getFlagReason(); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

    <body>

<html>