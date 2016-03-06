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
      padding-left: 15px;
      padding-right: 2.5%;
      margin: 0;
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

        $sql = "INSERT INTO reviews (user, place, review, date)
          VALUES ('$user','$place','$review', '$date')";

          if ($conn->query($sql) === TRUE) {
              echo "New record created successfully";
          } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
          }
      }

       $conn->close();
      
    }

          
  ?>
  <?php require "navbar.php"; ?>
  <h1 style = "padding-top: 2cm"><?php echo $name; ?></h1>
  <div class = "container-fluid col-md-8" >
    <h5><?php echo $category; ?></h5>
    <h5><?php echo $address; ?></h5>
    <div class = "divider"></div>
    <h5><a>Directions</a> | <?php echo $displayphone; ?></h5>
    <div class = "divider"></div>
    <div id = "write">
      <h3>Write a Review</h3>
      <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
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
            echo "<div>
                  <h2>$userResult <small>($dateResult)</small></h2>
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
     }

     google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    </div>
  </div>
</body>
</html>