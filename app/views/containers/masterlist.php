<?php

	$delete_id = null;
	$delete_container = null;

?>

<DOCTYPE html>

<html>
	<head>
		<?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>
	</head>

	<body>
		
		<div id="wrapper">

			<?php include(Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'); ?>
			
			<script type="text/javascript">
				
				function deleteModal(con_id, con_number)
				{
					document.getElementById("deleteBodyText").innerHTML = "Are you sure you would like to delete container: " + con_number;
					document.getElementById("deleteContainerForm").action = "<?= Config::get('site/siteurl').'/containers/delete/'; ?>" + con_id;
					$("#deleteContainerModal").modal();
				}

			</script>

			<script type="text/javascript">

                $(document).ready(function() {
                    $('#maintable').DataTable();
                } );

            </script>

			<!-- Page Content -->
			<div id="page-content-wrapper">

				<div class="container-fluid" id="webbg">

				<?php if(isset($_GET['action'])): ?>
                    <?php

                    switch ($_GET['action']) {
                        case 'usuccess':
                            $webAction = 'updated';
                            break;
                        case 'dsuccess':
                            $webAction = 'deleted';
                            break;
                        case 'csuccess':
                            $webAction = 'created';
                            break;
                        default:
                            $webAction = 'submitted/saved';
                    }
                    
                    ?>

                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>Congratulations!</strong> You have successfully <?= $webAction ?> a container!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <?php endif; ?>
					<!-- 2nd Row. -->
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading text-center">
									<b>Master List of Containers</b>
								</div>
								<div class="panel-body">
								
									<?php    

									if($data['conList']) {

										echo '
										<div class="col-lg-10">
										<table class="table table-striped table-hover" id="maintable">
											<thead>
												<tr>
													<th>GBR Number</th>
													<th>Serial Number</th>
													<th>Size</th>
													<th>Shelves?</th>
													<th>Paint?</th>
													<th>Numbers?</th>
													<th>Signs?</th>
													<th>Rental or Resale</th>
													<th>Is it rented?</th>
													<th>Release Number</th>
												</tr>
											</thead>
										';

										$toolcount = 0;
										echo '<tbody>';
										foreach($data['conList'] as $con) {

											if($con->getIsRented()=="TRUE"){
												$isrented = "Yes";
											}
											else
											{
												$isrented = "No";
											}

											if($con->getFlag() == "Yes"){
												$toolcount += 1;
												$danger = 'danger';
												$flag_reason = $con->getFlagReason();
												$tooltip = 'data-toggle="popover" data-placement="top" data-popover-content="#a'.$toolcount.'"';
												echo '
												<div id="a'.$toolcount.'" class="hidden">
													<div class="popover-body"><b>'.$flag_reason.'</b></div>
												</div>
												';

											} else {
												$danger = '';
												$flag_reason = '';
												$tooltip = '';
											}

											echo '

											
												<tr class="clickable-row '.$danger.'" data-href="'.Config::get('site/siteurl').'/containers/id/' . $con->getId().'" '.$tooltip.'>
													<td>' . $con->getContainerNumber() . '</td>
													<td>' . $con->getContainerSerialNumber() . '</td>
													<td>' . $con->getContainerSize() . '</td>
													<td>' . $con->getContainerShelves() . '</td>
													<td>' . $con->getContainerPaint() . '</td>
													<td>' . $con->getContainerOnboxNumbers() . '</td>
													<td>' . $con->getContainerSigns() . '</td>
													<td>' . $con->getRentalResale() . '</td>
													<td>' . $isrented . '</td>
													<td>' . $con->getReleaseNumber() . '</td>
												</tr>
											
											';

										}
										echo '</tbody>';

										echo '</table>';
										echo '</div>';
										echo '<div class="col-xs-2">';
										echo '

										<table class="table table-striped table-hover">
											<thead>
												<tr>
													<th style="text-align: center;">Tools</th>
												</tr>
											</thead>
										';
										echo '<tbody>';
										foreach($data['conList'] as $con) {
										echo '
												<tr>
													<td style="text-align: center;">
														<a class="btn btn-xs btn-warning" href="'.Config::get('site/siteurl').'/containers/id/' . $con->getId().'">
														<span class="glyphicon glyphicon-pencil"></span>
														</a>
														'; ?>														
														<a class="btn btn-xs btn-danger" href="#" onclick='deleteModal(<?= $con->getId(); ?>,"<?= $con->getContainerNumber(); ?>")'>
														<?php
														echo '
														<span class="glyphicon glyphicon-trash"></span>
														</a>
													</td>
												</tr>
											';

										}
										echo '</tbody>';
										echo '</table>';
										echo '</div>';
									} else {

										echo "Couldn't issue database query.";

									}

									?>
								</div>
							</div>
						</div>
					</div>
					<!-- End of 2nd Row. -->

					<div id="deleteContainerModal" class="modal fade" role="dialog">
					  <div class="modal-dialog">
					  	<form action="" id="deleteContainerForm">
					    <!-- Modal content-->
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title" style="color=#FF0000;">!!!!! WARNING !!!!!</h4>
					      </div>
					      <div class="modal-body">
					        <p id="deleteBodyText">This will be replaced.</p>
					      </div>
					      <div class="modal-footer">
					      	<button type="submit" class="btn btn-default" onclick="">Confirm</button>
					        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					      </div>
					      </form>
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