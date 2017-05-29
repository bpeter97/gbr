<?php
    // # of containers in containers table
    $rows = $row;

    // # of containers to display per pagination
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

    // This sets the range of rows to query for the chosen $pagenum.
    $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

    $paginationCtrls = '';
    // If there is more than 1 page worth of results
    if($last != 1){
        /* First we check if we are on page one. If we are then we don't need a link to
           the previous page or the first page so we do nothing. If we aren't then we
           generate links to the first page, and to the previous page. */

        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn=1">First</a></li>';

        if ($pagenum > 1) {
            $previous = $pagenum - 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a></li>';
            // Render clickable number links that should appear on the left of the target page number
            for($i = $pagenum-2; $i < $pagenum; $i++){
                if($i > 0){
                    $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
                }
            }
        }

        // Render the target page number, but without it being a link
        $paginationCtrls .= '<li class="active"><a href="'.$_SERVER['PHP_SELF'].'?pn='.$pagenum.'">'.$pagenum.'</a></li>';

        // Render clickable number links that should appear on the right of the target page number
        for($i = $pagenum+1; $i <= $last; $i++){
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li> &nbsp;';
            if($i >= $pagenum+2){
                break;
            }
        }
        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
        if ($pagenum != $last) {
            $next = $pagenum + 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a></li>';
        }

        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$last.'">Last</a></li>';
    }

    if($container) {

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
                    <th></th>
                </tr>
            </thead>
        ';

        $toolcount = 0;

        foreach($container as $con) {

            if($con->is_rented=="TRUE"){
                $isrented = "Yes";
            }
            else
            {
                $isrented = "No";
            }

            if($con->flag == "Yes"){
                $toolcount += 1;
                $danger = 'danger';
                $flag_reason = $con->flag_reason;
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
                <tr class="clickable-row '.$danger.'" data-href="'.HTTP.HTTPURL.CONTROLLERS.'/editcontainer.php?of=mastercontainers&action=edit&id=' . $con->id.'" '.$tooltip.'>
                    <td>' . $con->container_number . '</td>
                    <td>' . $con->container_serial_number . '</td>
                    <td>' . $con->container_size . '</td>
                    <td>' . $con->container_shelves . '</td>
                    <td>' . $con->container_paint . '</td>
                    <td>' . $con->container_onbox_numbers . '</td>
                    <td>' . $con->container_signs . '</td>
                    <td>' . $con->rental_resale . '</td>
                    <td>' . $isrented . '</td>
                    <td>' . $con->release_number . '</td>
                    <td>
                        <a class="btn btn-xs btn-warning" href="'.HTTP.HTTPURL.CONTROLLERS.'/editcontainer.php?of=mastercontainers&action=edit&id=' . $con->id.'">
                        <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a class="btn btn-xs btn-danger" href="'.HTTP.HTTPURL.CONTROLLERS.'/editcontainer.php?of=mastercontainers&action=delete&id=' . $con->id.'">
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