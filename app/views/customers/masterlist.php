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
                                    <b>Master List of Customers</b>
                                </div>
                                <div class="panel-body">
                                <input type="text" id="filterName" onkeyup="searchNames()" placeholder="Search for names..">
                                    <?php    
                                    // # of containers in containers table
                                    $rows = $data['row'];
                                    $page_rows = $data['page_rows'];
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

                                        $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?pn=1">First</a></li>';

                                        if ($pagenum > 1) {
                                            $previous = $pagenum - 1;
                                            $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?pn='.$previous.'">Previous</a></li>';
                                            // Render clickable number links that should appear on the left of the target page number
                                            for($i = $pagenum-2; $i < $pagenum; $i++){
                                                if($i > 0){
                                                    $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?pn='.$i.'">'.$i.'</a></li>';
                                                }
                                            }
                                        }

                                        // Render the target page number, but without it being a link
                                        $paginationCtrls .= '<li class="active"><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?pn='.$pagenum.'">'.$pagenum.'</a></li>';

                                        // Render clickable number links that should appear on the right of the target page number
                                        for($i = $pagenum+1; $i <= $last; $i++){
                                            $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?pn='.$i.'">'.$i.'</a></li> &nbsp;';
                                            if($i >= $pagenum+2){
                                                break;
                                            }
                                        }
                                        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                                        if ($pagenum != $last) {
                                            $next = $pagenum + 1;
                                            $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?pn='.$next.'">Next</a></li>';
                                        }

                                        $paginationCtrls .= '<li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?pn='.$last.'">Last</a></li>';
                                    }

                                    $letterlist = '';
                                    $c = 'A';
                                    $chars = array($c);
                                    while ($c < 'Z') {
                                        $chars[] = ++$c;
                                    }

                                    $counter = 0;
                                    while ($counter <> 26) {
                                        $letterlist .= '<li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'/?f='.$chars[$counter].'">'.$chars[$counter].'</a></li> &nbsp;';
                                        $counter += 1;
                                    }

                                    if($data['custList']) {

                                        echo '
                                            <ul class="pagination">
                                                <li><a href="'.HTTP.HTTPURL.PUB.CUSTOMERS.'">ALL</a></li> &nbsp;
                                                ' . $letterlist . '
                                            </ul>
                                        ';

                                        echo '
                                            <ul class="pagination">
                                                ' . $paginationCtrls . '
                                            </ul>
                                        ';

                                        echo '

                                        <table class="table table-striped table-hover" id="custTable">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Ext</th>
                                                    <th>Fax</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                        ';

                                        $toolcount = 0;

                                        foreach($data['custList'] as $cus) {

                                            if($cus->flag == "Yes"){
                                                $toolcount += 1;
                                                $danger = 'danger';
                                                $flag_reason = $cus->flag_reason;
                                                $tooltip = ' data-toggle="popover" data-placement="top" data-popover-content="#a'.$toolcount.'"';
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

                                            <tbody>
                                                <tr class="clickable-row '.$danger.'" data-href="'.HTTP.HTTPURL.VIEW.'/customerinfo.php?id=' . $cus->id .'" '.$tooltip.'>
                                                    <td>' . $cus->customer_name . '</td>
                                                    <td>' . $cus->customer_phone . '</td>
                                                    <td>' . $cus->customer_ext . '</td>
                                                    <td>' . $cus->customer_fax . '</td>
                                                    <td>' . $cus->customer_email . '</td>
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