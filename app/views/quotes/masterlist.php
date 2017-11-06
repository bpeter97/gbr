<?php
    $quotesUrl = Config::get('site/siteurl').Config::get('site/quotes');
?>

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
                                    <b>Full List of Quotes</b>
                                </div>
                                <div class="panel-body">
                                    <?php    
                                    // # of quotes in quotes table
                                    $rows = $data['row'];

                                    // # of quotes to display per pagination
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

                                        $paginationCtrls .= '<li><a href="'.$qutotesUrl.'/?pn=1">First</a></li>';

                                        if ($pagenum > 1) {
                                            $previous = $pagenum - 1;
                                            $paginationCtrls .= '<li><a href="'.$qutotesUrl.'/?pn='.$previous.'">Previous</a></li>';
                                            // Render clickable number links that should appear on the left of the target page number
                                            for($i = $pagenum-2; $i < $pagenum; $i++){
                                                if($i > 0){
                                                    $paginationCtrls .= '<li><a href="'.$qutotesUrl.'/?pn='.$i.'">'.$i.'</a></li>';
                                                }
                                            }
                                        }

                                        // Render the target page number, but without it being a link
                                        $paginationCtrls .= '<li class="active"><a href="'.$qutotesUrl.'/?pn='.$pagenum.'">'.$pagenum.'</a></li>';

                                        // Render clickable number links that should appear on the right of the target page number
                                        for($i = $pagenum+1; $i <= $last; $i++){
                                            $paginationCtrls .= '<li><a href="'.$qutotesUrl.'/?pn='.$i.'">'.$i.'</a></li> &nbsp;';
                                            if($i >= $pagenum+2){
                                                break;
                                            }
                                        }
                                        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                                        if ($pagenum != $last) {
                                            $next = $pagenum + 1;
                                            $paginationCtrls .= '<li><a href="'.$qutotesUrl.'/?pn='.$next.'">Next</a></li>';
                                        }

                                        $paginationCtrls .= '<li><a href="'.$qutotesUrl.'/?pn='.$last.'">Last</a></li>';
                                    }

                                    if($data['quoteList']) {

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
                                                    <th>Quote ID</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Rental or Resale</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        ';

                                        foreach($data['quoteList'] as $quote) {

                                            if($quote->getStatus() == "Closed") {
                                                $tablebg = '<tr class="success clickable-row" data-href="'.$quotesUrl.'/viewinfo/' . $quote->getId() . '">';
                                            } else {
                                                $tablebg = '<tr class="danger clickable-row" data-href="'.$quotesUrl.'/viewinfo/' . $quote->getId() . '">';
                                            }

                                            echo '

                                            <tbody>
                                                '. $tablebg .'
                                                    <td>' . $quote->getId() . '</td>
                                                    <td>' . $quote->getCustomer() . '</td>
                                                    <td>' . $quote->getDate() . '</td>
                                                    <td>' . $quote->getType() . '</td>
                                                    <td>' . $quote->getStatus() . '</td>
                                                    <td>
                                                        <a class="btn btn-xs btn-warning" href="'.$quotesUrl.'/edit/'.$quote->getId().'">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                        </a>
                                                        <a type="button" class="btn btn-xs btn-success" href="'.$quotesUrl.'/convert/'.$quote->getId().'">
                                                        <span class="glyphicon glyphicon-usd"></span>
                                                        </a>
                                                        <a class="btn btn-xs btn-info button-link" href="'.$quotesUrl.'/viewinfo/' . $quote->getId() . '">
                                                        <span class="glyphicon glyphicon-print"></span>
                                                        </a>
                                                        <a class="btn btn-xs btn-danger" href="'.$quotesUrl.'/delete/'.$quote->getId().'">
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

                    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

                </div>

            </div>

        </div>

        <?php include(Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'); ?>

    <body>

<html>