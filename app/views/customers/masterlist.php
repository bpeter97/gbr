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
                    <strong>Congratulations!</strong> You have successfully <?= $webAction ?> a customer!
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
                                    <b>Master List of Customers</b>
                                </div>
                                <div class="panel-body">
                                <input type="text" id="filterName" onkeyup="searchNames()" placeholder="Search for names..">
                                    <?php    
                                    // # of customers in customers table
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

                                        $paginationCtrls .= '<li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?pn=1">First</a></li>';

                                        if ($pagenum > 1) {
                                            $previous = $pagenum - 1;
                                            $paginationCtrls .= '<li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?pn='.$previous.'">Previous</a></li>';
                                            // Render clickable number links that should appear on the left of the target page number
                                            for($i = $pagenum-2; $i < $pagenum; $i++){
                                                if($i > 0){
                                                    $paginationCtrls .= '<li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?pn='.$i.'">'.$i.'</a></li>';
                                                }
                                            }
                                        }

                                        // Render the target page number, but without it being a link
                                        $paginationCtrls .= '<li class="active"><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?pn='.$pagenum.'">'.$pagenum.'</a></li>';

                                        // Render clickable number links that should appear on the right of the target page number
                                        for($i = $pagenum+1; $i <= $last; $i++){
                                            $paginationCtrls .= '<li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?pn='.$i.'">'.$i.'</a></li> &nbsp;';
                                            if($i >= $pagenum+2){
                                                break;
                                            }
                                        }
                                        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                                        if ($pagenum != $last) {
                                            $next = $pagenum + 1;
                                            $paginationCtrls .= '<li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?pn='.$next.'">Next</a></li>';
                                        }

                                        $paginationCtrls .= '<li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?pn='.$last.'">Last</a></li>';
                                    }

                                    // Create the array containing the alphabet.
                                    $letterlist = '';
                                    $c = 'A';
                                    $chars = array($c);
                                    while ($c < 'Z') {
                                        $chars[] = ++$c;
                                    }

                                    // Generates the links for the alphebet.
                                    $counter = 0;
                                    while ($counter <> 26) {
                                        $letterlist .= '<li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'/?f='.$chars[$counter].'">'.$chars[$counter].'</a></li> &nbsp;';
                                        $counter += 1;
                                    }

                                    if($data['custList']) {

                                        echo '
                                            <ul class="pagination">
                                                <li><a href="'.Config::get('site/siteurl').Config::get('site/customers').'">ALL</a></li> &nbsp;
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

                                            if($cus->getFlag() == "Yes"){
                                                $toolcount += 1;
                                                $danger = 'danger';
                                                $flag_reason = $cus->getFlagReason();
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
                                                <tr class="clickable-row '.$danger.'" data-href="'.Config::get('site/siteurl').'/customers/id/' . $cus->getId() .'" '.$tooltip.'>
                                                    <td>' . $cus->getCustomerName() . '</td>
                                                    <td>' . $cus->getCustomerPhone() . '</td>
                                                    <td>' . $cus->getCustomerExt() . '</td>
                                                    <td>' . $cus->getCustomerFax() . '</td>
                                                    <td>' . $cus->getCustomerEmail() . '</td>
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