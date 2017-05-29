<?php

    // Create URL to CFG/SETTINGS.PHP file.
    $cfgurl = $_SERVER['DOCUMENT_ROOT'];
    $cfgurl .= "/cfg/settings.php";

    //Variable Constants
    include($cfgurl);

    include(BASEURL.CFG.'/database.php');

	if($_POST['containernumber']) {
    	$id = $_POST['containernumber']; //escape string

    	$db = new Database();
    	$db->connect();

    	$db->sql("SELECT * FROM containers WHERE container_number = '".$id."'");
    	$response = $db->getResult();

	    function checkboxes($checkboxcontainer){
	    	if ($checkboxcontainer == "Yes") {
				$container_checked = 'checked="true"';
				$container_checked_value = 1;
			} else {
				$container_checked = '""';
				$container_checked_value = 0;
			}
			return array($container_checked_value, $container_checked);
	    }

    	// Beginning of Modal Code.
    	foreach($response as $row) {

    		$containershelves = checkboxes($row['container_shelves']);
    		$containerpaint = checkboxes($row['container_paint']);
    		$containeronboxnumbers = checkboxes($row['container_onbox_numbers']);
    		$containersigns = checkboxes($row['container_signs']);

	    	echo '
	   		<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h4 class="modal-title" style="text-align:center;">Edit Container: ' . $row['container_number'] . '</h4>
	            </div>
	            <div class="modal-body">
	                <form action="../controller/savecontainerinfo.php" method="post">
	                    <div class="row"><!-- 1st Row -->
	                        <div class="col-lg-12">
	                        <input type="hidden" name="containerID" value="'. $row['container_ID'] .'">
	                            <div class="form-group">
	                                <label class="col-md-4 control-label" for="frmcontainernumber">Container Number</label>
	                                <div class="col-md-8">
	                                    <div class="input-group">
	                                        <span class="input-group-addon"></span>
	                                        <input id="frmcontainernumber" name="frmcontainernumber" class="form-control" placeholder="Type container # here." type="text" required="" value="'. $row['container_number'] .'">
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
	                                        <input id="frmcontainerserial" name="frmcontainerserial" class="form-control" placeholder="Type container serial number here." type="text" required="" value="'. $row['container_serial_number'] .'">
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
	                                    <label><input type="hidden" name="containershelves" value="'. $containershelves[0] .'"><input type="checkbox" '. $containershelves[1] .' onclick="this.previousSibling.value=1-this.previousSibling.value">Container Has Shelves?</label><br/>
	                                    <label><input type="hidden" name="containerpainted" value="'. $containerpaint[0] .'"><input type="checkbox" '. $containerpaint[1] .' onclick="this.previousSibling.value=1-this.previousSibling.value">Container Is Painted?</label><br/>
	                                    <label><input type="hidden" name="containergbrnumbers" value="'. $containeronboxnumbers[0] .'"><input type="checkbox" '. $containeronboxnumbers[1] .' onclick="this.previousSibling.value=1-this.previousSibling.value">Container Has GBR Numbers?</label><br/>
	                                    <label><input type="hidden" name="containersigns" value="'. $containersigns[0] .'"><input type="checkbox" '. $containersigns[1] .' onclick="this.previousSibling.value=1-this.previousSibling.value">Container Has Signs?</label><br/>
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
	                                    <input id="frmcontainersize" name="frmcontainersize" class="form-control" placeholder="Type container size here." type="text" required="" value="'. $row['container_size'] .'">
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
	                                	'. //Change this to select one later
	                                	'
	                                    <option>'. $row['rental_resale'] .'</option>
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
	                                        <input id="frmcontainerrelease" name="frmcontainerrelease" class="form-control" placeholder="Type container serial number here." type="text" value="'. $row['release_number'] .'">
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
	        ';
	    }
	}
?>
