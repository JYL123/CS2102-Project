<!DOCTYPE html>  
<head>
  <title>UPDATE PostgreSQL data with PHP</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>li {list-style: none;}</style>
  <link rel="stylesheet" href="background.css">
</head>
<body>
  <h2>User Page</h2>
  <ul>
    <form name="display" action="user.php" method="POST" >
      <li>As an Admin, you can perform:</li>
      <li><input type="submit" name="apply" value="Apply to be a driver (add car in database)" /></li>
      <li><input type="submit" name="post" value="Post an advertisement" /></li>
      <li><input type="submit" name="bid" value="Bid for an advertisement" /></li>
    </form>
  </ul>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
    $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");	

    //first function - Application of being a driver
    if (isset($_POST['apply'])) {

        echo "The first step to become a driver, you have to fill in the following information:";

        echo 
        "<ul><form name='update' action='user.php' method='POST' > 
        <li>Vehicle Plate Number:</li>  
    	<li><input type='text' name='platenum' value='$row[platenum]' /></li>  
    	<li>Vehicle Model:</li>  
    	<li><input type='text' name='models' value='$row[models]' /></li>  
        <li>Number of seats:</li>
        <li><input type='text' name='numseats' value='$row[numseats]' /></li>  

        <li><input type='submit' name='cars'/></li> 
        </form>  
    	</ul>";
    }
    //Submit add query
    if (isset($_POST['cars'])) {	// Submit the update SQL command
        $result = pg_query($db, "INSERT INTO cars (platenum, models, numseats) VALUES ('$_POST[platenum]', '$_POST[models]', '$_POST[numseats]')");		// Query template
        if (!$result) {
            echo "Oops, please try again!";
        } else {
            echo "Yay, you have successfully become a driver!";
        }
    }

    //second function - post an advertisement 
    if (isset($_POST['post'])) {

        echo "The first step to post an advertisement, you have to fill in the following information:";

        echo 
        "<ul><form name='update' action='user.php' method='POST' > 
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
    	</ul>";
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
        $idresult = pg_query($db, "SELECT adid FROM advertisements ORDER BY adid DESC LIMIT 1 ");// Query template
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
        $result = pg_query($db, "SELECT * FROM advertisements WHERE EXISTS (SELECT 1 FROM advertise WHERE advertisements.adid = advertise.adid)");		// Query template

        if (!$result) {
            echo "An error occurred.\n";
            exit;
        }

        while ($row = pg_fetch_assoc($result)) {
            echo $row['adid'];
            echo $row['origin'];
            echo $row['destination'];
            echo $row['doa'];
            echo "<br>";
        }

        //ask users to select an adid to bid
        echo "The first step to bid, you have to fill in the following information:";

        echo 
        "<ul><form name='update' action='user.php' method='POST' > 
        <li>Advertisement ID:</li>  
    	<li><input type='text' name='adid' value='$row[adid]' /></li>  
    	<li>Your icnum:</li>  
    	<li><input type='text' name='icnum' value='$row[icnum]' /></li>  

        <li><input type='submit' name='bidad'/></li> 
        </form>  
    	</ul>";
    }

    //Submit add query
    if (isset($_POST['bidad'])) {	// Submit the update SQL command
        //check whether the user has bid this ad before; duplication is not allowed
        $userresult = pg_query($db, "SELECT * FROM bid WHERE adid = $_POST[adid] AND icnum = '$_POST[icnum]'");
        if (!$userresult) {

        // by default, each user can contain bid i point for each ad
        $result = pg_query($db, "INSERT INTO bid (adid, icnum, bidpoints) VALUES ($_POST[adid],'$_POST[icnum]', 1)");		// Query template
        if (!$result) {
            echo "Oops, please try again!";
        } else {
            echo "Yay, you have successfully set a bid point!";
        }
      } else {
          // duplication for bidding an ad is not allowed.
        echo "You have already bid for this ad. You can bid for a new ad.";
      } 
     
    }
 
    ?>  
</body>
</html>
