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
      <li><input type="submit" name="check" value="Check the last user" /></li>
      <li><input type="submit" name="add" value="Add new users" /></li>
      <li><input type="submit" name="delete" value="Delete a user" /></li>
    </form>
  </ul>
</div>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
    $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");	
    //first function
    if (isset($_POST['check'])) {
        $result = pg_query($db, "SELECT * FROM users");		// Query template
        $row    = pg_fetch_assoc($result);		// To store the result row

        echo 
        "<div align='center'>
        <li>User name:</li>  
    	<li><input type='text' name='username' value='$row[username]' /></li>  
    	<li>Phone Number:</li>  
    	<li><input type='text' name='phonenum' value='$row[phonenum]' /></li>  
        <li>IC Number:</li>
        <li><input type='text' name='icnum' value='$row[icnum]' /></li>  
    	<li>Email:</li>  
        <li><input type='text' name='email' value='$row[email]' /></li>
        </div>";
    }
    //second function
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
    //Third function
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
    
    ?>  
</body>
</html>
