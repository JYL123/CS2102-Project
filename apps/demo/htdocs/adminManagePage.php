<?php  session_start();
?>

<?php
	// Connect to the database. Please change the password in the following line accordingly
  $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");

  if(!isset($_SESSION['user'])) // If session is not set then redirect to Login Page
   {
       header("Location: adminPortal.php");
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

    <title>RouteSharing</title>

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
        <a class="navbar-brand" href="#">RouteSharing</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav" role="tablist">
          <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
          <li role="presentation"><a href="#delete" aria-controls="delete" role="tab" data-toggle="tab">Delete users</a></li>
          <li role="presentation"><a href="#viewBidpoint" aria-controls="viewBidpoint" role="tab" data-toggle="tab">View ad bidpoints</a></li>
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
                          $sql = "SELECT * FROM administrators WHERE icnum = '$_SESSION[icnum]'";
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
                            echo "<td><a href='mailto:$email'>$email</a></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>Phone</td>";
                            echo "<td>" . $row['phonenum'] . "</td>";
                            echo "</tr>";
                          }
                        ?>
                        <!-- Total no. of users -->
                        <tr>
                          <td>Total Users</td>
                          <td>
                            <?php
                              $sql = "SELECT count(icnum) FROM users";
                              $result = pg_query($db, $sql);
                              $row = pg_fetch_assoc($result);
                              echo $row['count'];
                            ?>
                          </td>
                        </tr>

                        <!-- Total no. of drivers -->
                        <tr>
                          <td>Total Drivers</td>
                          <td>
                            <?php
                              $sql = "SELECT count(DISTINCT icnum)
                                      FROM drive NATURAL JOIN users";
                              $result = pg_query($db, $sql);
                              $row = pg_fetch_assoc($result);
                              echo $row['count'];
                            ?>
                          </td>
                        </tr>

                        <!-- Total no. of advertisments -->
                        <tr>
                          <td>Total Ads</td>
                          <td>
                            <?php
                              $sql = "SELECT count(*) FROM advertisements";
                              $result = pg_query($db, $sql);
                              $row = pg_fetch_assoc($result);
                              echo $row['count'];
                            ?>
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

      <!--     delete a user      -->
      <div role="tabpanel" class="tab-pane" id="delete">
      <table class="table table-user-information">
          <thead>
            <tr>
              <th>Username</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>IC Number</th>
              <th>Email</th>
              <th>Phone</th>
            </tr>
          </thead>
          <tbody>
            <?php
              //retrieve ad posting information about the user
              $sql = "SELECT username, firstname, lastname, icnum, email, phonenum FROM users";
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
                echo "<th>" . $row['username'] . "</th>";
                echo "<th>" . $row['firstname'] . "</th>";
                echo "<th>" . $row['lastname'] . "</th>";
                echo "<th>" . $row['icnum'] . "</th>";
                echo "<th>" . $row['email'] . "</th>";
                echo "<th>" . $row['phonenum'] . "</th>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>

        <div align='center'></div>
        <div align='center'>
          <ul>
            <form class="form" action="adminManagePage#delete.php" method="POST">
              <h2 class="form-heading">Delete an user</h2>
              <input type="text" name="icnum" class="form-control" placeholder="User IC Number">
              <button class="btn btn-lg btn-warning btn-block" type="submit" name="deleteusers">Delete</button>
              <button class="btn btn-lg btn-info btn-block" onclick="location.href = 'adminManagePage.php';" >Back</button>
            </form>
          </ul>
        </div>

        <?php
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
              echo "<script> window.location.replace('adminManagePage.php') </script>";
            }
        }

        ?>
    </div>

    <!-- View all maxx bidpoints -->
    <div role="tabpanel" class="tab-pane" id="viewBidpoint">
      <div align='center'></div>
         <h2 class="form-heading" align = "center">Max Bidpoints for valid ads</h2>
         <table class="table table-user-information">
          <thead>
            <tr>
              <th>Ad ID</th>
              <th>Point</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
              //show all VALID ads with max bidpoints
              $sql = "SELECT DISTINCT *
              FROM (
                  SELECT adid, max(bidpoints) as points
                  FROM bid
                  GROUP BY adid
              ) AS combined natural join advertisements
              ORDER BY points DESC";
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
                echo "<th>" . $row['points'] . "</th>";
                echo "<th>" . $row['origin'] . "</th>";
                echo "<th>" . $row['destination'] . "</th>";
                echo "<th>" . $row['doa'] . "</th>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
          <ul>
            <form class="form" action="adminManagePage.php" method="POST">
              <button class="btn btn-lg btn-info btn-block" onclick="location.href = 'adminManagePage.php';" >Back</button>
            </form>
          </ul>
      </div>

    <!--View expired ad section-->
    <div role="tabpanel" class="tab-pane" id="drive">
      <div align='center'></div>
          <h2 class="form-heading" align = "center">10 Most Recent Expired Ads for Past Weeks</h2>
          <table class="table table-user-information">
          <thead>
            <tr>
              <th>Ad ID</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
              //most recent 10 expired ads
              $sql = "SELECT DISTINCT *
                      FROM (
                        SELECT adid, max(bidpoints) as points
                        FROM bid
                        GROUP BY adid
                      ) AS combined natural join advertisements
                      WHERE CURRENT_TIMESTAMP - doa > '7 day'::interval
                      ORDER by doa DESC
                      LIMIT 10";
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
          <ul>
            <form class="form" action="adminManagePage.php" method="POST">
              <button class="btn btn-lg btn-info btn-block" onclick="location.href = 'adminManagePage.php';" >Back</button>
            </form>
          </ul>
    </div>

     <!--View popular ads-->
     <div role="tabpanel" class="tab-pane" id="bid">
      <div align='center'></div>
      <h2 class="form-heading" align = "center">Top 10 Popular Ads for Past Weeks</h2>
      <table class="table table-user-information">
          <thead>
            <tr>
              <th>Ad ID</th>
              <th>Point</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql = "SELECT DISTINCT *
              FROM (
              		SELECT adid, max(bidpoints) as points
              		FROM bid
              		GROUP BY adid
              ) AS combined natural join advertisements
              WHERE doa::timestamp <= CURRENT_TIMESTAMP - INTERVAL '7 days'
              ORDER BY points DESC
              LIMIT 10";
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
                echo "<th>" . $row['points'] . "</th>";
                echo "<th>" . $row['origin'] . "</th>";
                echo "<th>" . $row['destination'] . "</th>";
                echo "<th>" . $row['doa'] . "</th>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
          <ul>
            <form class="form" action="adminManagePage.php" method="POST">
              <button class="btn btn-lg btn-info btn-block" onclick="location.href = 'adminManagePage.php';" align = "center">Back</button>
            </form>
          </ul>
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
