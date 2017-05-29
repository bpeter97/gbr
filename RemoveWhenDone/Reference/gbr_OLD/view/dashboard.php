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

    include(BASEURL.CFG.'/database.php');
    $db = new Database();
    $db->connect();

?>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"
            type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            $('.map-print').on('click',

            /*
             * Print dat maps!
             */
            function printMaps() {
              var body               = $('body');
              var mapContainer       = $('#map');
              var mapContainerParent = mapContainer.parent();
              var printContainer     = $('<div>');

              printContainer
                .addClass('print-container')
                .css('position', 'relative')
                .height(mapContainer.height())
                .append(mapContainer)
                .prependTo(body);

              var content = body
                .children()
                .not('script')
                .not(printContainer)
                .detach();
                
              // Patch for some Bootstrap 3.3.x `@media print` styles. :|
              var patchedStyle = $('<style>')
                .attr('media', 'print')
                .text('img { max-width: none !important; }' +
                      'a[href]:after { content: ""; }')
                .appendTo('head');

              window.print();

              body.prepend(content);
              mapContainerParent.prepend(mapContainer);

              printContainer.remove();
              patchedStyle.remove();
            });
        });
    </script>

    <script type="text/javascript">

    var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
      },
      container: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_green.png'
      },
      bar: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
      }
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(36.341990,-119.417796),
        zoom: 10,
        mapTypeId: 'hybrid'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("map/phpsqlajax_genxml2.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> <br/>" + address;
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
      var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
       map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));
       google.maps.event.addListener(searchBox, 'places_changed', function() {
         searchBox.set('map', null);


         var places = searchBox.getPlaces();

         var bounds = new google.maps.LatLngBounds();
         var i, place;
         for (i = 0; place = places[i]; i++) {
           (function(place) {
             var marker = new google.maps.Marker({

               position: place.geometry.location
             });
             marker.bindTo('map', searchBox, 'map');
             google.maps.event.addListener(marker, 'map_changed', function() {
               if (!this.getMap()) {
                 this.unbindAll();
               }
             });
             bounds.extend(place.geometry.location);


           }(place));

         }
         map.fitBounds(bounds);
         searchBox.set('map', map);
         map.setZoom(Math.min(map.getZoom(),12));


       });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    google.maps.event.addDomListener(window, 'load', load);
    //]]>
    </script>

    <script type="text/javascript">
    $(function () {
        Highcharts.chart('total_orders', {
            data: {
                table: 'ordertable'
            },
            chart: {
                type: 'line'
            },
            credits: {
                enabled: false
            },
            colors: ['#ff940a','#09b50b'
            ],
            title: {
                text: ''
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Units'
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                        this.point.y + ' - ' + this.point.name.toLowerCase();
                }
            }
        });
    });
    </script>

    <!-- This script contains the stock of our containers. -->
    <script type="text/javascript">
    $(function () {
        Highcharts.chart('stock_container', {
            data: {
                table: 'datatable'
            },
            chart: {
                type: 'column',
            },
            credits: {
                enabled: false
            },
            legend: {
                align: 'right',
                verticalAlign: 'top',
                layout: 'vertical',
                x: 0,
                y: 100
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },
            colors: ['#ff940a','#09b50b'
            ],
            title: {
                text: ''
            },
            xAxis: {
                labels: {
                    overflow: 'justify'
                }
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Units'
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                        this.point.y + ' - ' + this.point.name.toLowerCase();
                }
            }
        });
    });
    </script>

    
 

    <div class="row">
        <div class="col-lg-7">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                        <button class="map-print float-left btn btn-default" style="padding-top: 2px; padding-bottom: 2px; margin-top: -2px;">Print</button>
                        <b>Container Map</b> 
                </div>
                <div class="panel-body">
                    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                    <div id="map" style="width:100%;height:450px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <b><a class="homeLink" href="<?php echo HTTP.HTTPURL.VIEW.'/calendar.php';?>">Calendar</a></b>
                </div>
                <div class="panel-body">
                    <?php include(BASEURL.MODEL.'/calendar.php') ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End of 2nd Row. -->
    <!-- 3rd Row. -->
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <b>Orders / Quotes In <?php echo date('Y'); ?></b>
                </div>
                <div class="panel-body">
                    <div id="total_orders" style="min-width: 310px; height: 300px; margin: 0 auto"></div>

                    <?php

                    $begmonth = 1;
                    $curmonth = 1;

                    while ($curmonth != 13) {

                        // Query to see how many quotes there are for January of the current year.
                        $db->sql("SELECT COUNT(quote_id) FROM quotes WHERE MONTH(quote_date) = $begmonth AND YEAR(quote_date) = ".date('Y'));
                        $pag_response = $db->getResult();
                        foreach($pag_response as $rowcount){
                            $row = $rowcount["COUNT(quote_id)"];
                        }

                        // # of quotes in quotes table for january.
                        if($begmonth == 1){
                            $jan_quote = $row;
                        } elseif($begmonth == 2) {
                            $feb_quote = $row;
                        } elseif($begmonth == 3) {
                            $mar_quote = $row;
                        } elseif($begmonth == 4) {
                            $apr_quote = $row;
                        } elseif($begmonth == 5) {
                            $may_quote = $row;
                        } elseif($begmonth == 6) {
                            $jun_quote = $row;
                        } elseif($begmonth == 7) {
                            $jul_quote = $row;
                        } elseif($begmonth == 8) {
                            $aug_quote = $row;
                        } elseif($begmonth == 9) {
                            $sep_quote = $row;
                        } elseif($begmonth == 10) {
                            $oct_quote = $row;
                        } elseif($begmonth == 11) {
                            $nov_quote = $row;
                        } elseif($begmonth == 12) {
                            $dec_quote = $row;
                        } else {
                            echo 'Error counting months...';
                        }

                        $begmonth += 1;
                        $curmonth += 1;

                    }

                    $begmonth = 1;
                    $curmonth = 1;

                    while ($curmonth != 13) {

                        // Query to see how many quotes there are for January of the current year.
                        $db->sql("SELECT COUNT(order_id) FROM orders WHERE MONTH(order_date) = $begmonth AND YEAR(order_date) = ".date('Y'));
                        $pag_response = $db->getResult();
                        foreach($pag_response as $rowcount){
                            $row = $rowcount["COUNT(order_id)"];
                        }

                        // # of quotes in quotes table for january.
                        if($begmonth == 1){
                            $jan_order = $row;
                        } elseif($begmonth == 2) {
                            $feb_order = $row;
                        } elseif($begmonth == 3) {
                            $mar_order = $row;
                        } elseif($begmonth == 4) {
                            $apr_order = $row;
                        } elseif($begmonth == 5) {
                            $may_order = $row;
                        } elseif($begmonth == 6) {
                            $jun_order = $row;
                        } elseif($begmonth == 7) {
                            $jul_order = $row;
                        } elseif($begmonth == 8) {
                            $aug_order = $row;
                        } elseif($begmonth == 9) {
                            $sep_order = $row;
                        } elseif($begmonth == 10) {
                            $oct_order = $row;
                        } elseif($begmonth == 11) {
                            $nov_order = $row;
                        } elseif($begmonth == 12) {
                            $dec_order = $row;
                        } else {
                            echo 'Error counting months...';
                        }

                        $begmonth += 1;
                        $curmonth += 1;

                    }

                    ?>


                    <table id="ordertable" style="display:none;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Quotes</th>
                                <th>Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Jan</th>
                                <td><?php echo $jan_quote ?></td>
                                <td><?php echo $jan_order ?></td>
                            </tr>
                            <tr>
                                <th>Feb</th>
                                <td><?php echo $feb_quote ?></td>
                                <td><?php echo $feb_order ?></td>
                            </tr>
                            <tr>
                                <th>Mar</th>
                                <td><?php echo $mar_quote ?></td>
                                <td><?php echo $mar_order ?></td>
                            </tr>
                            <tr>
                                <th>Apr</th>
                                <td><?php echo $apr_quote ?></td>
                                <td><?php echo $apr_order ?></td>
                            </tr>
                            <tr>
                                <th>May</th>
                                <td><?php echo $may_quote ?></td>
                                <td><?php echo $may_order ?></td>
                            </tr>
                            <tr>
                                <th>Jun</th>
                                <td><?php echo $jun_quote ?></td>
                                <td><?php echo $jun_order ?></td>
                            </tr>
                            <tr>
                                <th>Jul</th>
                                <td><?php echo $jul_quote ?></td>
                                <td><?php echo $jul_order ?></td>
                            </tr>
                            <tr>
                                <th>Aug</th>
                                <td><?php echo $aug_quote ?></td>
                                <td><?php echo $aug_order ?></td>
                            </tr>
                            <tr>
                                <th>Sep</th>
                                <td><?php echo $sep_quote ?></td>
                                <td><?php echo $sep_order ?></td>
                            </tr>
                            <tr>
                                <th>Oct</th>
                                <td><?php echo $oct_quote ?></td>
                                <td><?php echo $oct_order ?></td>
                            </tr>
                            <tr>
                                <th>Nov</th>
                                <td><?php echo $nov_quote ?></td>
                                <td><?php echo $nov_order ?></td>
                            </tr>
                            <tr>
                                <th>Dec</th>
                                <td><?php echo $dec_quote ?></td>
                                <td><?php echo $dec_order ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <b>Containers In Stock</b>
                </div>
                <div class="panel-body">
                <div id="stock_container" style="min-width: 310px; height: 300px; margin: 0 auto"></div>

                <?php

                $con_array = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);

                $total_cons = 1;
                $x = 0;

                while ($total_cons != 17) {

                    $db->sql("SELECT COUNT(container_ID) FROM containers WHERE container_size_code = ".$con_array[$x]." AND rental_resale = 'Rental' AND is_rented = 'FALSE'");
                    $first_res = $db->getResult();
                    foreach($first_res as $rowcount){
                        $row = $rowcount["COUNT(container_ID)"];
                    }

                    if($con_array[$x] == 1){
                        $rental_ten_count = $row;
                    } elseif ($con_array[$x] == 2) {
                        $rental_twenty_count = $row;
                    } elseif ($con_array[$x] == 3) {
                        $rental_twentyc_count = $row;
                    } elseif ($con_array[$x] == 4) {
                        $rental_twentyf_count = $row;
                    } elseif ($con_array[$x] == 5) {
                        $rental_twentydd_count = $row;
                    } elseif ($con_array[$x] == 6) {
                        $rental_twentyhc_count = $row;
                    } elseif ($con_array[$x] == 7) {
                        $rental_twentys_count = $row;
                    } elseif ($con_array[$x] == 8) {
                        $rental_twentytwoddhc_count = $row;
                    } elseif ($con_array[$x] == 9) {
                        $rental_twentytwohc_count = $row;
                    } elseif ($con_array[$x] == 10) {
                        $rental_twentyfour_count = $row;
                    } elseif ($con_array[$x] == 11) {
                        $rental_twentyfourhc_count = $row;
                    } elseif ($con_array[$x] == 12) {
                        $rental_fourty_count = $row;
                    } elseif ($con_array[$x] == 13) {
                        $rental_fourtyc_count = $row;
                    } elseif ($con_array[$x] == 14) {
                        $rental_fourtydd_count = $row;
                    } elseif ($con_array[$x] == 15) {
                        $rental_fourtyf_count = $row;
                    } elseif ($con_array[$x] == 16) {
                        $rental_fourtyhc_count = $row;
                    } else {
                        echo 'error matching condition';
                    }

                    $total_cons += 1;
                    $x += 1;

                }

                $total_cons = 1;
                $x = 0;

                while ($total_cons != 17) {

                    $db->sql("SELECT COUNT(container_ID) FROM containers WHERE container_size_code = ".$con_array[$x]." AND rental_resale = 'Resale' AND is_rented = 'FALSE'");
                    $first_res = $db->getResult();
                    foreach($first_res as $rowcount){
                        $row = $rowcount["COUNT(container_ID)"];
                    }

                    if($con_array[$x] == 1){
                        $sales_ten_count = $row;
                    } elseif ($con_array[$x] == 2) {
                        $sales_twenty_count = $row;
                    } elseif ($con_array[$x] == 3) {
                        $sales_twentyc_count = $row;
                    } elseif ($con_array[$x] == 4) {
                        $sales_twentyf_count = $row;
                    } elseif ($con_array[$x] == 5) {
                        $sales_twentydd_count = $row;
                    } elseif ($con_array[$x] == 6) {
                        $sales_twentyhc_count = $row;
                    } elseif ($con_array[$x] == 7) {
                        $sales_twentys_count = $row;
                    } elseif ($con_array[$x] == 8) {
                        $sales_twentytwoddhc_count = $row;
                    } elseif ($con_array[$x] == 9) {
                        $sales_twentytwohc_count = $row;
                    } elseif ($con_array[$x] == 10) {
                        $sales_twentyfour_count = $row;
                    } elseif ($con_array[$x] == 11) {
                        $sales_twentyfourhc_count = $row;
                    } elseif ($con_array[$x] == 12) {
                        $sales_fourty_count = $row;
                    } elseif ($con_array[$x] == 13) {
                        $sales_fourtyc_count = $row;
                    } elseif ($con_array[$x] == 14) {
                        $sales_fourtydd_count = $row;
                    } elseif ($con_array[$x] == 15) {
                        $sales_fourtyf_count = $row;
                    } elseif ($con_array[$x] == 16) {
                        $sales_fourtyhc_count = $row;
                    } else {
                        echo 'error matching condition';
                    }

                    $total_cons += 1;
                    $x += 1;

                }

                ?>

                    <table id="datatable" style="display:none;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Rentals</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>10' Containers</th>
                                <td><?php echo $rental_ten_count; ?></td>
                                <td><?php echo $sales_ten_count; ?></td>
                            </tr>
                            <tr>
                                <th>20' Containers</th>
                                <td><?php echo $rental_twenty_count; ?></td>
                                <td><?php echo $sales_twenty_count; ?></td>
                            </tr>
                            <tr>
                                <th>20' Combos</th>
                                <td><?php echo $rental_twentyc_count; ?></td>
                                <td><?php echo $sales_twentyc_count; ?></td>
                            </tr>
                            <tr>
                                <th>20' Full Offices</th>
                                <td><?php echo $rental_twentyf_count; ?></td>
                                <td><?php echo $sales_twentyf_count; ?></td>
                            </tr>
                            <tr>
                                <th>20' Double Door</th>
                                <td><?php echo $rental_twentydd_count; ?></td>
                                <td><?php echo $sales_twentydd_count; ?></td>
                            </tr>
                            <tr>
                                <th>20' Containers w/ Shelves</th>
                                <td><?php echo $rental_twentys_count; ?></td>
                                <td><?php echo $sales_twentys_count; ?></td>
                            </tr>
                            <tr>
                                <th>20' High Cube</th>
                                <td><?php echo $rental_twentyhc_count; ?></td>
                                <td><?php echo $sales_twentyhc_count; ?></td>
                            </tr>
                            <tr>
                                <th>22' DD/HC</th>
                                <td><?php echo $rental_twentytwoddhc_count; ?></td>
                                <td><?php echo $sales_twentytwoddhc_count; ?></td>
                            </tr>
                            <tr>
                                <th>22' High Cube</th>
                                <td><?php echo $rental_twentytwohc_count; ?></td>
                                <td><?php echo $sales_twentytwohc_count; ?></td>
                            </tr>
                            <tr>
                                <th>24' Containers</th>
                                <td><?php echo $rental_twentyfour_count; ?></td>
                                <td><?php echo $sales_twentyfour_count; ?></td>
                            </tr>
                            <tr>
                                <th>24' High Cube</th>
                                <td><?php echo $rental_twentyfourhc_count; ?></td>
                                <td><?php echo $sales_twentyfourhc_count; ?></td>
                            </tr>
                            <tr>
                                <th>40' Containers</th>
                                <td><?php echo $rental_fourty_count; ?></td>
                                <td><?php echo $sales_fourty_count; ?></td>
                            </tr>
                            <tr>
                                <th>40' Combos</th>
                                <td><?php echo $rental_fourtyc_count; ?></td>
                                <td><?php echo $sales_fourtyc_count; ?></td>
                            </tr>
                            <tr>
                                <th>40' Double Doors</th>
                                <td><?php echo $rental_fourtydd_count; ?></td>
                                <td><?php echo $sales_fourtydd_count; ?></td>
                            </tr>
                            <tr>
                                <th>40' Full Offices</th>
                                <td><?php echo $rental_fourtyf_count; ?></td>
                                <td><?php echo $sales_fourtyf_count; ?></td>
                            </tr>
                            <tr>
                                <th>40' High Cubes</th>
                                <td><?php echo $rental_fourtyhc_count; ?></td>
                                <td><?php echo $sales_fourtyhc_count; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
