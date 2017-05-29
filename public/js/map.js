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