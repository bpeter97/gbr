<DOCTYPE html>

<html>
    <head>
        <?php require_once(BASEURL.APP.ASSETS.'/header.php'); ?>
    </head>

    <body>

        <div id="wrapper">

            <?php include(BASEURL.APP.ASSETS.'/fixednavbar.php'); ?>

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div class="container-fluid" id="webbg">
                    <!-- 2nd Row. -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">
                                    <b>Full List of Orders</b>
                                </div>
                                <div class="panel-body">
                                    <?php    
                                    // # of orders in orders table
                                    $rows = $data['row'];

                                    // # of orders to display per pagination
                                    $page_rows = 20;

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

                                        $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.ORDERS.'/?pn=1">First</a></li>';

                                        if ($pagenum > 1) {
                                            $previous = $pagenum - 1;
                                            $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.ORDERS.'/?pn='.$previous.'">Previous</a></li>';
                                            // Render clickable number links that should appear on the left of the target page number
                                            for($i = $pagenum-2; $i < $pagenum; $i++){
                                                if($i > 0){
                                                    $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.ORDERS.'/?pn='.$i.'">'.$i.'</a></li>';
                                                }
                                            }
                                        }

                                        // Render the target page number, but without it being a link
                                        $paginationCtrls .= '<li class="active"><a href="'.HTTP.HTTPURL.PUB.ORDERS.'/?pn='.$pagenum.'">'.$pagenum.'</a></li>';

                                        // Render clickable number links that should appear on the right of the target page number
                                        for($i = $pagenum+1; $i <= $last; $i++){
                                            $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.ORDERS.'/?pn='.$i.'">'.$i.'</a></li> &nbsp;';
                                            if($i >= $pagenum+2){
                                                break;
                                            }
                                        }
                                        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                                        if ($pagenum != $last) {
                                            $next = $pagenum + 1;
                                            $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.ORDERS.'/?pn='.$next.'">Next</a></li>';
                                        }

                                        $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.ORDERS.'/?pn='.$last.'">Last</a></li>';
                                    }

                                    if($data['orderList']) {

                                        echo '
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                ' . $paginationCtrls . '
                                            </ul>
                                        </nav>
                                        ';

                                        echo '

                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Stage</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Rental or Resale</th>
                                                    <th>Ordered By</th>
                                                    <th>On-Site Contact</th>
                                                    <th>On-Site Contact #</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        ';

                                        foreach($data['orderList'] as $order) {

                                            if($order->stage == 1) {
                                                $tablebg = '<tr class="warning clickable-row" data-href="'.HTTP.HTTPURL.VIEW.'/orderinfo.php?oid='.$order->id.'">';
                                            } elseif($order->stage == 2) {
                                                $tablebg = '<tr class="info clickable-row" data-href="'.HTTP.HTTPURL.VIEW.'/orderinfo.php?oid='.$order->id.'">';
                                            } elseif($order->stage == 3){
                                                $tablebg = '<tr class="success clickable-row" data-href="'.HTTP.HTTPURL.VIEW.'/orderinfo.php?oid='.$order->id.'">';
                                            }

                                            echo '

                                            <tbody>
                                                '. $tablebg .'
                                                    <td>' . $order->id . '</td>
                                                    <td>' . $order->stage . '</td>
                                                    <td>' . $order->order_customer . '</td>
                                                    <td>' . $order->order_date . '</td>
                                                    <td>' . $order->order_time . '</td>
                                                    <td>' . $order->order_type . '</td>
                                                    <td>' . $order->ordered_by . '</td>
                                                    <td>' . $order->onsite_contact . '</td>
                                                    <td>' . $order->onsite_contact_phone . '</td>
                                                    <td>
                                                        <a class="btn btn-xs btn-warning" href="'.HTTP.HTTPURL.VIEW.'/orderinfo.php?oid='.$order->id.'">
                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                        </a>
                                                        <a class="btn btn-xs btn-info button-link" href="#">
                                                            <span class="glyphicon glyphicon-print"></span>
                                                        </a>
                                                        <a class="btn btn-xs btn-danger" href="#">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            ';

                                        }

                                        echo '</table>';

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

                    <?php include(BASEURL.APP.ASSETS.'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <?php include(BASEURL.APP.ASSETS.'/botjsincludes.php'); ?>

    <body>

<html>