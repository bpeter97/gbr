<?php

function checkcolor($variable){
	if($variable == 100) {
		$bar_color = "progress-bar-success";
	} elseif ($variable >= 50) {
		$bar_color = "progress-bar-info";
	} elseif ($variable >= 25) {
		$bar_color = "progress-bar-warning";
	} else {
		$bar_color = "progress-bar-danger";
	}
	return $bar_color;
}

$webdesign_status = 100;
$webdesign_color = checkcolor($webdesign_status);

$quotes_status = 50;
$quotes_color = checkcolor($quotes_status);

$containers_status = 75;
$containers_color = checkcolor($containers_status);

$customers_status = 25;
$customers_color = checkcolor($customers_status);

$orders_status = 10;
$orders_color = checkcolor($orders_status);

$current_task = "Orders";
$average_complete = ($webdesign_status + $quotes_status + $containers_status + $customers_status + $orders_status)/500*100;

echo '
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-danger alert-dismissible text-center" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            <strong>Heads up!</strong>
            <hr class="divider headeralert"><br/>
            <strong>Current Task:</strong> '. $current_task .'<br/>
            <strong>Total Progress:</strong> '. $average_complete .'%
            <div class="progress progalert border-1px-solid-custdarkgrey">
                <div class="progress-bar '. $webdesign_color .' progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '. $webdesign_status .'%;">
                    <strong>Web Design: '. $webdesign_status .'% Complete</strong>
                </div>
            </div>
            <div class="progress progalert border-1px-solid-custdarkgrey">
                <div class="progress-bar '. $quotes_color .' progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '. $quotes_status .'%;">
                    <strong>Quotes: '. $quotes_status .'% Complete</strong>
                </div>
            </div>
            <div class="progress progalert border-1px-solid-custdarkgrey">
                <div class="progress-bar '. $containers_color .' progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '. $containers_status .'%;">
                    <strong>Containers: '. $containers_status .'% Complete</strong>
                </div>
            </div><div class="progress progalert border-1px-solid-custdarkgrey">
                <div class="progress-bar '. $customers_color .' progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '. $customers_status .'%;">
                    <strong>Customers: '. $customers_status .'% Complete</strong>
                </div>
            </div><div class="progress progalert border-1px-solid-custdarkgrey">
                <div class="progress-bar '. $orders_color .' progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '. $orders_status .'%;">
                    <strong>Orders: '. $orders_status .'% Complete</strong>
                </div>
            </div>
        </div>
    </div>
</div>
';
?>