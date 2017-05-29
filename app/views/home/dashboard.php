<?php

$chart = $data['chart'];
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$con_list = [
            "10' Containers",
            "20' Containers",
            "20' Combos",
            "20' Full Offices",
            "20' Double Door",
            "20' Containers w/ Shelves",
            "20' High Cube",
            "22' DD/HC",
            "22' High Cube",
            "24' Containers",
            "24' High Cube",
            "40' Containers",
            "40' Combos",
            "40' Double Doors",
            "40' Full Offices",
            "40' High Cubes"
            ];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(BASEURL.APP.ASSETS.'/header.php'); ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmAJNXfLD_-32yOSheQ-xo4gySGStag9U&v=3.exp&libraries=places"
            type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo HTTP.HTTPURL.PUB.JS.'/mapprinting.js'; ?>"></script>
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
      downloadUrl("http://localhost/public/js/map/phpsqlajax_genxml2.php", function(data) {
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
    <script type="text/javascript" src="<?php echo HTTP.HTTPURL.PUB.JS.'/dashboard_charts.js'; ?>"></script>
    
</head>

<body>

    <div id="wrapper">

        <?php include BASEURL.APP.ASSETS.'/fixednavbar.php'; ?>
    
        <!-- Page Content -->
        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <!-- include BASEURL.VIEW.'/dashboard.php'; ?> -->
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
                                <table id="ordertable" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Quotes</th>
                                            <th>Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for($i=0;$i<12;$i++)
                                        {
                                        ?>
                                        <tr>
                                            <th><?php echo $months[$i]; ?></th>
                                            <td><?php echo $chart->quotes[$i]; ?></td>
                                            <td><?php echo $chart->orders[$i]; ?></td>
                                        </tr>
                                        <?php } ?>
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
                                <table id="datatable" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Rentals</th>
                                            <th>Sales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for($i=0;$i<16;$i++)
                                        {
                                        ?>
                                        <tr>
                                            <th><?php echo $con_list[$i]; ?></th>
                                            <td><?php echo $chart->rentals[$i]; ?></td>
                                            <td><?php echo $chart->resales[$i]; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php include(BASEURL.APP.ASSETS.'/copyright.php'); ?>

                </div>

    </div>

    <?php include BASEURL.APP.ASSETS.'/botjsincludes.php'; ?>

</body>

</html>