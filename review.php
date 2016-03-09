 <?php

    session_start();

    $db_host = "localhost";
    $db_username = "root";
    $db_pass = "";
    $db_name = "birdee";
        
    $conn = new mysqli($db_host, $db_username, $db_pass, $db_name);
    if($conn->connect_error) {
      die("Connection failed" . $conn->connect_error);
    }
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
      
      
      background: #c3e1ca;
    }

    #map {
      
      height: 50vh;
      max-width: 33vw;
      z-index: 1;
      top: 0;
      left: 0;
      margin: 0;
      padding: 0;
    }

    #directionsMap {
      height: 500px;
      width: 50%;
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
   });

   
  </script>
</head>
<body>
  <?php 

    $id = $_REQUEST["q"];
    $name = $_REQUEST["name"];
    $image = $_REQUEST["image"];
    $displayphone = $_REQUEST["displayphone"];
    $phone = $_REQUEST["phone"];
    $category = $_REQUEST["categories"];
    $rating = "4.5";
    $ratingimg = $_REQUEST["ratingimg"];
    $address = $_REQUEST["address"];
    $latitude = $_REQUEST["latitude"];
    $longitude = $_REQUEST["longitude"];
    $url = $_REQUEST["url"];

    function getUrl() {
      $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
      $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
      $url .= $_SERVER["REQUEST_URI"];
      return $url;
    }

    if(isset($_POST['submit'])) {
      if(!$_POST['review']) {
            die('You have not written a review yet!');
      }

      if(!isset($_SESSION['login_user'])) {
        echo "<script type='text/javascript'>alert('Please write a review before submitting.'); </script>";
      }

      else {
        $date = date("Y-m-d");
        if(!get_magic_quotes_gpc()) {
          $user = addslashes($_SESSION['login_user']);
          $place = addslashes($id);
          $review = addslashes($_POST['review']);
          $date = addslashes($date);
        }

        echo $place;

        $sql = "INSERT INTO reviews (user, place, review, date)
          VALUES ('$user','$place','$review', '$date')";

          if ($conn->query($sql) === TRUE) {
              echo "New record created successfully";
            /*  $currentURL = getUrl();
              header('Location: '.$currentURL);*/

          } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
          }
      }

       $conn->close();
      
    }

          
  ?>
  <?php require "navbar.php"; ?>
    <div class = "green">
    <div class = "white container-fluid">
      <p><a href = "<?php echo $_SERVER['HTTP_REFERER']; ?>">Back to results</a></p>
      <h1><?php echo $name; ?></h1>
      <div class = "container-fluid col-md-8" >
        <h5><?php echo $category; ?></h5>
        <h5><?php echo $address; ?></h5>
        <div class = "divider"></div>
        <h5><a data-toggle = 'modal' data-target = '#directionsModal' id = "directionsLink">Directions</a> | <?php echo $displayphone; ?></h5>


          <div class="modal fade" id="directionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title" id="myModalLabel">Directions</h3>
               </div>
               <div class="modal-body">
                  <div class = "col-md-6" id = "directionsMap">
                  </div>
                  <div class = "col-md-6" id = "directionsRightPanel">
                  </div>
               </div>
               <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
              </div>
             </div>
          </div>


        <div class = "divider"></div>
        <div id = "write">
          <h3>Write a Review</h3>
          <form action = "<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method = "post">
              <fieldset>
                <textarea type = "text" id = "review" name = "review" style = "width: 100%" rows = "5"></textarea>
                <button type = "submit" name = "submit" id = "submit" style = "width: 5em; margin: auto">Submit</button>
              </fieldset>
          </form>
        </div>
        <div id = "reviews">
          <?php
             $sql = "SELECT * FROM reviews WHERE place = '$id'";
             $result = $conn->query($sql);
             $existCount = mysqli_num_rows($result);
                 
             if($existCount != 0) { 
              while($row = mysqli_fetch_assoc($result)) {
                $userResult = $row['user'];
                $dateResult = $row['date'];
                $reviewResult = $row['review'];

                    $picsql = "SELECT * FROM login WHERE username = '" . $userResult . "'";
                    $picresult = mysqli_query($conn, $picsql);
                    /* fetch associative array */
                    $picrow = $picresult->fetch_row();
                    $profResult =  $picrow[2];


                echo "<div class = 'rev'>
                      <h2><img height = '40px' width = '40px' src = '$profResult'><span class = 'reviewbio'>$userResult <small>($dateResult)</small></span></h2>
                      <p>$reviewResult</p>
                    </div>";
              }

             } else {
                echo "<br><br><h5>No reviews yet!</h5>";            
              }

                  unset($db);
          ?>
        </div>
      </div>

      
      <div class = "container-fluid col-md-4" >
        <div id = "map">
        <script>
         var map;
         var directionsDisplay;
         var directionsService;
         var pos;
         var current;
         var directionsMap;
         function initialize() {
           map = new google.maps.Map(document.getElementById('map'), {
             center: {lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?>},
             zoom: 17
           });

           pos = new google.maps.LatLng(<?php echo $latitude;?>,
                <?php echo $longitude;?>);

              var marker=new google.maps.Marker({
                position:pos
              });

              marker.setMap(map); 

           directionsMap = new google.maps.Map(document.getElementById('directionsMap'), {
            center: pos,
            zoom: 17
           });

           directionsDisplay = new google.maps.DirectionsRenderer();
           directionsService = new google.maps.DirectionsService();

           directionsDisplay.setMap(directionsMap);
           directionsDisplay.setPanel(document.getElementById('directionsRightPanel'));

          if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
              current = new google.maps.LatLng(position.coords.latitude,
                position.coords.longitude);
              calcRoute(directionsService, directionsDisplay);
            }, function() {
                handleNoGeolocation(true);
              });
            } else {
              handleNoGeolocation(false);
          }

         }

         google.maps.event.addDomListener(window, 'load', initialize);

        function calcRoute(directionsService, directionsDisplay) {
            setTimeout( function(){ 
               var start = current;
               var end = pos;
           
               var request = {
                 origin:start,
                 destination:end,
                 travelMode: google.maps.TravelMode.DRIVING
               };

               
               directionsService.route(request, function(result, status) {
                 if (status === google.maps.DirectionsStatus.OK) { //3 equals
                   directionsDisplay.setDirections(result);
                   directionsDisplay.setMap(directionsMap);
                 } else {
                   window.alert('Directions request failed due to ' + status);
                 }
               });
             }  , 1000 );
         } 
        </script>
        </div>
      </div>
    </div>
  </div>
</body>
</html>