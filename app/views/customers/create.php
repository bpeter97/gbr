<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>

</head>

<body>

    <div id="wrapper">

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'); ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <!-- Need to add action (url) -->
        <form action="<?php echo Config::get('site/siteurl').Config::get('site/customers').'/create/?action=create'; ?>" method="post">

        
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
                                        <label class="col-md-2" for="frmcname" control-label>Customer Name:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcname" required="true" value="">
                                            <p class="help-block">This field is the customers first and last name or company name.</p>
                                        </div>
                                        <label class="col-md-2" for="frmcname" control-label>Address:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcaddy1">
                                            <input class="form-control" type="text" name="frmcaddy2">
                                            <p class="help-block">This field is the first and second line of the customers address.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcpnumber" control-label>Phone Number:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcpnumber">
                                            <p class="help-block">This field is the customers phone number.</p>
                                        </div>
                                        <label class="col-md-2" for="frmccity" control-label>City:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmccity">
                                            <p class="help-block">This field is the city of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcfnumber" control-label>Fax Number:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcfnumber">
                                            <p class="help-block">This field is the customers fax number.</p>
                                        </div>
                                        <label class="col-md-2" for="frmcext" control-label>Extension:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcext">
                                            <p class="help-block">This field is the customers extension if they have one.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcemail" control-label>E-Mail:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcemail">
                                            <p class="help-block">This field is the customers e-mail address.</p>
                                        </div>
                                       <label class="col-md-2" for="frmcstate" control-label>State:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcstate">
                                            <p class="help-block">This field is the state of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcrdp" control-label>RDP:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmcrdp">
                                            <p class="help-block">This field is the customers RDP.</p>
                                        </div>
                                         <label class="col-md-2" for="frmczipcode" control-label>Zipcode:</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="frmczipcode">
                                            <p class="help-block">This field is the zipcode of the address above.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcrdp" control-label>Flagged?</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="frmflaggedq" id="frmflaggedq" required="true">
                                                <option selected>Choose One</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            <p class="help-block">This field is to let us know if the customer is flagged or not.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmflagreason" control-label>Flag Reason:</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="frmflagreason"></textarea>
                                            <p class="help-block">This field tells us why the customer is flagged or not.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-2" for="frmcnotes" control-label>Notes:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" cols="40" rows="5" type="text" name="frmcnotes"></textarea>
                                            <p class="help-block">This field is the customers notes.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-default form-button">Create</button>
                                    <button type="button" onclick="history.go(-1);" class="btn btn-default form-button" style="margin-top: 7px;">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->

                <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>
            </div>
        </form>
    </div>

    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

</body>

</html>