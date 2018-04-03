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

    <title>Admin Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signUpUser.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="signUpUser.php" method="POST">
        <h2 class="form-signin-heading">Sign Up</h2>
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="userpassword" class="form-control" placeholder="Password" required>
        <input type="text" name="icnum" class="form-control" placeholder="IC Number" required>
        <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
        <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
        <input type="email" name="email" class="form-control" placeholder="Email address" required>
        <input type="number" name="phonenum" class="form-control" placeholder="Phone Number" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign up</button>
        <button class="btn btn-lg btn-block" onclick="location.href = 'adminPortal.php';" >Back</button>
      </form>

      <!-- Sign Up Failed Modal -->
      <div id="failModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Sign Up Failed</h4>
            </div>
            <div class="modal-body">
              Sorry, your sign up has failed. But you can try again! See error at the end of the page. 
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Try Again</button>
            </div>
          </div>
        </div>
    </div> <!-- /container -->

    <!-- Bootstrap core -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <?php
    	// Connect to the database. Please change the password in the following line accordingly
      $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");

      if (isset($_POST['submit'])) {

      $result = pg_query($db, "INSERT INTO administrators (username, userpassword, icnum, firstname, lastname, email, phonenum) VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]',
      '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]')");		// Query template

        if (!$result) {
          $error = pg_last_error($db);
          echo "<div align='center'> $error </div>";
          echo "<script type='text/javascript'>$('#failModal').modal('toggle');</script>";
        } else {
          echo "You have successfully signed up! You can log in now, yay!";
          echo "<script> window.location.replace('adminPortal.php') </script>";
        }
      }
    ?>
  </body>
</html>
