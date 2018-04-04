<?php  session_start(); ?>

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

//   //forth function -- select bidders
//   //show all VALID ads
//   if (isset($_POST['select'])) {
//     $sql = "SELECT b.adid, a.origin, a.destination, a.doa, icnum, bidpoints, status 
//             FROM bid b, advertisements a
//             WHERE status = 'Not Selected' AND b.adid = a.adid
//             ORDER BY b.adid";
//     $result = pg_query($db, $sql);

//     if (!$result) {
//         echo "An error occurred.\n";
//         exit;
//     }

//     // display NOT SELECTED bidders for each advertisement
//     $ids = 'ids';
//     while ($row = pg_fetch_assoc($result)) {
//         echo "<div align='center'>";
//         echo $row['adid'];
//         
//          
//         echo $row['origin'];
//         echo $row['destination'];
//         echo $row['doa']; 
//         echo $row['icnum'];
//         echo $row['bidpoints'];
//         echo $row['status']; 
//         echo "</div>";
//     }

//     // display a form for user to input an id
//     echo
//     "<div align='center'>
//     <ul><form name='update' action='user.php' method='POST' >
//     <li>Select an ADID:</li>
//     <li><input type='text' name='adid' value='$row[adid]' /></li>
//     <li>Select an ICNUM:</li>
//     <li><input type='text' name='icnum' value='$row[icnum]' /></li>
//     <li><input type='submit' name='select' value = 'select a bidder at a time'/></li>
//     </form>
//     </ul>
//     </div>";

//     //Submit add query
//   if (isset($_POST['select'])) {	// Submit the update SQL command
//     //check whether the user has bid this ad before; duplication is not allowed
//     $sql = "UPDATE bid
//             SET status = 'Selected'
//             WHERE icnum = '$_POST[icnum]' and adid = $_POST[adid]";
//     $result = pg_query($db, $sql); 
  
//     if (!$result) {
//       echo "An error occurred.\n";
//       exit;
//     } else {
//       echo "<div align='center'>You have choosen a bidder! (It seems that you have to refresh the page to update the bidder info, 
//       we are sorry about the inconvenience and will fix this soon.) \n</div>";
//     }
//   }
   
// }

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
          <li role="presentation"><a href="#post" aria-controls="post" role="tab" data-toggle="tab">Post Ad</a></li>
          <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Select Bidder</a></li>
          <li role="presentation"><a href="#drive" aria-controls="drive" role="tab" data-toggle="tab">Apply Driver</a></li>
          <li role="presentation"><a href="#bid" aria-controls="bid" role="tab" data-toggle="tab">Bid Ad</a></li>
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
               FROM ((users u natural left join advertise a ) natural join advertisements) as uaa
               WHERE uaa.icnum = '$_SESSION[icnum]'";
       $result = pg_query($db, $sql);// Query template
       //show error 
       if (!$result) {
        echo '<script language="javascript">';
        echo 'alert("Oops, please try again!")';
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
      
      // $i = 0;
      // echo "<html><body><table align='center'><tr>";
      // while ($i < pg_num_fields($result))
      // {
      //   $fieldName = pg_field_name($result, $i);
      //   echo '<td>  ' . $fieldName . '</td> ';
      //   $i = $i + 1;
      // }
      // echo '</tr> ';
      // $i = 0;
      
      // while ($row = pg_fetch_row($result)) 
      // {
      //   echo '<tr>';
      //   $count = count($row);
      //   $y = 0;
      //   while ($y < $count)
      //   {
      //     $c_row = current($row);
      //     str_pad($c_row,2,"  ");
      //     echo '<td>' . str_pad($c_row,2,"  ") . '</td>'  ;
      //     next($row);
      //     $y = $y + 1;
      //   }
      //   echo '</tr>';
      //   $i = $i + 1;
      // }
      // pg_free_result($result);
      
      // echo '</table></body></html>';


      //check if the user is a driver
      $sql = "SELECT DISTINCT * 
               FROM drive d
               WHERE d.icnum = '$_SESSION[icnum]'";
       $result = pg_query($db, $sql);// Query template
       //show error 
       if (!$result) {
        echo '<script language="javascript">';
        echo 'alert("Oops, please try again!")';
        echo '</script>';
       } else {
         //nothing
       }

       $row = pg_fetch_assoc($result);
       if(empty($row)){
          echo "<h2 align='center'>You are a rider!</h2>";
       } else {
         echo "<h2 align='center'>You are a driver!</h2>";

          $sql = "SELECT * FROM cars natural join drive WHERE icnum = '$_SESSION[icnum]' ";
          $result = pg_query($db, $sql);// Query template
          //show error 
          if (!$result) {
            echo "<p align='center'>Oops, an error has occured! You can try again.</p>";
          } else {
            //echo "<p align='center'>Yay, you have successfully post an ad!</p>";
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

            <!--            -->

      <div role="tabpanel" class="tab-pane" id="post">
        <div align='center'>
        </div>

        <div align='center'>
          <ul>
            <form class="form" action="user.php" method="POST">
              <h2 class="form-heading">Post an advertisement</h2>
              <input type="text" name="origin" class="form-control" placeholder="Origin" required autofocus>
              <input type="text" name="destination" class="form-control" placeholder="Destination" required>
              <!--<div class='input-group date' id='datetimepicker3'>
                <input type='text' class="form-control" />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-time"></span>
                </span>
              </div> -->
              <input type="text" name="doa" class="form-control" placeholder="Date of traveling (YYYY-MM-DD)" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="ads">Apply</button>
              <button class="btn btn-lg btn-block" onclick="location.href = 'user.php';" >Back</button>
            </form>
          </ul>
        </div>

        </div>

        <!--
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
        
        -->

      <!--   bid         -->
      <div role="tabpanel" class="tab-pane" id="messages">
      <?php
          $sql = "SELECT b.adid, a.origin, a.destination, a.doa, icnum, bidpoints, status 
                  FROM bid b, advertisements a
                  WHERE status = 'Not Selected' AND b.adid = a.adid
                  ORDER BY b.adid";
          $result = pg_query($db, $sql);
      
          if (!$result) {
              echo '<script language="javascript">';
              echo 'alert("Oops, please try again!")';
              echo '</script>';
              exit;
          }

          // display NOT SELECTED bidders for each advertisement
          $ids = 'ids';
          while ($row = pg_fetch_assoc($result)) {
            echo "<div align='center'>";
            echo $row['adid'];
            echo "  ";
            // $thisId = $row['adid'];
            /* echo "<Input type = 'Radio' Name ='adid' value= 'id'<?PHP print $$ids = $thisId;?>>";*/
            echo $row['origin'];
            echo "  ";
            echo $row['destination'];
            echo "  ";
            echo $row['doa']; 
            echo "  ";
            echo $row['icnum'];
            echo "  ";
            echo $row['bidpoints'];
            echo "  ";
            echo $row['status']; 
            echo "</div>";
          }
      ?>
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
       //Submit add query
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

    <!--     drive       -->
    </div>
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