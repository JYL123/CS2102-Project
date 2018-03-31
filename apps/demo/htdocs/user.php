<?php  session_start(); ?>
<?php include 'logout.php';?>
<?php
	// Connect to the database. Please change the password in the following line accordingly
  $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");

  if(!isset($_SESSION['user'])) // If session is not set then redirect to Login Page
   {
       header("Location: index.php");
   }

  // //first function - Application of being a driver
  // if (isset($_POST['apply'])) {
  //
  //     echo "<div align='center'> The first step to become a driver, you have to fill in the following information: </div>";
  //
  //     echo
  //     "<div align='center'>
  //     <ul><form name='update' action='user.php' method='POST' >
  //     <li>Vehicle Plate Number:</li>
  // 	<li><input type='text' name='platenum' value='$row[platenum]' /></li>
  // 	<li>Vehicle Model:</li>
  // 	<li><input type='text' name='models' value='$row[models]' /></li>
  //     <li>Number of seats:</li>
  //     <li><input type='text' name='numseats' value='$row[numseats]' /></li>
  //     <li><input type='submit' name='cars'/></li>
  //     </form>
  //     </ul>
  //     </div>";
  // }

  // Insert into cars and drive
  if (isset($_POST['cars'])) {	// Submit the update SQL command
    $result = pg_query($db,
      "INSERT INTO cars (platenum, models, numseats) VALUES ('$_POST[platenum]', '$_POST[models]', '$_POST[numseats]');
      INSERT INTO drive(platenum, icnum) VALUES ('$_POST[platenum]', '$_SESSION[icnum]');"
    );
    if (!$result) {
      $error = pg_last_error($db);
      echo "<script type='text/javascript'>alert('Oops, please try again! " . strstr($error," \"",true). "!');</script>";
    } else {
      echo "Yay, you have successfully become a driver!";
      header("Location: user.php");
    }
  }

  //second function - post an advertisement
  if (isset($_POST['post'])) {

      echo "<div align='center'> The first step to post an advertisement, you have to fill in the following information: </div>";

      echo
      "<div align='center'>
      <ul><form name='update' action='user.php' method='POST' >
      <li>Your icnum:</li>
  	<li><input type='text' name='icnum' value='$row[icnum]' /></li>
      <li>Start location:</li>
  	<li><input type='text' name='origin' value='$row[origin]' /></li>
  	<li>Destination location:</li>
  	<li><input type='text' name='destination' value='$row[destination]' /></li>
      <li>Date of traveling (YYYY-MM-DD):</li>
      <li><input type='text' name='doa' value='$row[doa]' /></li>
      <li><input type='submit' name='ads'/></li>
      </form>
      </ul>
      </div>";
  }
  //Submit add query
  if (isset($_POST['ads'])) {	// Submit the update SQL command
      //checking the velidity of the user:
      $userresult = pg_query($db, "SELECT username FROM users WHERE icnum = '$_POST[icnum]'");// Query template
      if (!$userresult) {
        echo "Oops, please register to be driver to post an ad :)";
      } else {

      //add advertisements
      $result = pg_query($db, "INSERT INTO advertisements (origin, destination, doa) VALUES ('$_POST[origin]', '$_POST[destination]', '$_POST[doa]')");// Query template
      if (!$result) {
        echo "Oops, adding advertisements failed! You can try again.";
      } else {
        echo "Yay, you have successfully post an ad!";
      }

      //retrieve the adid for the last ad just added
      $idresult = pg_query($db, "SELECT adid FROM advertisements ORDER BY adid DESC LIMIT 1");// Query template
      $row    = pg_fetch_assoc($idresult);	// To store the result row
      echo "<li><input type='text' name='bookid_updated' value='$row[adid]'/></li>";

      //add advertisements with icnum into advertise table
      $adresult = pg_query($db, "INSERT INTO advertise (icnum, adid) VALUES ('$_POST[icnum]','$row[adid]')");// Query template
      if (!$adresult) {
          echo "Oops, adding to advertise failed! You can try again.";
      } else {
          echo "Yay, you have successfully linked the ad to the driver!";
      }
   }
  }

  //third function - bid for an ad!
  if (isset($_POST['bid'])) {
      //show all VALID ads
      $result = pg_query($db, "SELECT * FROM advertisements WHERE EXISTS (SELECT 1 FROM advertise WHERE advertisements.adid = advertise.adid)");

      if (!$result) {
          echo "An error occurred.\n";
          exit;
      }

      while ($row = pg_fetch_assoc($result)) {
          echo "<div align='center'>";
          echo $row['adid'];
          echo $row['origin'];
          echo $row['destination'];
          echo $row['doa'];
          echo "</div>";
      }

      //ask users to select an adid to bid
      echo "<div align='center'> The first step to bid, you have to fill in the following information: </div>";

      echo
      "<div align='center'>
      <ul><form name='update' action='user.php' method='POST' >
      <li>Advertisement ID: </li>
  	  <li><input type='text' name='adid' value='$row[adid]' /></li>
  	  <li>Your icnum: </li>
      <li><input type='text' name='icnum' value='$row[icnum]' /></li>
      <li>Your point: </li>
  	  <li><input type='text' name='bidpoints' value='$row[bidpoints]' /></li>
      <li><input type='submit' name='bidad'/></li>
      </form>
      </ul>
      </div>";
  }

  //Submit add query
  if (isset($_POST['bidad'])) {	// Submit the update SQL command
      //check whether the user has bid this ad before; duplication is not allowed
      $userresult = pg_query($db, "SELECT * FROM bid WHERE adid = $_POST[adid] AND icnum = '$_POST[icnum]'");
      $row    = pg_fetch_assoc($userresult);

      if (!$row) {
          // by default, each user can contain bid i point for each ad
          $result = pg_query($db, "INSERT INTO bid VALUES ('$_POST[icnum]', $_POST[adid], '$_POST[bidpoints]')");
           if (!$result) {
               echo "Oops, please try again!";
           } else {
               echo "Yay, you have successfully set a bid point!";
           }
       } else {
          //duplication for bidding an ad is not allowed.
          echo "$row[adid]";
          echo "You have already bid for this ad. You can bid for a new ad.";
       }

  }
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

    <title>15 Carpooling</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/user.css" rel="stylesheet">

  </head>

<body>

  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Carpooling</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav" role="tablist">
          <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
          <li role="presentation"><a href="#post" aria-controls="post" role="tab" data-toggle="tab">Post Ad</a></li>
          <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Bid Ad</a></li>
          <li role="presentation"><a href="#drive" aria-controls="drive" role="tab" data-toggle="tab">Drive</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a><?php echo $_SESSION['first'] . " " . $_SESSION['last'];?></a><li>
          <form name="display" action="logout.php" method="POST" >
            <li><button type="submit" name="logout" class="btn btn-danger">Logout</button></li>
          </form>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>

  <div class="container">

    <div class="starter-template">
      <h1>Bootstrap starter template</h1>
      <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
    </div>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="home">Show user profile</div>
      <div role="tabpanel" class="tab-pane" id="post">
        <div align='center'><h4>The first step to post an advertisement, you have to fill in the following information: </h4></div>";

        <div align='center'>
          <ul>
            <form class="form" action="user.php" method="POST">
              <h2 class="form-heading">Post a advertisement</h2>
              <input type="text" name="origin" class="form-control" placeholder="Origin" required autofocus>
              <input type="text" name="destination" class="form-control" placeholder="Destination" required>
              <div class='input-group date' id='datetimepicker3'>
                <input type='text' class="form-control" />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-time"></span>
                </span>
              </div>
              <input type="text" name="doa" class="form-control" placeholder="Number of Seats" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="cars">Apply</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'user.php';" >Back</button>
            </form>
          </ul>
        </div>



        <div align='center'>
        <ul><form name='update' action='user.php' method='POST' >
        <li>Your icnum:</li>
    	<li><input type='text' name='icnum' value='$row[icnum]' /></li>
        <li>Start location:</li>
    	<li><input type='text' name='origin' value='$row[origin]' /></li>
    	<li>Destination location:</li>
    	<li><input type='text' name='destination' value='$row[destination]' /></li>
        <li>Date of traveling (YYYY-MM-DD):</li>
        <li><input type='text' name='doa' value='$row[doa]' /></li>
        <li><input type='submit' name='ads'/></li>
        </form>
        </ul>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="messages">Show message</div>
      <div role="tabpanel" class="tab-pane" id="drive">
        <div align='center'> <h4>The first step to become a driver, you have to fill in the following information: </h4> </div>";
        <div align='center'>
          <ul>
            <form class="form" action="user.php" method="POST">
              <h2 class="form-heading">Apply to become a driver</h2>
              <input type="text" name="platenum" class="form-control" placeholder="Vehicle Plate Number" required autofocus>
              <input type="text" name="models" class="form-control" placeholder="Vehicle Model" required>
              <input type="number" name="numseats" class="form-control" placeholder="Number of Seats" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="cars">Apply</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'user.php';" >Back</button>
            </form>
          </ul>
        </div>
      </div>
    </div>

  <div align='center'>
    <ul>
      <form name="display" action="user.php" method="POST" >
        <li>As an user, you can perform:</li>
        <li><input type="submit" name="apply" value="Apply to be a driver (add car in database)" /></li>
        <li><input type="submit" name="post" value="Post an advertisement" /></li>
        <li><input type="submit" name="bid" value="Bid for an advertisement" /></li>
      </form>
    </ul>
  </div>

  <!-- Bootstrap core -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(function () {
        $('#datetimepicker3').datetimepicker({
          format: 'LT'
        });
    });
  </script>

</body>
</html>
