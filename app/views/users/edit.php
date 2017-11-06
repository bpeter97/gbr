<?php $user = $data['user']; ?>

<DOCTYPE html>

<html>
    <head>
        <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>
    </head>

    <body>

        <div id="wrapper">

            <?php include(Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'); ?>

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div class="container-fluid" id="webbg">

                    <!-- 2nd Row. -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <b>Edit Users</b>
                                </div>
                                <div class="panel-body">
                                    <!-- <form action="http://www.rebol.com/cgi-bin/test-cgi.cgi" id="orderForm" method="post"> -->
                                    <form action="<?php echo Config::get('site/siteurl').Config::get('site/users').'/update/'; ?>" id="orderForm" method="post">
                                    <input class="form-control" type="hidden" name="userId" value="<?= $user->getId(); ?>">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmuname" control-label>User Name:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmuname" required="true" value="<?= $user->getUsername(); ?>">
                                                <p class="help-block">This field is the user's username.</p>
                                            </div>
                                            <label class="col-md-2" for="frmutitle" control-label>Title:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmutitle" required="false" value="<?= $user->getTitle(); ?>">
                                                <p class="help-block">This field is the title of the user.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmufirstname" control-label>First Name:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmufirstname" value="<?= $user->getFirstName(); ?>">
                                                <p class="help-block">This field is the user's first name.</p>
                                            </div>
                                            <label class="col-md-2" for="frmulastname" control-label>Last Name:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmulastname" value="<?= $user->getLastName(); ?>">
                                                <p class="help-block">This field is the user's last name.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmupassword" control-label>Password:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmupassword" value="<?= $user->getPassword(); ?>">
                                                <p class="help-block">This field is the user's password.</p>
                                            </div>
                                            <label class="col-md-2" for="frmutype" control-label>User Type:</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="frmutype">
                                                    <?php   
                                                        echo '
                                                        <option selected>'.$user->getType().'</option>
                                                        ';
                                                        if($user->getType() == 'admin'){
                                                            echo '
                                                            <option>employee</option>
                                                            ';
                                                        } else {
                                                            echo '
                                                            <option>admin</option>
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
                                                <input class="form-control" type="text" name="frmuphone" value="<?= $user->getPhone(); ?>">
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

                    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

    <body>

<html>