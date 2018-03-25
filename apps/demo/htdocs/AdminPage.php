<!DOCTYPE html>  
<head>
  <title>UPDATE PostgreSQL data with PHP</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>li {list-style: none;}
  #nav {
  background-color: #C08374;
  border: 1px solid #A76358;
  text-align: center;
  }
  ul {
  list-style: none;
  display: inline-block;
  }
  ul li {
  float: none;
  margin: 0 20px;
  }
  ul li a {
  color: white;
  }
  </style>
  <link rel="stylesheet" href="background.css">
</head>
<body>
<div id="nav">
    <h2>Admin Page</h2>
</div>
<div align="center">  
  <ul>
    <form name="display" action="AdminPage.php" method="POST" >
      <li>As an Admin, you can perform:</li>
      <li><input type="submit" name="add" value="Add new users" /></li>
      <li><input type="submit" name="delete" value="Delete a user" /></li>
      <li><input type="submit" name="view" value="View ads bidpoint" /></li>
      <li><input type="submit" name="viewex" value="View expired ad" /></li>
      <li><input type="submit" name="viewpop" value="View most popular ad of this week" /></li>
    </form>
  </ul>
</div>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
    $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");	
    //first function
    if (isset($_POST['add'])) {
        echo 
        "<div align='center'>
        <ul><form name='update' action='AdminPage.php' method='POST' >  
    	<li>User Name:</li>  
    	<li><input type='text' name='username' value='$row[username]' /></li>  
    	<li>Password:</li>  
        <li><input type='text' name='userpassword' value='$row[userpassword]'/></li>
        <li>IC Number:</li>  
        <li><input type='text' name='icnum' value='$row[icnum]'/></li>
        <li>First Number:</li>  
        <li><input type='text' name='firstname' value='$row[firstname]'/></li> 
        <li>Last Number:</li>  
        <li><input type='text' name='lastname' value='$row[lastname]'/></li>
        <li>Email:</li>  
        <li><input type='text' name='email' value='$row[email]'/></li>
        <li>Phone Number:</li>  
    	<li><input type='text' name='phonenum' value='$row[phonenum]'/></li>  
    	<li><input type='submit' name='newuser'/></li>  
    	</form>  
        </ul>
        </div>";

        echo 
        "<div align='center'>Fill in necessary information please.</div>";
    }

    if (isset($_POST['newuser'])) {	// Submit the update SQL command
        $result = pg_query($db, "INSERT INTO users VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]',  
                    '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]'");
        if (!$result) {
            echo "Update failed!!";
        } else {
            echo "Update successful!";
        }
    }
    
    //second function
    if (isset($_POST['delete'])) {
        echo 
        "<div align='center'>
        <ul><form name='update' action='AdminPage.php' method='POST' >  
    	<li>User IC Number:</li>  
        <li><input type='text' name='icnum' value='$row[icnum]' /></li>  
        <li><input type='submit' name='deleteuser'/></li> 
    	</form>  
        </ul>
        </div>";

    }
    if (isset($_POST['deleteuser'])) {	// Submit the update SQL command
        $result = pg_query($db, "DELETE FROM users WHERE icnum = '$_POST[icnum]'");		// Query template
        
        if (!$result) {
            echo "Delete failed!!";
        } else {
            echo "Delete successful!";
        }
    }

    //third function TODO: can be replaced by a function or procedure
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
            echo "An error occurred.\n";
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

    //forth function TODO: can be replaced by a function or procedure
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
            echo "An error occurred.\n";
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

    //fifith function -- retrieve the most popular ad (highest bid point)
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
            echo "An error occurred.\n";
            exit;
        }

        if(empty(pg_fetch_assoc($result))) { 
            echo "<div align='center'>";
            echo "It seems that no one has posted any advertosements this week"; 
            echo "</div>";
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
</body>
</html>
