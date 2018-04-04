<?php  session_start(); 
?>

<?php
	// Connect to the database. Please change the password in the following line accordingly
  $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");

  if(!isset($_SESSION['user'])) // If session is not set then redirect to Login Page
   {
       header("Location: adminPortal.php");
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
        //echo "<p align='center'>Yay, you have successfully post an ad!</p>";
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
         // echo "<p align='center'>Yay, you have successfully linked the ad to the driver!</p>";
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

    <title>Carpooling</title>

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
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Carpooling</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav" role="tablist">
          <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
          <li role="presentation"><a href="#delete" aria-controls="delete" role="tab" data-toggle="tab">Delete users</a></li>
          <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">View ad bidpoints</a></li>
          <li role="presentation"><a href="#drive" aria-controls="drive" role="tab" data-toggle="tab">View expired ad</a></li>
          <li role="presentation"><a href="#bid" aria-controls="bid" role="tab" data-toggle="tab">Popular ad of the week</a></li>
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
          <!--            -->

      <div role="tabpanel" class="tab-pane active" id="home">
      
      <?php
      //retrieve basic information about the user
      $sql = "SELECT * FROM administrators WHERE icnum = '$_SESSION[icnum]'";
      $result = pg_query($db, $sql);// Query template
      //show error 
      if (!$result) {
        echo "<p align='center'>Oops, an error has occured! You can try again.</p>";
      } else {
        //nothing
      }
      //display retrieved information 
      while ($row = pg_fetch_assoc($result)) {
        echo "<div align='center'>";
        echo "<h2> This is your basic profile information: </h2>";
        echo "<br>";
        echo "First Name: ";
        echo $row['firstname'];
        echo "<br>";
        echo "<br>";
        echo "Last Name: ";
        echo $row['lastname'];
        echo "<br>";
        echo "<br>";
        echo "Email: ";
        echo $row['email'];
        echo "<br>";
        echo "<br>";
        echo "Phone Number: ";
        echo $row['phonenum']; 
        echo "</div>";
      }
      
       //retrieve ad posting information about the user
       $sql = "SELECT DISTINCT uaa.adid, uaa.origin, uaa.destination, uaa.doa 
               FROM ((administrators u natural left join advertise a ) natural join advertisements) as uaa
               WHERE uaa.icnum = '$_SESSION[icnum]'";
       $result = pg_query($db, $sql);// Query template
       //show error 
       if (!$result) {
        echo '<script language="javascript">';
        echo 'alert("Oops, an error has occured! You can try again!")';
        echo '</script>';
       } else {
        //nothing
       }

       //display retrieved ad posting information 
       echo "<h2 align='center'> This is your ad posting information: </h2>";
       echo "<p align='center'> AD ID,  Origin, Destination, Date of Advertisement</p>";
       while ($row = pg_fetch_assoc($result)) {
         echo "<div align='center'>";
         echo "<br>";
         echo "Ad ID: ";
         echo $row['adid'];
         echo "<br>";
         echo "<br>";
         echo "Origin: ";
         echo $row['origin'];
         echo "<br>";
         echo "<br>";
         echo "Destination: ";
         echo $row['destination'];
         echo "<br>";
         echo "<br>";
         echo "Date of advertisement: ";
         echo $row['doa']; 
         echo "</div>";
       }    

      //check if the user is a driver
      $sql = "SELECT DISTINCT * 
               FROM drive d
               WHERE d.icnum = '$_SESSION[icnum]'";
       $result = pg_query($db, $sql);// Query template
       //show error 
       if (!$result) {
         echo "<p align='center'>Oops, an error has occured! You can try again.</p>";
       } else {
         //echo "<p align='center'>Yay, you have successfully post an ad!</p>";
       }

       $row = pg_fetch_assoc($result);
       if(empty($row)){
          echo "<h2 align='center'>You are a rider too!</h2>";
       } else {
         echo "<h2 align='center'>You are a driver too!</h2>";

          $sql = "SELECT * FROM cars natural join drive WHERE icnum = '$_SESSION[icnum]' ";
          $result = pg_query($db, $sql);// Query template
          //show error 
          if (!$result) {
            echo '<script language="javascript">';
            echo 'alert("Oops, an error has occured! You can try again!")';
            echo '</script>';
          } else {
            //nothing
          }

          while ($row = pg_fetch_assoc($result)) {
            echo "<div align='center'>";
            echo "<br>";
            echo "Plate Number: ";
            echo $row['platenum'];
            echo "<br>";
            echo "<br>";
            echo "Model: ";
            echo $row['models'];
            echo "<br>";
            echo "<br>";
            echo "Number of seats: ";
            echo $row['numseats'];
            echo "<br>";
            echo "<br>";
            echo "</div>";
          }    

       }

      ?>
      
      </div>

      <!--     delete a user      -->
      <div role="tabpanel" class="tab-pane" id="delete">
        <div align='center'></div>

        <div align='center'>
          <ul>
            <form class="form" action="adminManagePage#delete.php" method="POST">
              <h2 class="form-heading">Delete an user</h2>
              <input type="text" name="icnum" class="form-control" placeholder="User IC Number">
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="deleteusers">Delete</button>
              <button class="btn btn-lg btn-primary btn-block" name="displayusers">Display all users</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'adminManagePage.php';" >Back</button>
            </form>
          </ul>
        </div>

        <?php
        if (isset($_POST['displayusers'])) {
            //firstly display all the users 
            $sql = "SELECT username, firstname, lastname, icnum FROM users";
            $result = pg_query($db, $sql);
        
            if (!$result) {
              echo '<script language="javascript">';
              echo 'alert("An error occurred.")';
              echo '</script>';
                exit;
            }

            // display all users in the database
            $ids = 'ids';
            while ($row = pg_fetch_assoc($result)) {
                echo "<div align='center'>";
                echo "User name: ";
                echo $row['username'];
                echo "  ";
                echo "First name: ";
                echo $row['firstname'];
                echo "  ";
                echo "Last name: ";
                echo $row['lastname'];
                echo "  ";
                echo "IC number:  ";
                echo $row['icnum']; 
                echo "</div>";
            }
        }

        if (isset($_POST['deleteusers'])) {
            //secondly we delete according to the IC number
            $result = pg_query($db, "DELETE FROM users WHERE icnum = '$_POST[icnum]'");		// Query template
        
            if (!$result) {
              echo '<script language="javascript">';
              echo 'alert("Delete failed.")';
              echo '</script>';
            } else {
              echo '<script language="javascript">';
              echo 'alert("The user is deleted.")';
              echo '</script>';
            }
        }
        
        ?>
    </div>

    
    <div role="tabpanel" class="tab-pane" id="messages">   
      <div align='center'></div>
          <ul>
            <form class="form" action="adminManagePage.php" method="POST">
              <h2 class="form-heading" align = "center">Bidpoints for valid ads</h2>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="view">view</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'adminManagePage.php';" >Back</button>
            </form>
          </ul>
      </div>
      <?php
      if (isset($_POST['view'])) {
            //show all VALID ads
            $sql = "SELECT DISTINCT * 
                    FROM (
                        SELECT adid, count(bidpoints) as points
                        FROM bid
                        GROUP BY adid
                    ) AS combined natural join advertisements
                    ORDER BY points DESC";
            $result = pg_query($db, $sql);
            if (!$result) {
              echo '<script language="javascript">';
              echo 'alert("An error occurred.")';
              echo '</script>';
              exit;
            }

            while ($row = pg_fetch_assoc($result)) {
                echo "<div align='center'>";
                echo "ad id: ";
                echo $row['id'];
                echo " points: ";
                echo $row['points'];
                echo "ad origin: ";
                echo $row['origin'];
                echo "ad destination: ";
                echo $row['destination'];
                echo "ad date: ";
                echo $row['doa'];
                echo "</div>";
            }
        }
      ?>
       
    <!--View expired ad section-->
    <div role="tabpanel" class="tab-pane" id="drive">   
      <div align='center'></div>
          <ul>
            <form class="form" action="adminManagePage.php" method="POST">
              <h2 class="form-heading" align = "center">Expired Ads for past 2 weeks</h2>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="viewex">view</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'adminManagePage.php';" >Back</button>
            </form>
          </ul>
          <?php
    if (isset($_POST['viewex'])) {
        //show all VALID ads
        $sql = "SELECT DISTINCT * 
                FROM (
                    SELECT adid, count(bidpoints) as points
                    FROM bid
                    GROUP BY adid
                ) AS combined natural join advertisements
                WHERE CURRENT_TIMESTAMP - doa > '14'";
        $result = pg_query($db, $sql);
        if (!$result) {
            echo '<script language="javascript">';
            echo 'alert("An error occurred.")';
            echo '</script>';
            exit;
        }

        while ($row = pg_fetch_assoc($result)) {
            echo "<div align='center'>";
            echo "ad id: ";
            echo $row['id'];
            echo " points: ";
            echo $row['points'];
            echo "ad origin: ";
            echo $row['origin'];
            echo "ad destination: ";
            echo $row['destination'];
            echo "ad date: ";
            echo $row['doa'];
            echo "</div>";
        }
    }
    ?>   
    </div>

     <!--View popular ads-->
     <div role="tabpanel" class="tab-pane" id="bid">   
      <div align='center'></div>
          <ul>
            <form class="form" action="adminManagePage.php" method="POST">
              <h2 class="form-heading" align = "center">Populard Ads for last weeks</h2>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="viewpop">view</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'adminManagePage.php';" >Back</button>
            </form>
          </ul>
          <?php
    if (isset($_POST['viewpop'])) {
        //show all VALID ads
        $sql = "SELECT DISTINCT * 
                FROM (
                    SELECT adid, count(bidpoints) as points
                    FROM bid
                    GROUP BY adid
                ) AS combined natural join advertisements
                WHERE CURRENT_TIMESTAMP - doa <= '7' 
                ORDER BY points DESC
                LIMIT 1;";

        $result = pg_query($db, $sql);
        if (!$result) {
          echo '<script language="javascript">';
          echo 'alert("An error occurred.")';
          echo '</script>';
          exit;
        }

        if(empty(pg_fetch_assoc($result))) { 
          echo '<script language="javascript">';
          echo 'alert("It seems that no one has posted any advertosements this week.")';
          echo '</script>';
        }
        else {
            while ($row = pg_fetch_assoc($result)) {
                echo "<div align='center'>";
                echo "ad id: ";
                echo $row['id'];
                echo " points: ";
                echo $row['points'];
                echo "ad origin: ";
                echo $row['origin'];
                echo "ad destination: ";
                echo $row['destination'];
                echo "ad date: ";
                echo $row['doa'];
                echo "</div>";
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
  <script type="text/javascript">
    $(function () {
        $('#datetimepicker3').datetimepicker({
          format: 'LT'
        });
    });
  </script>

</body>
</html>