<?php
  session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <?php require "head.php"; ?>
  
  <script>


  $(document).ready(function(){
   
    $("#site-title").fadeIn(2000);
    $("#site-slogan").fadeIn(3000);
  });
 
  var map;
  var pos;
  var infowindow;
  var service;
  //var directionsEnd = '4200 Elkins Rd';
  var directionsDisplay;
  var directionsService;

  //declaire namespace
  var yoh = {};

  var yelp = [];
  var bounds;
  var infowindow_yelp = new google.maps.InfoWindow();

  function trace(message) { /*trace function for debugging*/
    if(typeof console != 'undefined')
    {
      console.log(message);
    }
  }

  function initialize() {
    //directionsDisplay = new google.maps.DirectionsRenderer;
    bounds = new google.maps.LatLngBounds();

    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsService = new google.maps.DirectionsService();
    var mapOptions = {
      zoom: 15
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);

    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById('right-panel'));

    // Try HTML5 geolocation
    if(navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        pos = new google.maps.LatLng(position.coords.latitude,
          position.coords.longitude);

        map.setCenter(pos);
        var marker=new google.maps.Marker({
          position:pos,
          icon: {
            // Star
            path: 'M 0,-24 6,-7 24,-7 10,4 15,21 0,11 -15,21 -10,4 -24,-7 -6,-7 z',
            fillColor: '#ffff00',
            fillOpacity: 1,
            scale: 1/4,
            strokeColor: '#bd8d2c',
            strokeWeight: 1
          }
        });

        marker.setMap(map); //directionsDisplay.setMap(map)??
      }, function() {
        handleNoGeolocation(true);
      });
    } else {
      // Browser doesn't support Geolocation
      handleNoGeolocation(false);
    }
  }

  function handleNoGeolocation(errorFlag) {
    if (errorFlag) {
      var content = 'Error: The Geolocation service failed.';
    } else {
      var content = 'Error: Your browser doesn\'t support geolocation.';
    }

    var options = {
      map: map,
      position: new google.maps.LatLng(60, 105),
      content: content
    };

    var infowindow = new google.maps.InfoWindow(options);
    map.setCenter(options.position);
  }

  google.maps.event.addDomListener(window, 'load', initialize);

  </script>
</head>

<body bgcolor="#accbe8" onload = "initialize()">
    <?php require "navbar.php"; ?>

     <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="myModalLabel">Login</h3>
           </div>
           <div class="modal-body">
              <form id = "loginForm" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method = "post">
                <fieldset>
                  <div align = "center" style = "margin-bottom: 5px"> Username: <input type = "text" id = "username" name = "username" /> </div>
                  <div align = "center" style = "margin-bottom: 5px"> Password: <input type = "password" id = "password" name = "password" /> </div>
                </fieldset>
              <!--</form> -->
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button class = "btn btn-primary" type = "submit" name = "loginSubmit" id = "loginSubmit">Login</button>
           </div>
            </form>
          </div>
         </div>
      </div>

      <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="myModalLabel">Register</h3>
           </div>
           <div class="modal-body">
              <form id = "loginForm" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method = "post">
                <fieldset>
                  <div align = "center" style = "margin-bottom: 5px"> Username: <input type = "text" id = "username" name = "username" /> </div>
                  <div align = "center" style = "margin-bottom: 5px"> Password: <input type = "password" id = "password" name = "password" /> </div>
                </fieldset>
              
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button class = "btn btn-primary" type = "submit" name = "registerSubmit" id = "registerSubmit">Register</button>
           </div>
            </form>
          </div>
         </div>
      </div>

    <div class = "alert alert-danger fade in" id="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Sorry!</strong> No results were returned from your search.
    </div>

  <div id = "main" class = "container-fluid">
    <!--<h1>Birdee</h1> -->
    <h2>Birdee</h2>
    <form action = "search.php" method = "get">
      <fieldset>
        <span class="dropdown center" style="display:inline">
          <select name="category" id="selected">
            <option selected = "selected">I'm looking for ... </option>
            <option value="Lodging" label = "Lodging">Lodging</option>
            <option value="Food" label = "Food">Food</option>
            <option value="Airport" label = "Airport">Airport</option>
            <option value="Hotel" label = "Hotel">Hotel</option>
            <option value="ATM" label = "ATM">ATM</option>
            <option value="Bank" label = "Bank">Bank</option>
            <option value="Bus Station" label = "Bus Station">Bus Station</option>
            <option value="Taxi" label = "Taxi">Taxi</option>
            <option value="Train" label = "Train">Train</option>
            <option value="Cafe" label = "Cafe">Cafe</option>
            <option value="Gas Station" label = "Gas Station">Gas Station</option>
            <option value="Clothing" label = "Clothing">Clothing</option>
          </select>
        </span>
        <span class="dropdown center" style="display:inline">

          <input name = "location" type = "text" id = "current">
          <h3 style="display:inline"><small>.</small></h3>
          <input type="submit" id="myBtn" value="Search" class="btn btn-success btn-sm" style = "margin-left: 1em">
          <p id="demo"></p>
        </span>
      </fieldset>
    </form>
    
    <br>
  
    </div>

    <script>


      function loadScript() {
        var script = document.createElement("script");
        script.src = "http://maps.googleapis.com/maps/api/js?callback=initialize";
        document.body.appendChild(script);
      }

      // window.onload = loadScript;
    </script>
      <div class = "container-fluid">
        <div id = "mask"></div>
        <div id="right-panel"></div>
        <div id = "result-list" class = "container-fluid" style = "overflow: scroll">
          <h3>Results for <span id = "place-type"></span> in <span id = "place-name"></span></h3>
          <!--<h1>Suggestions for </h1><span id = "selection"></span><h1> near </h1><span id = "location-selection"></span> -->
        </div>
        <div id="map-canvas" class = "container-fluid"></div>

      </div>
      <div class="container" id="review-page"></div>
      <footer class = "footer" style = "background: #6ab47b">
        <div class = "container">
      </footer>
  </body>
  </html>
