<?php
    $pickupsUrl = Config::get('site/siteurl').Config::get('site/pickups');
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
				
				function deleteModal(id, name)
				{
					document.getElementById("deleteBodyText").innerHTML = "Are you sure you would like to delete the quote for: " + name;
					document.getElementById("deleteForm").action = "<?= Config::get('site/siteurl').'/pickups/delete/'; ?>" + id;
					$("#deleteModal").modal();
                }
                
                function hideModal(id, name)
				{
                    document.getElementById("hideBodyText").innerHTML = "Are you sure you would like to archive the quote for: " + name;
                    document.getElementById("hideBodyText").innerHTML += "</br></br></br><strong>This will archive it forever!</strong>";
					document.getElementById("hideForm").action = "<?= Config::get('site/siteurl').'/pickups/hide/'; ?>" + id;
					$("#hideModal").modal();
				}

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
                        case 'hsuccess':
                            $webAction = 'hidden';
                            break;
                        default:
                            $webAction = 'submitted/saved';
                    }
                    
                    ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>Congratulations!</strong> You have successfully <?= $webAction ?> a pickup!
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
                                    <b>Full List of Pickups</b>
                                </div>
                                <div class="panel-body">
                                    <?php    
                                    // # of pickups in pickups table
                                    $rows = $data['row'];

                                    // # of pickups to display per pagination
                                    $page_rows = 100;

                                    // This tells us the page # of our last page
                                    $last = ceil($rows/$page_rows);

                                    // This ensures that last is not less than 1.
                                    if ($last < 1)
                                    {
                                        $last = 1;
                                    }

                                    // Current Pagination number.
                                    $pagenum = 1;

                                    // Get pagenum from URL vars if it is present, else it will equal 1.
                                    if (isset($_GET['pn'])) {
                                        $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
                                    }

                                    // This is to ensure pagenum never gets less than 1 or more than the last page.
                                    if ($pagenum < 1) {
                                        $pagenum = 1;
                                    } else if ($pagenum > $last) {
                                        $pagenum = $last;
                                    }

                                    $paginationCtrls = '';
                                    // If there is more than 1 page worth of results
                                    if($last != 1){
                                        /* First we check if we are on page one. If we are then we don't need a link to
                                           the previous page or the first page so we do nothing. If we aren't then we
                                           generate links to the first page, and to the previous page. */

                                        $paginationCtrls .= '<li><a href="'.$pickupsUrl.'/?pn=1">First</a></li>';

                                        if ($pagenum > 1) {
                                            $previous = $pagenum - 1;
                                            $paginationCtrls .= '<li><a href="'.$pickupsUrl.'/?pn='.$previous.'">Previous</a></li>';
                                            // Render clickable number links that should appear on the left of the target page number
                                            for($i = $pagenum-2; $i < $pagenum; $i++){
                                                if($i > 0){
                                                    $paginationCtrls .= '<li><a href="'.$pickupsUrl.'/?pn='.$i.'">'.$i.'</a></li>';
                                                }
                                            }
                                        }

                                        // Render the target page number, but without it being a link
                                        $paginationCtrls .= '<li class="active"><a href="'.$pickupsUrl.'/?pn='.$pagenum.'">'.$pagenum.'</a></li>';

                                        // Render clickable number links that should appear on the right of the target page number
                                        for($i = $pagenum+1; $i <= $last; $i++){
                                            $paginationCtrls .= '<li><a href="'.$pickupsUrl.'/?pn='.$i.'">'.$i.'</a></li> &nbsp;';
                                            if($i >= $pagenum+2){
                                                break;
                                            }
                                        }
                                        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                                        if ($pagenum != $last) {
                                            $next = $pagenum + 1;
                                            $paginationCtrls .= '<li><a href="'.$pickupsUrl.'/?pn='.$next.'">Next</a></li>';
                                        }

                                        $paginationCtrls .= '<li><a href="'.$pickupsUrl.'/?pn='.$last.'">Last</a></li>';
                                    }

                                    if($data['pickupList']) {

                                        echo '
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                ' . $paginationCtrls . '
                                            </ul>
                                        </nav>
                                        ';

                                        echo '
                                        <div class="col-lg-10">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Pickup ID</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                        ';

                                        echo '<tbody>';
                                        foreach($data['pickupList'] as $pickup) {

                                            if($pickup->getCompleted() == "true" || $pickup->getCompleted() == 'TRUE') {
                                                $tablebg = '<tr class="success clickable-row" data-href="#">';
                                            } else {
                                                $tablebg = '<tr class="danger clickable-row" data-href="#">';
                                            }

                                            echo '

                                                '. $tablebg .'
                                                    <td>' . $pickup->getId() . '</td>
                                                    <td>' . $pickup->getCustomerName() . '</td>
                                                    <td>' . $pickup->getDate() . '</td>
                                                    <td>' . $pickup->getTime() . '</td>
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
													<th>Tools</th>
												</tr>
											</thead>
										';
                                        echo '<tbody>';
                                        foreach($data['pickupList'] as $pickup) {

                                            if($pickup->getCompleted() == "true" || $pickup->getCompleted() == 'TRUE') {
                                                $tablebg = '<tr class="success">';
                                            } else {
                                                $tablebg = '<tr class="danger">';
                                            }

                                            echo '
                                                '. $tablebg .'
                                                    <td style="text-align: center;">
                                                        <a class="btn btn-xs btn-warning" href="'.$pickupsUrl.'/edit/'.$pickup->getId().'">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                        </a>
                                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                        </a>
                                                        <a class="btn btn-xs btn-info button-link" href="'.$pickupsUrl.'/viewinfo/' . $pickup->getId() . '">
                                                        <span class="glyphicon glyphicon-print"></span>
                                                        </a>';?>
                                                        <a class="btn btn-xs btn-danger" href="#" onclick='deleteModal(<?= $pickup->getId(); ?>,"<?= $pickup->getCustomerName(); ?>")'>
                                                        <?php echo '
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                        </a>';?>
                                                        <a class="btn btn-xs btn-danger" href="#" onclick='hideModal(<?= $pickup->getId(); ?>,"<?= $pickup->getCustomerName(); ?>")'>
                                                        <?php echo '
                                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>';
                                        }
                                        echo '</tbody>';
										echo '</table>';
                                        echo '</div>';
                                        
                                    } else {

                                        echo "Couldn't issue database query.";

                                    }

                                    echo '
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                ' . $paginationCtrls . '
                                            </ul>
                                        </nav>
                                        ';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of 2nd Row. -->

                    <div id="deleteModal" class="modal fade" role="dialog">
					  <div class="modal-dialog">
					  	<form action="" id="deleteForm">
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
                    
                    <div id="hideModal" class="modal fade" role="dialog">
					  <div class="modal-dialog">
					  	<form action="" id="hideForm">
					    <!-- Modal content-->
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title" style="color=#FF0000;">!!!!! WARNING !!!!!</h4>
					      </div>
					      <div class="modal-body">
					        <p id="hideBodyText">This will be replaced.</p>
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