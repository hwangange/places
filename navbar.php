<nav class="navbar navbar-default navbar-fixed-top" style = "margin-bottom: 2cm; background: #6ab47b; border-color: #599a68">
      <ul class = "nav navbar-nav navbar-left">
        <li><h1><a href = "index.php"><b id="site-title" style="display:none">Birdee   </b></a> <small><i id="site-slogan" style="display:none">  travel made easy</i></small></h1></li>
      </ul>
      <ul class = "nav navbar-nav navbar-right loginNav">
      

          <?php
            $db_host = "localhost";
            $db_username = "root";
            $db_pass = "";
            $db_name = "birdee";
          
            $conn = mysqli_connect("$db_host","$db_username","$db_pass","$db_name") or die ("could not connect to mysql");

           
              if(isset($_SESSION['login_user']))
              {
                $username = $_SESSION['login_user'];
                $sql = "SELECT * FROM login WHERE username = '" . $username . "'";
                $result = mysqli_query($conn, $sql);
                /* fetch associative array */
                $row = $result->fetch_row();
                $profilepath =  $row[2];
                
              }
                
             if(isset($_POST['registerSubmit'])) {
              if(!$_POST['username'] | !$_POST['password']) {
                die('Please complete the entire form.');
              }
              if(!get_magic_quotes_gpc()) {
                $_POST['username'] = addslashes($_POST['username']);
              }

              $usercheck = $_POST['username'];
             

              $sql = "SELECT * FROM login WHERE username = '$usercheck'";     
              $existCount = mysqli_num_rows($conn->query($sql));
              if($existCount != 0) { 
                die('Sorry, the username '. $usercheck.' is already in use.');
              }


              $_POST['password'] = md5($_POST['password']);
              if(!get_magic_quotes_gpc()) {
                $_POST['password'] = addslashes($_POST['password']);
                $_POST['username'] = addslashes($_POST['username']);
             
                $password = $_POST['password'];
                $username = $_POST['username'];
              }

              $sql = "INSERT INTO login (username, password, profile)
              VALUES ('$username', '$password', 'img/default.jpg')";

              if ($conn->query($sql) === TRUE) {
                  echo "<p style = 'margin-top: 5cm'>Thank you for registering.</p>";
                  exit;
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }

              $conn->close();


            }

            if(isset($_POST['loginSubmit'])) {
              if(!$_POST['username'] | !$_POST['password']) {
                die('Please complete the entire form.');
              }
              if(!get_magic_quotes_gpc()) {
                $_POST['username'] = addslashes($_POST['username']);
              }

              $username =  $_POST["username"];

              $_POST['password'] = md5($_POST['password']);
              $password = $_POST["password"];



              $sql = "SELECT * FROM login WHERE username ='$username' AND password = '$password'";
              $existCount = mysqli_num_rows($conn->query($sql));
             
              if($existCount == 0) { 
                die('Sorry, the username and password do not match.');
              } else {


                $sql = "SELECT * FROM login WHERE username = '" . $username . "'";
                $result = mysqli_query($conn, $sql);
                /* fetch associative array */
                $row = $result->fetch_row();
                $profilepath =  $row[2];
                $_SESSION['login_user'] = $username;
                
               
                
              }

              unset($db);
          ?>
          <!--<li><h3><small>Welcome </small><a href = "profile.php"> <?php //echo $_SESSION['login_user']; ?></a><a href = "logout.php"><small> Logout</small></a></h3></li> -->
          <li>
            <div class = "btn-group" style = "margin-top: 10px">
            <a class = "btn dropdown-toggle" data-toggle = "dropdown" href = "#">
              <img class = "profilepicture" width = "20px" height = "20px" src = "<?php echo $profilepath; ?>">
              <?php echo $_SESSION['login_user'];?>
              <span class = "caret"></span>
            </a>
            <ul class = "dropdown-menu">
              <li><a href = "profile.php?q='"+<?php echo $_SESSION['login_user'];?>+"'">Profile</a></li>
              <li><a href = "logout.php">Log Out</a></li>
            </ul>
            </div>
          </li>

          <?php 
        }

        else if (!isset($_SESSION['login_user']))
        { ?>
           <li id = "loginNav"><span><button class = "btn btn-primary" data-toggle = 'modal' data-target = '#loginModal'>Login</button> or <a data-toggle = 'modal' data-target = '#registerModal'>Register</a></span></li>
       <?php }

        else {
         ?>
          <!--<li><h3><small>Welcome </small><a href = "profile.php"> <?php //echo $_SESSION['login_user']; ?></a><a href = "logout.php"><small> Logout</small></a></h3></li> -->

          <li>
            <div class = "btn-group" style = "margin-top: 10px">
            <a class = "btn dropdown-toggle" data-toggle = "dropdown" href = "#">
              <img class = "profilepicture" width = "20px" height = "20px" src = "<?php echo $profilepath; ?>">
              <?php echo $_SESSION['login_user'];?>
              <span class = "caret"></span>
            </a>
            <ul class = "dropdown-menu">
              <li><a href = "profile.php?q='"+<?php echo $_SESSION['login_user'];?>+"'">Profile</a></li>
              <li><a href = "logout.php">Log Out</a></li>
            </ul>
            </div>
          </li>

          <?php 
        }
        ?>


        
      </ul>
    </nav>

     <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="myModalLabel">Login</h3>
           </div>
           <div class="modal-body">
              <form id = "loginForm" action = "<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method = "post">
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
              <form id = "loginForm" action = "<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method = "post">
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