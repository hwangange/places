 <?php
    $category = $_REQUEST["category"];
    $location = $_REQUEST["location"];
    session_start();
  ?>

<!DOCTYPE html>
<html>
<head>
  <title>Birdee</title>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Biryani:400,800,900|Nunito:400,700' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="css/extra.css" rel="stylesheet" media="screen">
  <script type="text/javascript" src="http://oauth.googlecode.com/svn/code/javascript/oauth.js"></script>
  <script type="text/javascript" src="http://oauth.googlecode.com/svn/code/javascript/sha1.js"></script>
  <style>
  html, body {
    height: 100%;
    padding: 0;
    margin: 0;

  }

  #map-canvas {
    height: 100vh;
    width: 50vw;
    max-width: 50vw;
    z-index: 1;
    top: 0;
    left: 0;
    margin: 0;
    padding: 0;
  }


  .center {
    margin: auto;
    width: 100%;
  }

  </style>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
  <script src="js/bootstrap.min.js" type="text/javascript"></script>
  <script>
    $(document).ready(function() {
     $("#site-title").fadeIn(2000);
     $("#site-slogan").fadeIn(3000);

    document.getElementById('place-type').innerHTML = "&ldquo;" + <?php echo "'".$category."'";?> + "&ldquo;";
    document.getElementById('place-name').innerHTML = "&ldquo;" + "Sugar Land" + "&ldquo;";
      
    $('#result-list').fadeIn();

 
    });
  </script>
  <script>
    var resultsjson;
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
          authenticate(<?php echo "'".$category."'"; ?>);
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

    function authenticate(term) {
      var url = "http://api.yelp.com/v2/search";
      var method = "GET";
      var accessor = {
        token: "RBEZ5dknjTvj40dKDoTAkhdPQSp2oi51",
        tokenSecret: "LgyHnOuliXVr2mte1Y9p1dmtF0I",
        consumerKey : "2kwa_4JCIn9-rK4iiJ6_VA",
        consumerSecret: "rL_R0BqJJVBd9BK83obKFWy6GIc"
      };

      var terms = term;
      var near = map.getCenter().lat()+','+map.getCenter().lng();


      parameters = [];
      parameters.push(['term', terms]);
      parameters.push(['ll', near]);
      parameters.push(['callback', 'cb']);
      parameters.push(['oauth_consumer_key', accessor.consumerKey]);
      parameters.push(['oauth_consumer_secret', accessor.consumerSecret]);
      parameters.push(['oauth_token', accessor.token]);
      parameters.push(['oauth_signature_method', 'HMAC-SHA1']);
      var message = {
        'action':'http://api.yelp.com/v2/search',
        'method': 'GET',
        'parameters': parameters
      }

      OAuth.setTimestampAndNonce(message);
      OAuth.SignatureMethod.sign(message, accessor); 
      var parameterMap = OAuth.getParameterMap(message.parameters);
      parameterMap.oauth_signature = OAuth.percentEncode(parameterMap.oauth_signature)
      console.log(parameterMap);

      //clear json file
      <?php
        $file = fopen("resultsjson.txt", "w") or die("Unable to open file");
        fwrite($file, "");
        fclose($file);
      ?>

      $.ajax({
        'url' : message.action,
        'data' : parameterMap, 
        'cache' : true,
        'dataType' : 'jsonp',
        'jsonpCallback' : 'cb',
        'success' : function(data, textStats, XMLHttpRequest) {
          console.log(data);
        
             $.each(data.businesses, function(i,item){
                infowindowcontent = '<strong>'+item.name+'</strong><br>';
                infowindowcontent += '<img src="'+item.image_url+'"><br>';
                infowindowcontent += '<a href="'+item.url+'" target="_blank">see it on yelp</a>';
                  
                createYelpMarker(i,item.location.coordinate.latitude,item.location.coordinate.longitude,item.name, infowindowcontent);
                //addData(item);
                addInfo(item);
              });  
              
        }
      })
    }

    function createYelpMarker(i,latitude,longitude,title, infowindowcontent) {
        var markerLatLng = new google.maps.LatLng(latitude,longitude);  
        
        //extent bounds for each stop and adjust map to fit to it
        bounds.extend(markerLatLng);
        map.fitBounds(bounds);

        yelp[i] = new google.maps.Marker({
            position: markerLatLng,
            map: map,
            title: title,
            icon: 'http://yohman.bol.ucla.edu/images/yelp.png'
        });

        yelp[i].setMap(map);

        //add an onclick event
        google.maps.event.addListener(yelp[i], 'click', function() {
            infowindow_yelp.setContent(infowindowcontent);
            infowindow_yelp.open(map,yelp[i]);

            map.panTo(yelp[i].position);

            dest = marker.position; 
            destPlace = place;
        });

    }

    function addInfo(item) {

          var temp = document.getElementById('result-list').innerHTML;
          var tempItem = item;
          

          var html = 
          "<div class = 'result'>" +
            "<h2>" + item.name + "</h2>" +
            "<p>" + item.categories[0][0] + "</p>" +
            "<p>" + item.location.address + "</p>" +
            "<p>" + item.snippet_text + "</p>" +
            "<form action = '' method = 'GET'>" +
              "<a href = 'review.php?q=" + item.id + "&name=" + item.name + "&image=" + item.image_url + "&displayphone=" + item.display_phone + "&phone=" + item.phone + "&categories=" + item.categories[0][0] + "&rating=" + item.rating + "&ratingimg=" + item.rating_img_url + "&address=" + item.location.address + "&latitude=" + item.location.coordinate.latitude + "&longitude=" + item.location.coordinate.longitude + "&url=" + item.url + "'><input value = 'View' class = 'btn btn-success btn-sm'></a>" +
            "</form>" +
           "</div>" + 
           "<div class = 'divider'></div>" ;

          document.getElementById('result-list').innerHTML = temp + html;
          
    }

    function review(temp) {
      if(window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
      } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.open("GET","review.php?q=" + temp, true);
      xmlhttp.send();
    }

     google.maps.event.addDomListener(window, 'load', initialize);

  </script>
</head>
<body>
  <h2>Search results: </h2>

  <script>
    var map;
    var pos;
    var infowindow;
    var dest;
    var destPlace;
    function loadScript() {
        var script = document.createElement("script");
        script.src = "http://maps.googleapis.com/maps/api/js?callback=initialize";
        document.body.appendChild(script);
      }

      // window.onload = loadScript;
   </script>
   <?php require "navbar.php"; ?>
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

