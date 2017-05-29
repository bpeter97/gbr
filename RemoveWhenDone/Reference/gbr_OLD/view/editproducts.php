<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Include database connections
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

    // Create DB and connect the DB.
    $db = new Database();
    $db->connect();

    // Find out what action to take.
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        if($action == "edit"){
            // If action equals edit, then set url to send to edit with edit action.
            $url = '../controller/editproducts.php?from=vieweditproducts&action=edit';
            
            if(isset($_GET['uid'])) {
                $uid = $_GET['uid'];
            }

            $db->sql('SELECT * FROM modifications WHERE mod_ID = '.$uid);
            $res = $db->getResult();

            foreach($res as $u){
                $mod_name = $u['mod_name'];
                $mod_cost = $u['mod_cost'];
                $mod_short_name = $u['mod_short_name'];
                $monthly = $u['monthly'];
                $item_type = $u['item_type'];
                $rental_type = $u['rental_type'];
            }

        }  elseif($action == "create") {
            // If action equals create, then set url to send to edit with create action.
            $url = '../controller/editproducts.php?action=create';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.MODEL.'/header.php'); ?>

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
            
            <input class="form-control" type="hidden" name="frmuid" required="false" value="'.$uid.'">

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
                                <b>Create Products</b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmpname" control-label>Product Name</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmpname" required="true" value="<?php if($action == "edit"){echo $mod_name;} ?>">
                                            <p class="help-block">This field is the product's name.</p>
                                        </div>
                                        <label class="col-md-2" for="frmmsn" control-label>Product Label:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmmsn" required="false" value="<?php if($action == "edit"){echo $mod_short_name;} ?>">
                                            <p class="help-block">This field is the label of the product for database management (You can create your own <strong>unique</strong> ones).</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmpscost" control-label>Sales Cost:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmpscost" value="<?php if($action == "edit"){echo $mod_cost;} ?>">
                                            <p class="help-block">This field is the sales cost of the product.</p>
                                        </div>
                                        <label class="col-md-2" for="frmprcost" control-label>Rental Cost</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmprcost" value="<?php if($action == "edit"){echo $monthly;} ?>">
                                            <p class="help-block">This field is the monthly cost of the product if it is rentable.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmptype" control-label>Item Type:</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="frmptype">
                                                <?php 
                                                    if($action == "edit"){
                                                        if($item_type == 'modification'){
                                                            echo '
                                                            <option value="'.$item_type.'" selected>Modification</option>
                                                            <option value="container">Container</option>
                                                            <option value="pickup">Pickup</option>
                                                            <option value="delivery">Delivery</option>
                                                            <option value="rent_mod">Rental Modification</option>
                                                            ';
                                                        } elseif($item_type == 'container') {
                                                            echo '
                                                            <option value="'.$item_type.'" selected>Container</option>
                                                            <option value="modification">Modification</option>
                                                            <option value="pickup">Pickup</option>
                                                            <option value="delivery">Delivery</option>
                                                            <option value="rent_mod">Rental Modification</option>
                                                            ';
                                                        } elseif($item_type == 'pickup') {
                                                            echo '
                                                            <option value="'.$item_type.'" selected>Pickup</option>
                                                            <option value="modification">Modification</option>
                                                            <option value="container">Container</option>
                                                            <option value="delivery">Delivery</option>
                                                            <option value="rent_mod">Rental Modification</option>
                                                            ';
                                                        } elseif($item_type == 'delivery') {
                                                            echo '
                                                            <option value="'.$item_type.'" selected>Delivery</option>
                                                            <option value="modification">Modification</option>
                                                            <option value="container">Container</option>
                                                            <option value="pickup">Pickup</option>
                                                            <option value="rent_mod">Rental Modification</option>
                                                            ';
                                                        } elseif($item_type == 'rent_mod') {
                                                            echo '
                                                            <option value="'.$item_type.'" selected>Rental Modification</option>
                                                            <option value="modification">Modification</option>
                                                            <option value="container">Container</option>
                                                            <option value="pickup">Pickup</option>
                                                            <option value="delivery">Delivery</option>
                                                            ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option value="modification">Modification</option>
                                                        <option value="container">Container</option>
                                                        <option value="pickup">Pickup</option>
                                                        <option value="delivery">Delivery</option>
                                                        <option value="rent_mod">Rental Modification</option>
                                                        ';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">This field is the type of product.</p>
                                        </div>
                                        <label class="col-md-2" for="frmprtype" control-label>Rental Status</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="frmprtype">
                                                <?php 
                                                    if($action == "edit"){
                                                        echo '
                                                        <option selected>'.$rental_type.'</option>
                                                        ';
                                                        if($user_type == 'Nonrental'){
                                                            echo '
                                                            <option>Rental</option>
                                                            ';
                                                        } else {
                                                            echo '
                                                            <option>Nonrental</option>
                                                            ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option>Nonrental</option>
                                                        <option>Rental</option>
                                                        ';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">This field is the current status if it's a rental or nonrental product.</p>
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

                <?php include(BASEURL.INCLUDES.'/copyright.php'); ?>
            </div>
        </form>
    </div>

    <?php include(BASEURL.INCLUDES.'/modals.php'); ?>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
