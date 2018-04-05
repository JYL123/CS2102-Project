<?php  session_start(); ?>
<?php include 'logout.php';?>

<?php
	// Connect to the database. Please change the password in the following line accordingly
  $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");

  if(!isset($_SESSION['user'])) // If session is not set then redirect to Login Page
   {
       header("Location: index.php");
   }

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

  if (isset($_POST['ads'])) {
      //add advertisements
      $result = pg_query($db, "INSERT INTO advertisements (origin, destination, doa) VALUES ('$_POST[origin]', '$_POST[destination]', '$_POST[doa]')");// Query template
      //show error
      if (!$result) {
        echo "<p align='center'>Oops, adding advertisements failed! You can try again.</p>";
      } else {
        //nothing;
      }

      //retrieve the adid for the last ad just added
      $idresult = pg_query($db, "SELECT adid FROM advertisements ORDER BY adid DESC LIMIT 1");// Query template
      $row    = pg_fetch_assoc($idresult);	// To store the result row
      $adid = $row[adid];
      //echo "<li><input type='text' name='bookid_updated' value='$row[adid]'/></li>";

      //add advertisements with icnum into advertise table
      $adresult = pg_query($db, "INSERT INTO advertise (icnum, adid) VALUES ('$_SESSION[icnum]', $adid)");// Query template
      if (!$adresult) {
          echo "<p align='center'>Oops, adding to advertise failed! You can try again.</p>";
      } else {
         //nothing
      }
    }

  //third function - bid for an ad!
  if (isset($_POST['bid'])) {
      //show all VALID ads
      $result = pg_query($db, "SELECT * FROM advertisements WHERE EXISTS (SELECT 1 FROM advertise WHERE advertisements.adid = advertise.adid)");

      if (!$result) {
        echo '<script language="javascript">';
        echo 'alert("Oops, an error has occured! You can try again!")';
        echo '</script>';
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
               echo '<script language="javascript">';
               echo 'alert("Oops, please try again!")';
               echo '</script>';
           } else {
              echo '<script language="javascript">';
              echo 'alert("Yay, you have successfully set a bid point!")';
              echo '</script>';
           }
       } else {
          //duplication for bidding an ad is not allowed.
          echo '<script language="javascript">';
          echo 'alert("You have already bid for this ad. You can bid for a new ad.")';
          echo '</script>';
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
    <link rel="icon" href="images/favicon.png" type="image/png">

    <title>Carpooling</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Carpooling</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav" role="tablist">
          <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
          <li role="presentation"><a href="#post" aria-controls="post" role="tab" data-toggle="tab">Post Ad</a></li>
          <li role="presentation"><a href="#selectBidder" aria-controls="selectBidder" role="tab" data-toggle="tab">Select Bidder</a></li>
          <li role="presentation"><a href="#bid" aria-controls="bid" role="tab" data-toggle="tab">Bid Ad</a></li>
          <li role="presentation"><a href="#drive" aria-controls="drive" role="tab" data-toggle="tab">Apply Driver</a></li>
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
      <h1>Welcome back!</h1>
       <!--<p class="lead">The following is your information.<br> Hope it is useful to you.</p>-->
    </div>

    <div class="tab-content">

      <!-- Home Summary page-->

      <div role="tabpanel" class="tab-pane active" id="home">

        <div class="container">
          <div class="row">
            <div class="col-md-5  toppad  pull-right col-md-offset-3 ">
            </div>
            <div class="toppad" >
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo $_SESSION['first'] . " " . $_SESSION['last'];?></h3>
                </div>
                <div class="panel-body">
                <div class="row">
                  <div class=" col-md-9 col-lg-9 ">
                    <table class="table table-user-information">
                      <tbody>
                        <!-- Name -->
                        <tr>
                          <td>Name</td>
                          <td><?php echo $_SESSION['first'] . " " . $_SESSION['last'];?></td>
                        </tr>
                        <!-- Email and phone -->
                        <?php
                          //retrieve basic information about the user
                          $sql = "SELECT * FROM users WHERE icnum = '$_SESSION[icnum]'";
                          $result = pg_query($db, $sql);// Query template
                          //show error
                          if (!$result) {
                            echo '<script language="javascript">';
                            echo 'alert("Oops, please try again!")';
                            echo '</script>';
                          } else {
                            //nothing
                          }
                          //display retrieved information
                          while ($row = pg_fetch_assoc($result)) {
                            $email = $row['email'];
                            echo "<tr>";
                            echo "<td>Email</td>";
                            echo "<td><a href=\"mailto:" + $email+ "\">"+ $email +"</></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>Phone</td>";
                            echo "<td>" . $row['phonenum'] . "</td>";
                            echo "</tr>";
                          }
                        ?>
                        <!-- Cars -->
                        <tr>
                          <td>Cars</td>
                          <td>
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Plate Number</th>
                                  <th>Model</th>
                                  <th>Seats</th>
                                </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    $result = pg_query($db, "SELECT * FROM cars WHERE plateNum IN (SELECT plateNum FROM drive WHERE icnum = '$_SESSION[icnum]')");
                                    if (!$result) {
                                      echo "An error occurred.\n";
                                      exit;
                                    }
                                    $firstRow = pg_fetch_assoc($result);
                                    if (!$firstRow) {
                                      echo "<div align='center'>The user is not a driver</div>";
                                    }
                                    else {
                                      echo "<tr>";
                                      echo "<th>" . $firstRow['platenum'] . "</th>";
                                      echo "<th>" . $firstRow['models'] . "</th>";
                                      echo "<th>" . $firstRow['numseats'] . "</th>";
                                      echo "</tr>";
                                    }

                                    while ($row = pg_fetch_assoc($result)) {
                                      echo "<tr>";
                                      echo "<th>" . $row['platenum'] . "</th>";
                                      echo "<th>" . $row['models'] . "</th>";
                                      echo "<th>" . $row['numseats'] . "</th>";
                                      echo "</tr>";
                                    }
                                  ?>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        <!-- Advertisements -->
                        <tr>
                          <td>Advertisements</td>
                          <td>
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Ad ID</th>
                                  <th>Origin</th>
                                  <th>Destination</th>
                                  <th>Time</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  //retrieve ad posting information about the user
                                  $sql = "SELECT DISTINCT uaa.adid, uaa.origin, uaa.destination, uaa.doa
                                          FROM ((users u natural left join advertise a ) natural join advertisements) as uaa
                                          WHERE uaa.icnum = '$_SESSION[icnum]'";
                                  $result = pg_query($db, $sql);// Query template
                                  //show error
                                  if (!$result) {
                                   echo '<script language="javascript">';
                                   echo 'alert("Oops, please try again!")';
                                   echo '</script>';
                                  }

                                  //display retrieved ad posting information
                                  while ($row = pg_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<th>" . $row['adid'] . "</th>";
                                    echo "<th>" . $row['origin'] . "</th>";
                                    echo "<th>" . $row['destination'] . "</th>";
                                    echo "<th>" . $row['doa'] . "</th>";
                                    echo "</tr>";
                                  }
                                ?>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        <!-- Bids -->
                        <tr>
                          <td>My Bids</td>
                          <td>
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Origin</th>
                                  <th>Destination</th>
                                  <th>Time</th>
                                  <th>Points</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  //retrieve ad posting information about the user
                                  $sql = "SELECT origin, destination, doa, bidpoints, status
                                          FROM bid, advertisements a
                                          WHERE bid.adid = a.adid
                                          AND bid.icnum = '$_SESSION[icnum]'";
                                  $result = pg_query($db, $sql);// Query template
                                  //show error
                                  if (!$result) {
                                   echo '<script language="javascript">';
                                   echo 'alert("Oops, please try again!")';
                                   echo '</script>';
                                  }

                                  //display retrieved ad posting information
                                  while ($row = pg_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<th>" . $row['origin'] . "</th>";
                                    echo "<th>" . $row['destination'] . "</th>";
                                    echo "<th>" . $row['doa'] . "</th>";
                                    echo "<th>" . $row['bidpoints'] . "</th>";
                                    echo "<th>" . $row['status'] . "</th>";
                                    echo "</tr>";
                                  }
                                ?>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <a href="#" class="btn btn-primary">Back To Top</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


      <!-- Post advertisement -->
      <div role="tabpanel" class="tab-pane" id="post">
        <div align='center'>
        </div>

        <div align='center'>
          <ul>
            <form class="form" action="user.php" method="POST">
              <h2 class="form-heading">Post an advertisement</h2>
              <input type="text" name="origin" class="form-control" placeholder="Origin" required autofocus>
              <input type="text" name="destination" class="form-control" placeholder="Destination" required>
              <input  type="datetime-local" name="doa" class="form-control" placeholder="Date of traveling (YYYY-MM-DD)" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="ads">Apply</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'user.php';" >Back</button>
            </form>
          </ul>
        </div>

        </div>

      <!-- Select a bidder -->
      <div role="tabpanel" class="tab-pane" id="selectBidder">
        <table class="table table-user-information">
          <thead>
            <tr>
              <th>Ad ID</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Time</th>
              <th>Points</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
              //retrieve ad posting information about the user
              $sql = "SELECT b.adid, a.origin, a.destination, a.doa, at.icnum, bidpoints, status
                      FROM bid b, advertisements a, advertise at
                      WHERE status = 'Not Selected' AND b.adid = a.adid AND b.adid = at.adid AND at.icnum = '$_SESSION[icnum]'
                      ORDER BY b.adid";
              $result = pg_query($db, $sql);// Query template
              //show error
              if (!$result) {
               echo '<script language="javascript">';
               echo 'alert("Oops, please try again!")';
               echo '</script>';
              }

              //display retrieved ad posting information
              while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<th>" . $row['adid'] . "</th>";
                echo "<th>" . $row['origin'] . "</th>";
                echo "<th>" . $row['destination'] . "</th>";
                echo "<th>" . $row['doa'] . "</th>";
                echo "<th>" . $row['bidpoints'] . "</th>";
                echo "<th>" . $row['status'] . "</th>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
      <div align='center'>
          <ul>
            <form class="form" action="user.php" method="POST">
              <h2 class="form-heading">Select a bidder</h2>
              <input type="text" name="adid" class="form-control" placeholder="Ad index" required autofocus>
              <input type="text" name="icnum" class="form-control" placeholder="Bidder IC" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="select">Bid</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'user.php';" >Back</button>
            </form>
          </ul>
      </div>

      <?PHP
        if (isset($_POST['select'])) {	// Submit the update SQL command
          //check whether the user has bid this ad before; duplication is not allowed
          $sql = "UPDATE bid
                  SET status = 'Selected'
                  WHERE icnum = '$_POST[icnum]' and adid = $_POST[adid]";
          $result = pg_query($db, $sql);

          if (!$result) {
            echo '<script language="javascript">';
            echo 'alert("Oops, please try again!")';
            echo '</script>';
            exit;
          } else {
            echo '<script language="javascript">';
            echo 'alert("You have choosen a bidder!")';
            echo '</script>';
            //echo "<h2 align='center'>You have choosen a bidder! </h2>";
          }
        }
      ?>

    </div>

     <!-- Apply to be a driver -->
      <div role="tabpanel" class="tab-pane" id="drive">
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

      <!-- Bid an advetisement-->
      <div role="tabpanel" class="tab-pane" id="bid">
        <table class="table">
          <thead>
            <tr>
              <th>Ad Id</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Time</th>
              <th>Highest Bid</th>
              <th>Your Bid</th>
            </tr>
          </thead>
          <tbody>
            <?php
              //retrieve ad posting information about the user
              $sql = "SELECT * ,
                      (SELECT max(bidpoints) AS maxBid FROM bid GROUP BY adid HAVING adid = a.adid),
                      (SELECT bidpoints AS yourBid FROM bid WHERE icnum='$_SESSION[icnum]' AND adid = a.adid)
                      FROM advertisements a
                      WHERE NOT EXISTS (
                      	SELECT 1 FROM bid b
                      	WHERE b.adid = a.adid
                      	AND b.status = 'Selected')";
              $result = pg_query($db, $sql);// Query template
              //show error
              if (!$result) {
               echo '<script language="javascript">';
               echo 'alert("Oops, please try again!")';
               echo '</script>';
              }

              //display retrieved ad posting information
              while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<th>" . $row['adid'] . "</th>";
                echo "<th>" . $row['origin'] . "</th>";
                echo "<th>" . $row['destination'] . "</th>";
                echo "<th>" . $row['doa'] . "</th>";
                echo "<th>" . $row['maxbid'] . "</th>";
                echo "<th>" . $row['yourbid'] . "</th>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
        <div align='center'>
          <ul>
            <form class="form" action="user.php" method="POST">
              <h2 class="form-heading">Bid an advertisement</h2>
              <input type="text" name="adid" class="form-control" placeholder="AD ID" required autofocus>
              <input type="text" name="bidpoints" class="form-control" placeholder="Place Your Bid Points" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="bidads">Bid</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'user.php';" >Back</button>
            </form>
          </ul>
        </div>
        <?php
        if (isset($_POST['bidads'])) {
         //check whether the user has bid this ad before; duplication is not allowed
          $userresult = pg_query($db, "SELECT * FROM bid WHERE adid = $_POST[adid] AND icnum = '$_SESSION[icnum]'");
          $row    = pg_fetch_assoc($userresult);

          if (empty($row)) {
              // by default, each user can contain bid i point for each ad
              $result = pg_query($db, "INSERT INTO bid VALUES ('$_SESSION[icnum]', $_POST[adid], '$_POST[bidpoints]')");
              if (!$result) {
                  echo '<script language="javascript">';
                  echo 'alert("Oops, please try again!")';
                  echo '</script>';
              } else {
                  echo '<script language="javascript">';
                  echo 'alert("Yay, you have successfully set a bid point!")';
                  echo '</script>';
              }
          } else {
            //duplication for bidding an ad is not allowed.
            //so update if a record already exists.
            $sql = "UPDATE bid
                    SET bidpoints = '$_POST[bidpoints]'
                    WHERE icnum = '$_SESSION[icnum]'
                    AND adid = '$_POST[adid]'";

            $result = pg_query($db, $sql);
            if (!$result) {
              echo "An error occurred.\n";
              exit;
            } else {
              echo '<script language="javascript">';
              echo 'alert("Yay, you have successfully updated your bidpoint!")';
              echo '</script>';
            }
          }
        }
        ?>
      </div>
    </div>


  <!-- Bootstrap core -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

</body>
</html>
