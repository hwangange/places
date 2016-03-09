<?php

    session_start();
    $user = $_SESSION['login_user'];

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
    <?php require "head.php"; ?>
    <script>
        $(document).ready(function() {
         $("#site-title").fadeIn(2000);
         $("#site-slogan").fadeIn(3000); 
       });  
    </script>
</head>
<body>
    <?php require "navbar.php"; ?>
    <style>
         html, body {
          height: 100%;
          padding: 0;
          background: #c3e1ca;
        }
    </style>
    <div class = "white container-fluid">
        <div class = "container-fluid col-md-6">
            <h1 style = "text-align: center"><?php echo $user; ?></h1>
            <img width = "200px" height = "200px" src = "<?php echo $profilepath;?>" style = "margin-left: auto; margin-right: auto; display: block">
        </div>
        <div class = "container-fluid col-md-6">
            <form action "<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method = "post" enctype = "multipart/form-data">
                <h5>Select image to upload:</h5>
                <input class = "btn btn-primary" type = "file" name = "fileToUpload" id = "fileToUpload">
                <br>
                <input class = "btn btn-success" type = "submit" value = "Set as Profile" name = "upload">

            </form>
            <div style = "visibility: none;" class = "imageholder"></div>
        </div>
    </div>

     <script>
        function jupload(result)
        {
            if(result==0)
            {
                $(".imageholder").html("");
            }
            else if(result!=0)
            {
                $(".imageholder").html("");
                //imageplace is the class of the div where you want to add the image
                $(".imageholder").append("<img width = '100px' height = '100px' src = '"+result+"'>");
                $(".imageholder").css("visibility","visible");
                $("#setprofile").css("visibility", "visible");
            }
        }
    </script>
    <?php 
        if(isset($_POST["upload"])) {
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            //Check if image file is a actual image or fake
        
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {

                if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file))
                {
                    //echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                    $ext = findexts($target_file);
                    $new_target_file = "img/".$user."_profile".".".$ext;
                    rename($target_file, $new_target_file);
                    //echo "New image name - " . basename($new_target_file);
                    $result = $new_target_file;

                    $sql = "UPDATE login SET profile='$new_target_file' WHERE username='$user'";
                    if ($conn->query($sql) === TRUE) {
                          echo"<script>setProf();</script>";
                         
                      } else {
                          echo "Error: " . $sql . "<br>" . $conn->error;
                      }

                      $conn->close();
                    //$result = $target_file;
                }
            } else {
                echo "File is not an image";
                $uploadOk = 0;
                $result = 0;
            }
        }

        function findexts ($filename) {
            $filename = strtolower($filename);
            $exts = split("[/\\.]", $filename);
            $n = count($exts) - 1;
            $exts = $exts[$n];
            return $exts;
        }


    ?>

   <!-- <script language = "javascript" type = "text/javascript">
        window.top.window.jupload("<?php //echo $result; ?>");
    </script> -->
    <script>
        function updateProf() {
            $(".profile").append("<img height = '10px' width = '10px' src = '"+<?php echo $result; ?>+"'>");
        }
    </script>
</body>
</html>