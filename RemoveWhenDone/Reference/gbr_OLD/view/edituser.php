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
            $url = '../controller/edituser.php?from=viewedituser&action=edit';
            
            if(isset($_GET['uid'])) {
                $uid = $_GET['uid'];
            }

            $db->sql('SELECT * FROM users WHERE userid = '.$uid);
            $res = $db->getResult();

            foreach($res as $u){
                $username = $u['username'];
                $firstname = $u['firstname'];
                $lastname = $u['lastname'];
                $phone = $u['phone'];
                $title = $u['title'];
                $user_type = $u['user_type'];
                $password = $u['password'];
            }

        }  elseif($action == "create") {
            // If action equals create, then set url to send to edit with create action.
            $url = '../controller/edituser.php?action=create';
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
                                <b>Create Customer</b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmuname" control-label>User Name:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmuname" required="true" value="<?php if($action == "edit"){echo $username;} ?>">
                                            <p class="help-block">This field is the user's username.</p>
                                        </div>
                                        <label class="col-md-2" for="frmutitle" control-label>Title:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmutitle" required="false" value="<?php if($action == "edit"){echo $title;} ?>">
                                            <p class="help-block">This field is the title of the user.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmufirstname" control-label>First Name:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmufirstname" value="<?php if($action == "edit"){echo $firstname;} ?>">
                                            <p class="help-block">This field is the user's first name.</p>
                                        </div>
                                        <label class="col-md-2" for="frmulastname" control-label>Last Name:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmulastname" value="<?php if($action == "edit"){echo $lastname;} ?>">
                                            <p class="help-block">This field is the user's last name.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmupassword" control-label>Password:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmupassword" value="<?php if($action == "edit"){echo $password;} ?>">
                                            <p class="help-block">This field is the user's password.</p>
                                        </div>
                                        <label class="col-md-2" for="frmutype" control-label>User Type:</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="frmutype">
                                                <?php 
                                                    if($action == "edit"){
                                                        echo '
                                                        <option selected>'.$user_type.'</option>
                                                        ';
                                                        if($user_type == 'admin'){
                                                            echo '
                                                            <option>employee</option>
                                                            ';
                                                        } else {
                                                            echo '
                                                            <option>admin</option>
                                                            ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <option selected>Select One</option>
                                                        <option>admin</option>
                                                        <option>employee</option>
                                                        ';
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">This field is the user's type.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmuphone" control-label>User Phone Number:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmuphone" value="<?php if($action == "edit"){echo $phone;} ?>">
                                            <p class="help-block">This field is the user's phone number.</p>
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
