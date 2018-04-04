<?php  session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Carpooling">
    <meta name="author" content="Team 15">
    <link rel="icon" href="images/favicon.png">

    <title>Carpooling</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/index.css" rel="stylesheet">

  </head>

  <body>

    <!-- Nav Bar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <text class="navbar-brand" href="#">Carpooling</text>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" action="index.php" method="POST">
          <button type="button" class="btn btn-primary navbar-right" onclick="location.href = 'adminPortal.php';">Admin Portal</button>
            <div class="form-group">
              <input type="text" name="username" placeholder="Username" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" name="userpassword" placeholder="Password" class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-success">Sign in</button>
            <button type="button" class="btn btn-primary navbar-right" onclick="location.href = 'signUpUser.php';">Sign up</button>
            <break></break>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Welcome to Carpooling!</h1>
        <p>This is an application for you to bid for your favourite route, or you may post your routes as a driver.</p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Road traffic rules </h2>
          <p>Check out the traffic rules before you can sign up to be a driver. </p>
          <p><a class="btn btn-default" href="https://sso.agc.gov.sg/SL/RTA1961-R20?DocDate=20170630" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Friendly social media</h2>
          <p>We aim to develop a friendly social media platform. </p>
          <p><a class="btn btn-default" href="https://coschedule.com/blog/social-media-friendly/" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Route Planning</h2>
          <p>Help you plan your route.</p>
          <p><a class="btn btn-default" href="http://www.streetdirectory.com/routing/" role="button">View details &raquo;</a></p>
        </div>
      </div>

      <hr>

      <!-- Login Failed Modal -->
      <div id="failModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Login Failed</h4>
            </div>
            <div class="modal-body">
              Username and password mismatch. Try again or sign up as a user.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Try Again</button>
              <button type="button" class="btn btn-primary" onclick="location.href = 'signUpUser.php';">Sign up</button>
            </div>
          </div>
        </div>
      </div>

      <footer>
        <p>&copy; 2018 Company, Inc.</p>
      </footer>
    </div> <!-- /container -->

    <!-- Bootstrap core -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <?php
      // Connect to the database. Please change the password in the following line accordingly
      $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");

      if (isset($_POST['submit'])) {

        $result = pg_query($db, "SELECT icnum, firstName, lastName FROM users where username = '$_POST[username]' and userpassword = '$_POST[userpassword]'");
        $row = pg_fetch_assoc($result);		// To store the result row

        if (!empty($row['icnum'])) {
          $firstname = $row['firstname'];
          $lastname = $row['lastname'];
          $icnum = $row['icnum'];
          $_SESSION['first'] = $firstname;
          $_SESSION['last'] = $lastname;
          $_SESSION['icnum'] = $icnum;
          $_SESSION['user']=$_POST[username];
          echo "<script> window.location.replace('user.php') </script>";
        } else {
          echo "<script type='text/javascript'>$('#failModal').modal('toggle');</script>";
        }
      }
    ?>
  </body>
</html>
