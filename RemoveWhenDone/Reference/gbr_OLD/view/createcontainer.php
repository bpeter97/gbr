<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    //Check if session is started or not.
    if(session_id() == '' || !isset($_SESSION)) {
        session_start();
    }

    // Check if logged in.
    if(!isset($_SESSION['loggedin'])) {
        $locked = HTTP.HTTPURL.'/view/locked.php';
        header('Location: '.$locked);
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.MODEL.'/header.php'); ?>
    <script type="text/javascript">
    $(document).ready(function () {
        $('#frmquotedate').datepicker({
            format: "dd/mm/yyyy"
        });
    });
    </script>

</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.INCLUDES.'/fixednavbar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <!-- 2nd Row. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>New Container</b>
                            </div>
                            <div class="panel-body">
                                <form action="
                                <?php echo HTTP.HTTPURL.CONTROLLERS.'/editcontainer.php?of=rentalcontainers&action=create'; ?>"" method="post">
                                    <div class="row"><!-- 1st Row -->
                                        <div class="col-lg-12">
                                        <input type="hidden" name="containerID" value="">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="frmcontainernumber">Container Number</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input id="frmcontainernumber" name="frmcontainernumber" class="form-control" placeholder="Type container # here." type="text" required="" value="">
                                                    </div>
                                                    <p class="help-block">This is the GBR number (##-####).</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 1st Row -->
                                    <div class="row"><!-- 2nd Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="frmcontainerserial">Container Serial #</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input id="frmcontainerserial" name="frmcontainerserial" class="form-control" placeholder="Type container serial number here." type="text" required="" value="">
                                                     </div>
                                                    <p class="help-block">This is the serial number that is on the actual container.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 2nd Row -->
                                    <div class="row" style="margin-top:5px;"><!-- 3rd Row -->
                                        <div class="col-lg-12">
                                            <label class="col-md-4 control-label">Container Variables</label>
                                            <div class="col-md-8">
                                                <div class="checkbox" style="margin-top:0px;">
                                                    <label><input type="hidden" name="containershelves" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value">Container Has Shelves?</label><br/>
                                                    <label><input type="hidden" name="containerpainted" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value">Container Is Painted?</label><br/>
                                                    <label><input type="hidden" name="containergbrnumbers" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value">Container Has GBR Numbers?</label><br/>
                                                    <label><input type="hidden" name="containersigns" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value">Container Has Signs?</label><br/>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 3rd Row -->
                                    <div class="row"><!-- 4th Row -->
                                        <div class="col-lg-12"><!-- Seconed Column in 2nd Row -->
                                            <label class="col-md-4 control-label" for="frmcontainersize">Container Size</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon"></span>
                                                    <input id="frmcontainersize" name="frmcontainersize" class="form-control" placeholder="Type container size here." type="text" required="" value="">
                                                </div>
                                                <p class="help-block">This is the size of the container (ex: 20).</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 4th Row -->
                                    <div class="row"><!-- 5th Row -->
                                        <div class="col-lg-12"><!-- Seconed Column in 2nd Row -->
                                            <label class="col-md-4" for="frmrentalresale" control-label">Rental or Resale</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="frmrentalresale" id="frmrentalresale">
                                                    <option>Select One</option>
                                                    <option>Rental</option>
                                                    <option>Resale</option>
                                                </select>
                                                <p class="help-block">Select whether or not the it is a rental or a resale container.</p>
                                            </div>
                                        </div>
                                    </div><!-- End of 5th Row -->
                                     <div class="row"><!-- 6th Row -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="frmcontainerrelease">Container Release #</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"></span>
                                                        <input id="frmcontainerrelease" name="frmcontainerrelease" class="form-control" placeholder="Type container serial number here." type="text" value="">
                                                     </div>
                                                    <p class="help-block">Type the release number if there is one.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End of 6th Row -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-default">Submit</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->
                <?php include BASEURL.INCLUDES.'/copyright.php'; ?>

            </div>

        </div>

    </div>

    <?php include(BASEURL.INCLUDES.'/botjsincludes.php'); ?>

</body>

</html>
