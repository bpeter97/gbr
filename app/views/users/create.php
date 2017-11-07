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
                                    <form action="<?php echo Config::get('site/siteurl').Config::get('site/users').'/create/'; ?>" id="orderForm" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmuname" control-label>User Name:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmuname" required="true" placeholder="Enter Username">
                                                <p class="help-block">This field is the user's username.</p>
                                            </div>
                                            <label class="col-md-2" for="frmutitle" control-label>Title:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmutitle" required="false" placeholder="Enter Title">
                                                <p class="help-block">This field is the title of the user.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmufirstname" control-label>First Name:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmufirstname" placeholder="Enter First Name">
                                                <p class="help-block">This field is the user's first name.</p>
                                            </div>
                                            <label class="col-md-2" for="frmulastname" control-label>Last Name:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmulastname" placeholder="Enter Last Name">
                                                <p class="help-block">This field is the user's last name.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmupassword" control-label>Password:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmupassword" placeholder="Enter a Password">
                                                <p class="help-block">This field is the user's password.</p>
                                            </div>
                                            <label class="col-md-2" for="frmutype" control-label>User Type:</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="frmutype">
                                                    <option selected>Select One</option>
                                                    <option value="employee">employee</option>
                                                    <option value="admin">admin</option>
                                                </select>
                                                <p class="help-block">This field is the user's type.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="col-md-2" for="frmuphone" control-label>User Phone Number:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" type="text" name="frmuphone" placeholder="(555) 555-5555">
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