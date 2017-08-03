<?php

$chart = $data['chart'];
$events = $data['events'];
$con_list = $data['con_list'];

// echo '<pre>';
// var_dump($events);
// echo '</pre>';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmAJNXfLD_-32yOSheQ-xo4gySGStag9U&v=3.exp&libraries=places"
            type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo Config::get('site/http').Config::get('site/httpurl').Config::get('site/resources/js').'/mapprinting.js'; ?>"></script>
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
      downloadUrl("<?php echo Config::get('site/http').Config::get('site/httpurl').Config::get('site/resources/js').'/map/phpsqlajax_genxml2.php'; ?>", function(data) {
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
    <script type="text/javascript" src="<?php echo Config::get('site/http').Config::get('site/httpurl').Config::get('site/resources/js').'/dashboard_charts.js'; ?>"></script>

    
    
</head>

<body>

    <div id="wrapper">

        <?php include Config::get('site/baseurl').Config::get('site/assets').'/fixednavbar.php'; ?>
    
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid" id="webbg">
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
                                <b><a class="homeLink" href="<?php echo Config::get('site/http').Config::get('site/httpurl').'/calendar.php';?>">Calendar</a></b>
                            </div>
                            <div class="panel-body">
                                <div id="calendar" class="col-centered"></div>
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

                <?php include(Config::get('site/baseurl').Config::get('site/assets').'/copyright.php'); ?>

                </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="POST" action="<?php echo Config::get('site/http').Config::get('site/httpurl').'/home/addCustomEvent'; ?>">
                
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Event</h4>
                    </div>
                    <div class="modal-body">
                      
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" class="form-control" id="title" placeholder="Title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="color" class="col-sm-2 control-label">Color</label>
                            <div class="col-sm-10">
                                <select name="color" class="form-control" id="color">
                                    <option value="">Choose</option>
                                    <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
                                    <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
                                    <option style="color:#008000;" value="#008000">&#9724; Green</option>             
                                    <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
                                    <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
                                    <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
                                    <option style="color:#000;" value="#000">&#9724; Black</option>
                                    <option style="color:#FF1493;" value="#FF1493">&#9724; Pink (Delivery)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start" class="col-sm-2 control-label">Start date</label>
                            <div class="col-sm-10">
                                <input type="text" name="start" class="form-control" id="start">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end" class="col-sm-2 control-label">End date</label>
                            <div class="col-sm-10">
                                <input type="text" name="end" class="form-control" id="end">
                            </div>
                        </div>
                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-gbr" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-gbr">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                  
    <!-- Modal -->
    <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="POST" action="<?php echo Config::get('site/http').Config::get('site/httpurl').'/editEventTitle.php'; ?>">
                    <div class="modal-header gbr-header" style="text-align: center;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Event</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" class="form-control" id="title" placeholder="Title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="color" class="col-sm-2 control-label">Color</label>
                            <div class="col-sm-10">
                                <select name="color" class="form-control" id="color">
                                    <option value="">Choose</option>
                                    <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
                                    <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
                                    <option style="color:#008000;" value="#008000">&#9724; Green</option>             
                                    <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
                                    <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
                                    <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
                                    <option style="color:#000;" value="#000">&#9724; Black</option>
                                    <option style="color:#FF1493;" value="#FF1493">&#9724; Pink (Delivery)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label class="text-danger"><input type="checkbox"  name="delete"> Delete event</label>
                                </div>
                            </div>
                        </div>
                        <div id='response-content' style="margin-top: 50px;">
                            

                        <div id="orderDetailsDiv" class="panel panel-default">
                            <div class="panel-heading text-center">
                                <b>Order Details</b>
                            </div>
                            <div class="panel-body" id="prodInsert">

                            </div>
                        </div>




                        </div>
                        
                        <input type="hidden" name="id" class="form-control" id="id">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-gbr" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-gbr">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function() {
      

      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        height: 450,
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        businessHours: true,
        navLinks: true,
        select: function(start, end) {
          
          $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
          $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
          $('#ModalAdd').modal('show');
        },
        eventRender: function(event, element) {
          element.bind('dblclick', function() {

            $('#ModalEdit #id').val(event.id);
            $('#ModalEdit #title').val(event.title);
            $('#ModalEdit #color').val(event.color);
            if(event.custom == false)
            {
                    var getRidOfDiv = document.getElementById('orderDetailsDiv');
                    getRidOfDiv.style.display = 'block';
                    var prodTable = createProdTable(event);
                    $('#ModalEdit #prodInsert').html(prodTable);
            } else {
                var getRidOfDiv = document.getElementById('orderDetailsDiv');
                getRidOfDiv.style.display = 'none';
            }
            $('#ModalEdit').modal('show');
          });
        },
        eventDrop: function(event, delta, revertFunc) { // si changement de position

          edit(event);

        },
        eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

          edit(event);

        },

        events: [
        <?php foreach($events as $event): 
        
          $start = explode(" ", $event->getStart());
          $end = explode(" ", $event->getEnd());
          if($start[1] == '00:00:00'){
            $start = $start[0];
          }else{
            $start = $event->getStart();
          }
          if($end[1] == '00:00:00'){
            $end = $end[0];
          }else{
            $end = $event->getEnd();
          }
        ?>
          {
            id: '<?php echo $event->getId(); ?>',
            title: '<?php echo $event->getTitle(); ?>',
            start: '<?php echo $start; ?>',
            end: '<?php echo $end; ?>',
            <?php echo 'color: "'.$event->getColor().'",'.PHP_EOL; ?>
            order_id: <?= $event->getOrderId(); ?>,
            <?php

            if($event->getOrderId() == 0 || $event->getOrderId() = ''){
                echo 'custom: true,'.PHP_EOL;
            } else {
                echo 'custom: false,'.PHP_EOL;
                $prodCount = 0;
                foreach($event->order->products as $prod)
                {
                    echo 'prod'.$prodCount.': "'.$prod->getModName().'",'.PHP_EOL;
                    echo 'productQty'.$prodCount.': '.$prod->getProductQuantity().','.PHP_EOL;
                    $prodCount++;
                }
                echo 'prodCount: '.$prodCount.','.PHP_EOL;
                echo 'order_customer: "'.$event->order->getOrderCustomer().'"'.PHP_EOL;
            }
            ?>
          },
        <?php endforeach; ?>
        ]
      });
      
      function edit(event){
        start = event.start.format('YYYY-MM-DD HH:mm:ss');
        if(event.end){
          end = event.end.format('YYYY-MM-DD HH:mm:ss');
        }else{
          end = start;
        }
        
        id =  event.id;
        
        Event = [];
        Event[0] = id;
        Event[1] = start;
        Event[2] = end;
        
        $.ajax({
         url: '<?php echo Config::get('site/http').Config::get('site/httpurl').'/controllers/editEventDate.php'; ?>',
         type: "POST",
         data: {Event:Event},
         success: function(rep) {
            if(rep == 'OK'){
              alert('Saved');
            }else{
              alert('Could not be saved. try again.'); 
            }
          }
        });
      }

      function createProdTable(event)
      {
        var prodTable = '<table class="table table-striped table-hover">';
        prodTable += '<tr><th>Product</th><th>Quantity</th></tr>';
        <?php $i=1; ?>
        var l = 1;
        <?php foreach($events as $event): ?>
        <?php if(isset($event->order->getId())): ?>
        eventOrderId = <?php echo $event->order->getId(); ?>;
        console.log(event.order_id);
        if(eventOrderId != ''){
            if(eventOrderId == event.order_id)
            {
                <?php foreach($event->order->products as $prod): ?>
                prodTable += '<tr><td><?php echo $prod->getModName(); ?></td><td><?php echo $prod->getProductQuantity(); ?></td></tr>';
                <?php endforeach; ?>
            }
        }
        <?php endif; ?>
        
        <?php endforeach; ?>
        prodTable += '</table>';
        return prodTable;
      }

      function postData(order_id){
          $.post("<?php echo Config::get('site/http').Config::get('site/httpurl').'/home/getOrderInfo'; ?>", { order_id: order_id }, function(response) {
            // Inserts your chosen response into the page in 'response-content' DIV
            $('#response-content').html(response); // Can also use .text(), .append(), etc
          });
      }
      
    });

  </script>

    <?php include Config::get('site/baseurl').Config::get('site/assets').'/botjsincludes.php'; ?>

</body>

</html>